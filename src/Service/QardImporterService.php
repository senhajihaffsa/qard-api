<?php

namespace App\Service;

use App\Entity\User;
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
        $users = $this->api->getUsers();
        foreach ($users as $data) {
            $user = $this->repo->find($data['id']) ?? new User();
            $user->setId($data['id']);
            $user->setName($data['name'] ?? null);
            $user->setSiren($data['siren'] ?? null);
            $user->setCreatedAt(new \DateTime($data['created_at']));
            $this->em->persist($user);
        }
        $this->em->flush();
    }
}
