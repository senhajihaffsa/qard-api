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

    public function getAllUsers(): array
    {
        $allUsers = [];
        $page = 1;

        do {
            $response = $this->client->request('GET', "$this->baseUrl/api/v6/users?page=$page&per_page=50", [
                'headers' => ['X-API-KEY' => $this->apiKey],
            ]);

            $data = $response->toArray();
            $allUsers = array_merge($allUsers, $data['result'] ?? []);
            $page++;
        } while ($page <= ($data['last_page'] ?? 1));

        return $allUsers;
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
        $response = $this->client->request('GET', "$this->baseUrl/api/v6/users/$userId/financial-statements", [
            'headers' => ['X-API-KEY' => $this->apiKey],
        ]);

        return $response->toArray();
    }
}
