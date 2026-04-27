<?php

namespace App\Crontab;

use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\DbConnection\Db;
use App\Service\WithdrawService;

#[Crontab(rule: "*/5 * * * * *", name: "ProcessScheduledWithdraw", callback: "execute")]
class ProcessScheduledWithdraw
{
    public function __construct(
        private WithdrawService $withdrawService
    ) {}
    
    public function execute(): void
    {
        error_log("CRON rodando");
        // 1. Buscar saques pendentes que já podem ser executados
        $withdraws = Db::table('account_withdraw')
            ->where('status', 'PENDING')
            ->where('scheduled', 1)
            ->where('scheduled_for', '<=', date('Y-m-d H:i:s'))
            ->limit(10) // evita sobrecarga
            ->get();

        error_log("Encontrados: " . count($withdraws));

        foreach ($withdraws as $withdraw) {
            $this->withdrawService->processWithdraw(
                $withdraw->id,
                $withdraw->account_id,
                $withdraw->amount
            );
        }
    }

    private function process($withdraw): void
    {
        // Verificar duplicidade
    }
}