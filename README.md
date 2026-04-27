# 📌 PIX Withdraw Service

Plataforma de conta digital que permite realizar saques via PIX, com suporte a processamento imediato e agendado.

---

## 🚀 Tecnologias utilizadas

- PHP com Hyperf
- MySQL 8
- Docker + Docker Compose
- Mailhog (teste de envio de emails)
- Symfony Mailer (SMTP)

---

## 🧱 Arquitetura

O sistema foi estruturado com foco em:

- Separação de responsabilidades (Service Layer)
- Baixo acoplamento
- Facilidade de extensão (novos métodos de saque)
- Processamento assíncrono via cron

### Componentes principais

- `WithdrawService` → regra de negócio de saque
- `NotificationService` → envio de email
- `ProcessScheduledWithdraw` → processamento via cron

---

## ⚙️ Como rodar o projeto

Clone o repositório:

```bash
git clone <repo>
cd <repo>

docker-compose up -d --build

docker exec -it hyperf-skeleton composer install

docker exec -it hyperf-skeleton php bin/hyperf.php migrate

```

## A aplicação estará disponível em:

```bash
http://localhost:9501
```

Mailhog:

```bash
http://localhost:8025
```

## Endpoint de saque

POST `/account/{accountId}/balance/withdraw`

```bash
{
  "method": "PIX",
  "pix": {
    "type": "email",
    "key": "user@email.com"
  },
  "amount": 100.00,
  "schedule": null
}
```

Dentro do container do mySql

```bash
INSERT INTO account (id, name, balance, created_at, updated_at)
VALUES ('1', 'Henrique', 200, NOW(), NOW());
```

O projeto está totalmente dockerizado e reproduzível. Basta subir os containers e rodar as migrations.
