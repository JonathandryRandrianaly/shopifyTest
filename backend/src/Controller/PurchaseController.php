<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use App\Repository\StockRepository;
use App\Repository\SupplierRepository;
use App\Service\ExcelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    private $excelService;

    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    #[Route('/purchase', name: 'app_purchase')]
    public function index(): Response
    {
        return $this->render('purchase/index.html.twig', [
            'controller_name' => 'PurchaseController',
        ]);
    }

    /**
     * @Route("/api/purchase/import", name="importPurchase", methods={"POST"})
     */
    public function importPurchase(Request $request, PurchaseRepository $purchaseRep, SupplierRepository $supplierRep, ProductRepository $productRep, StockRepository $stockRep): Response
    {
        $file = $request->files->get('file');

        if ($file && $file->isValid()) {
            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();
            $data = $this->excelService->readExcelFile($filePath);
            $result = [
                'fileName' => $fileName,
                'importResult' => $purchaseRep->importPurchase(
                    $data,
                    $supplierRep,
                    $productRep,
                    $stockRep,
                    $this->getParameter('shopify_api_url')
                )
            ];
            return $this->json(['status' => 'success', 'results' => [$result]]);
        }

        return $this->json(['status' => 'error', 'message' => 'Aucun fichier valide.']);
    }

    /**
     * @Route("/api/purchase/export", name="exportRapport")
     */
    public function exportRapport(Request $request, StockRepository $stockRep): StreamedResponse
    {
        $response = $stockRep->exportRapport();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="RapportShopify.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
}
