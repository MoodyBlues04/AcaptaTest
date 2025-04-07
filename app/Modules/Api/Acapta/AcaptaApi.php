<?php

namespace App\Modules\Api\Acapta;

use App\Modules\Api\BaseApi;
use App\Modules\Api\Client;
use GuzzleHttp\Exception\GuzzleException;

class AcaptaApi extends BaseApi
{
    private const BASE_URL = 'https://exsrv.asarta.ru/api/test-task';

    public function __construct(Client $client)
    {
        parent::__construct(self::BASE_URL, $client);
    }

    /**
     * @throws GuzzleException
     */
    public function getOnuData(): string
    {
        return $this->get('/get_onu_data.php', new AcaptaRequest());
    }

    /**
     * @throws GuzzleException
     */
    public function getOnuStats(): string
    {
        return $this->get('/get_onu_stats.php', new AcaptaRequest());
    }
}
