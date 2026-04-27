<?php

namespace App\Service;

use Hyperf\DbConnection\Db;
use Ramsey\Uuid\Uuid;
use Exception;
use App\Service\NotificationService;

class WithdrawService
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function createWithdraw(string $accountId, array $data): void
    {
        Db::transaction(function () use ($accountId, $data) {

            $amount = $data['amount'];
            $isScheduled = !empty($data['schedule']);

            // 1. Cria o saque
            $withdrawId = Uuid::uuid4()->toString();

            Db::table('account_withdraw')->insert([
                'id' => $withdrawId,
                'account_id' => $accountId,
                'method' => $data['method'],
                'amount' => $amount,
                'status' => 'PENDING',
                'scheduled' => $isScheduled,
                'scheduled_for' => $data['schedule'] ?? null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            Db::table('account_withdraw_pix')->insert([
                'account_withdraw_id' => $withdrawId,
                'type' => $data['pix']['type'],
                'key' => $data['pix']['key'],
            ]);

            // 2. Se NÃO for agendado → processa agora
            if (!$isScheduled) {
                $this->processWithdraw($withdrawId, $accountId, $amount);
            }
        });
    }

    public function processWithdraw(string $withdrawId, string $accountId, float $amount): void
    {
        // 1. Tenta debitar saldo de forma
        $updated = Db::update(
            'UPDATE accounts SET balance = balance - ? WHERE id = ? AND balance >= ?',
            [$amount, $accountId, $amount]
        );

        // 2. Se não conseguiu → saldo insuficiente
        if ($updated === 0) {
            Db::table('account_withdraw')
                ->where('id', $withdrawId)
                ->update([
                    'status' => 'FAILED',
                    'error_reason' => 'Insufficient balance',
                    'processed_at' => date('Y-m-d H:i:s'),
                ]);

            return;
        }

        // 3. Sucesso
        Db::table('account_withdraw')
            ->where('id', $withdrawId)
            ->update([
                'status' => 'DONE',
                'processed_at' => date('Y-m-d H:i:s'),
            ]);

        $pixKey = $this->getPixKey($withdrawId);
        $this->notificationService->sendWithdrawEmail(
            $pixKey,
            [
                'amount' => $amount,
                'date' => date('Y-m-d H:i:s'),
                'pix_type' => 'email',
                'pix_key' => $pixKey,
            ]
        );
    }

    private function getPixKey(string $withdrawId): string
    {
        return Db::table('account_withdraw_pix')
            ->where('account_withdraw_id', $withdrawId)
            ->value('key');
    }
}