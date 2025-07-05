<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/api/product/updateShopifyPrice", name="updateShopifyPrice", methods={"PUT"})
     */
    public function updateShopifyPrice(ProductRepository $productRep): Response
    {
        try {
            $productRep->callShopifyApi($this->getParameter('shopify_api_url'));

            return $this->json([
                'status' => 'success',
                'message' => 'Les prix Shopify ont été mis à jour avec succès.'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Échec lors de la mise à jour des prix Shopify : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/api/product/rapports", name="getRapportList", methods={"GET"})
     */
    public function getRapportList(StockRepository $stockRep): Response
    {
        return $this->json([
            'status' => 'success',
            'data' => $stockRep->getRapportData()
        ]);
    }
}
