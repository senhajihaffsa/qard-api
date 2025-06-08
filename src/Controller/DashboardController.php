<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->render('dashboard/index.html.twig', [
            'total' => count($users),
            'users' => $users,
        ]);
    }
}
