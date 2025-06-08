<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\CompanyProfile;
use App\Entity\Officer;
use App\Entity\FinancialStatement;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class QardImporterService
{
    public function __construct(
        private QardApiService $api,
        private EntityManagerInterface $em,
        private UserRepository $repo
    ) {}

    public function importUsers(): void
    {
        $users = $this->api->getAllUsers();

        foreach ($users as $data) {
            $user = $this->repo->find($data['id']) ?? new User();
            $user->setId($data['id']);
            $user->setName($data['name'] ?? '');
            $user->setSiren($data['siren'] ?? null);
            $user->setType($data['type'] ?? null);
            $user->setStatus($data['status'] ?? null);

            if (!empty($data['created_at'])) {
                $user->setCreatedAt(new \DateTime($data['created_at']));
            }

            $this->em->persist($user);
        }

        $this->em->flush();
    }

    public function importCompanyProfile(User $user): void
    {
        $profileData = $this->api->getUserProfile($user->getId());
        if (!$profileData) return;

        $profile = $user->getProfile() ?? new CompanyProfile();
        $profile->setUser($user);
        $profile->setName($profileData['name'] ?? null);
        $profile->setSiren($profileData['siren'] ?? null);
        $profile->setCountry($profileData['country'] ?? null);
        $profile->setPostalCode($profileData['postal_code'] ?? null);
        $profile->setApeCode($profileData['ape_code'] ?? null);

        $user->setProfile($profile);
        $this->em->persist($profile);
        $this->em->flush();
    }

    public function importOfficers(User $user): void
    {
        $data = $this->api->getCompanyOfficers($user->getId());
        foreach ($data['result'] ?? [] as $item) {
            $officer = new Officer();
            $officer->setUser($user);
            $officer->setName($item['name'] ?? null);
            $officer->setRole($item['role'] ?? null);
            $officer->setSince(new \DateTime($item['since'] ?? 'now'));
            $this->em->persist($officer);
        }

        $this->em->flush();
    }

    public function importFinancials(User $user): void
    {
        $data = $this->api->getCompanyFinancials($user->getId());
        foreach ($data['result'] ?? [] as $item) {
            $fs = new FinancialStatement();
            $fs->setUser($user);
            $fs->setYear($item['year'] ?? null);
            $fs->setRevenue($item['revenue']['value'] ?? 0.0);
            $fs->setNetIncome($item['net_income']['value'] ?? 0.0);
            $this->em->persist($fs);
        }

        $this->em->flush();
    }

    public function importAll(): void
    {
        $users = $this->repo->findAll();

        foreach ($users as $user) {
            $this->importCompanyProfile($user);
            $this->importOfficers($user);
            $this->importFinancials($user);
        }
    }
}
