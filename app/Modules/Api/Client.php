<?php

namespace App\Modules\Api;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private GuzzleHttpClient $client;

    public function __construct()
    {
        $this->client = new GuzzleHttpClient(['http_errors' => false]);
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $url, array $data = [], array $headers = []): string
    {
        return $this->request('POST', $url, [
            'json' => $data,
            'headers' => $headers,
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $url, array $query = [], array $headers = []): string
    {
        return $this->request('GET', $url, [
            'query' => $query,
            'headers' => $headers,
        ]);
    }

    /**
     * @throws GuzzleException
     */
    protected function request(string $method, string $url, array $options): string
    {
        $response = $this->client->request($method, $url, $options);
        return $response->getBody()->getContents();
    }
}
