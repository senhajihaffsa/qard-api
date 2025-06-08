<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\QardApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    #[Route('/companies/export/pdf', name: 'company_export_pdf')]
    public function exportPdf(): Response
    {
        $companies = $this->userRepository->findAll();

        $html = $this->renderView('companies/pdf.html.twig', [
            'users' => $companies,
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="companies.pdf"',
        ]);
    }

    #[Route('/companies/export/excel', name: 'company_export_excel')]
    public function exportExcel(): StreamedResponse
    {
        $companies = $this->userRepository->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Nom', 'SIREN', 'Date de création'], null, 'A1');

        $row = 2;
        foreach ($companies as $company) {
            $sheet->setCellValue("A$row", $company->getName());
            $sheet->setCellValue("B$row", $company->getSiren());
            $sheet->setCellValue("C$row", $company->getCreatedAt()?->format('Y-m-d H:i'));
            $row++;
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="companies.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
