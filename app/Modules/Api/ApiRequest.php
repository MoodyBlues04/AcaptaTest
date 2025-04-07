<?php

namespace App\Modules\Api;

abstract class ApiRequest
{
    public function getBody(): array
    {
        return [];
    }

    public function getHeaders(): array
    {
        return [];
    }
}
