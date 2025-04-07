<?php

namespace App\Modules\Api;

class BearerRequest extends ApiRequest
{
    public function __construct(private readonly string $token)
    {
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->token}",
        ];
    }
}
