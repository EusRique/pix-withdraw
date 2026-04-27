<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class WithdrawRequest extends FormRequest
{
   public function rules(): array
   {
        return [
            'method' => 'required|string|in:PIX',
            'amount' => 'required|numeric|min:0.01',

            'pix.type' => 'required|string|in:email',
            'pix.key' => 'required|email',

            'schedule' => 'nullable|date'
        ];
   }
}
