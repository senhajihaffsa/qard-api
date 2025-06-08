<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\QardApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private QardApiService $qardApi
    ) {}

    #[Route('/companies', name: 'company_list')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('companies/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/companies/{id}', name: 'company_show')]
    public function show(string $id): Response
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }

        $profile = $this->qardApi->getUserProfile($id);
        $officers = $this->qardApi->getCompanyOfficers($id);
        $financials = $this->qardApi->getCompanyFinancials($id);

        return $this->render('companies/show.html.twig', [
            'company' => $user,
            'profile' => $profile,
            'officers' => $officers,
            'financials' => $financials,
        ]);
    }
}
