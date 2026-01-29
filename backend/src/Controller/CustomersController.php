<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/customers')]
final class CustomersController extends AbstractController
{
    #[Route('/new', name: 'app_customers_new', methods: ['GET', 'POST'])]
    public function createCustomer(Request $request, EntityManagerInterface $em): void
    {
         // Transform JSON body if needed
        $request = $this->transformJsonBody($request);
        dump($request);
    }

     protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $request;
        }

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);
        return $request;
    }
}
