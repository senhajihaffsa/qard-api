<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class QardApiService
{
    private HttpClientInterface $client;
    private string $apiKey;
    private string $baseUrl;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->apiKey = $params->get('QARD_API_KEY');
        $this->baseUrl = rtrim($params->get('QARD_API_BASE_URL'), '/');
    }

    public function createLegalUser(string $name, string $siren): ?array
    {
        $url = $this->baseUrl . '/api/v6/users/legal';

        try {
            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'name' => $name,
                    'siren' => $siren,
                ],
            ]);

            if ($response->getStatusCode() !== Response::HTTP_CREATED) {
                return null;
            }

            return $response->toArray();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function syncUser(string $userId): bool
    {
        $url = $this->baseUrl . "/api/v6/users/{$userId}/sync";

        try {
            $this->client->request('POST', $url, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'accept' => 'application/json',
                ]
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserProfile(string $userId): ?array
    {
        $url = $this->baseUrl . "/api/v6/users/{$userId}";

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'accept' => 'application/json',
                ]
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUserOfficers(string $userId): ?array
    {
        $url = $this->baseUrl . "/api/v6/users/{$userId}/company-officers/all";

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'accept' => 'application/json',
                ]
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUserFinancialStatements(string $userId): ?array
    {
        $url = $this->baseUrl . "/api/v6/users/{$userId}/financial-statements";

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'accept' => 'application/json',
                ]
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            return null;
        }
    }
}
