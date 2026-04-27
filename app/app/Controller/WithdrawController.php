<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Request\WithdrawRequest;
use App\Service\WithdrawService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: "")]
class WithdrawController
{
    public function __construct(private WithdrawService $service){}

    #[PostMapping(path: "/account/{accountId}/balance/withdraw")]
    public function withdraw(string $accountId, WithdrawRequest $request) {
        $data = $request->validated();

        $this->service->createWithdraw($accountId, $data);

        return ['message' => 'Withdraw request created successfully'];
    }
}
