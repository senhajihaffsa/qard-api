<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\CompanyProfile;
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
        $users = $this->api->getAllUsers(); // Assure-toi que cette méthode gère la pagination

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

            // Appel direct au profil ici (plus logique)
            $this->importCompanyProfile($user);
        }

        $this->em->flush();
    }

    private function importCompanyProfile(User $user): void
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
    }
}
