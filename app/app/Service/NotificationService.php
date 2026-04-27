<?php

namespace App\Service;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class NotificationService
{
    public function sendWithdrawEmail(string $to, array $data): void
    {
        error_log("Entrou no envio de email");
        $dsn = 'smtp://mailhog:1025';
        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from('no-reply@pix.com')
            ->to($to)
            ->subject('Saque realizado com sucesso')
            ->text(
                "Seu saque foi realizado!\n\n" .
                "Valor: {$data['amount']}\n" .
                "Data: {$data['date']}\n" .
                "Tipo PIX: {$data['pix_type']}\n" .
                "Chave: {$data['pix_key']}\n"
            );

        $mailer->send($email);
    }
}