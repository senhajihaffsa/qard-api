<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class QardApiService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey,
        private string $baseUrl
    ) {}

    public function getUsers(): array
    {
        $response = $this->client->request('GET', "$this->baseUrl/api/v6/users", [
            'headers' => ['X-API-KEY' => $this->apiKey],
        ]);

        return $response->toArray()['result'] ?? [];
    }

    public function getUserProfile(string $userId): ?array
    {
        $response = $this->client->request('GET', "$this->baseUrl/api/v6/users/$userId/company-profile", [
            'headers' => ['X-API-KEY' => $this->apiKey],
        ]);

        return $response->getStatusCode() === 204 ? null : $response->toArray();
    }

    public function getCompanyOfficers(string $userId): array
    {
        $response = $this->client->request('GET', "$this->baseUrl/api/v6/users/$userId/corporate-offices", [
            'headers' => ['X-API-KEY' => $this->apiKey],
        ]);

        return $response->toArray();
    }

    public function getCompanyFinancials(string $userId): array
    {
        $response = $this->client->request('GET', "$this->baseUrl/api/v6/users/$userId/financials", [
            'headers' => ['X-API-KEY' => $this->apiKey],
        ]);

        return $response->toArray();
    }
}
