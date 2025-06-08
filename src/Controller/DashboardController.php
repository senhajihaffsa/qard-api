<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\FinancialStatement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(UserRepository $repo): Response
    {
        $users = $repo->findAll();

        $revenus = [];
        $labels = [];

        foreach ($users as $user) {
            $labels[] = $user->getName();
            $financials = $user->getFinancials();

            // Prendre le plus récent ou le premier revenu
            $revenue = null;
            foreach ($financials as $f) {
                if ($f->getRevenue() !== null) {
                    $revenue = $f->getRevenue();
                    break;
                }
            }
            $revenus[] = $revenue ?? 0;
        }

        return $this->render('dashboard/index.html.twig', [
            'total' => count($users),
            'users' => $users,
            'labels' => $labels,
            'revenus' => $revenus,
        ]);
    }
}
