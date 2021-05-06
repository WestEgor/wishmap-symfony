<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SomeController extends AbstractController
{
    #[Route('/some', name: 'some')]
    public function index(): Response
    {
        return $this->render('some/index.html.twig', [
            'controller_name' => 'SomeController',
        ]);
    }
}
