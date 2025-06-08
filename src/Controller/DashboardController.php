<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\QardApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(UserRepository $userRepo, QardApiService $api): Response
    {
        $users = $userRepo->findAll();
        $total = count($users);

        $statuts = [];
        $revenues = [];

        foreach ($users as $user) {
            $profile = $api->getUserProfile($user->getId());
            $financials = $api->getCompanyFinancials($user->getId());

            // Statut juridique
            $form = $profile['legal']['form'] ?? 'Inconnu';
            $statuts[$form] = ($statuts[$form] ?? 0) + 1;

            // Chiffre d'affaires
            if (!empty($financials['result'])) {
                $latest = $financials['result'][0]; // prend le 1er bilan
                $revenues[] = [
                    'name' => $user->getName(),
                    'year' => $latest['closing_year'] ?? 'N/A',
                    'revenue' => $latest['revenue']['value'] ?? 0,
                ];
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'total' => $total,
            'statuts' => $statuts,
            'revenues' => $revenues,
        ]);
    }
}
