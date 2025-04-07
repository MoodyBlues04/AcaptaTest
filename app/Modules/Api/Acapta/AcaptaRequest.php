<?php

namespace App\Modules\Api\Acapta;

use App\Modules\Api\BearerRequest;

class AcaptaRequest extends BearerRequest
{
    public function __construct()
    {
        parent::__construct(env('ACAPTA_TOKEN'));
    }
}
