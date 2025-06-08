<?php

namespace App\Controller;

use App\Entity\Company;
use App\Service\QardApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CompanyController extends AbstractController
{
    #[Route('/companies', name: 'company_list')]
    public function index(EntityManagerInterface $em): Response
    {
        $companies = $em->getRepository(Company::class)->findAll();
        return $this->render('companies/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/companies/create', name: 'company_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, QardApiService $qard): Response
    {
        if ($request->isMethod('POST')) {
            $siren = $request->request->get('siren');
            $name = $request->request->get('name');

            $user = $qard->createLegalUser($name, $siren);
            if (!$user || !isset($user['id'])) {
                $this->addFlash('danger', 'Erreur lors de la création du compte Qard.');
                return $this->redirectToRoute('company_create');
            }

            $qard->syncUser($user['id']);

            $company = new Company();
            $company->setName($name);
            $company->setSiren($siren);
            $company->setUserId($user['id']);
            $company->setStatus($user['group'] ?? null);
            $company->setCreatedAt(new \DateTime());

            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company_list');
        }

        return $this->render('companies/create.html.twig');
    }

    #[Route('/companies/{id}', name: 'company_show')]
    public function show(Company $company, QardApiService $qard): Response
    {
        $profile = $qard->getUserProfile($company->getUserId());
        $officers = $qard->getUserOfficers($company->getUserId());
        $financials = $qard->getUserFinancialStatements($company->getUserId());

        return $this->render('companies/show.html.twig', [
            'company' => $company,
            'profile' => $profile,
            'officers' => $officers,
            'financials' => $financials,
        ]);
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $companies = $em->getRepository(Company::class)->findAll();

        $statusStats = [];
        $turnoverData = [];

        foreach ($companies as $company) {
            $status = $company->getStatus() ?? 'Inconnu';
            $statusStats[$status] = ($statusStats[$status] ?? 0) + 1;
            // Example static CA value (normally from API)
            $turnoverData[] = rand(100000, 1000000);
        }

        return $this->render('dashboard/index.html.twig', [
            'companies' => $companies,
            'statusStats' => $statusStats,
            'turnoverData' => $turnoverData,
        ]);
    }
}
