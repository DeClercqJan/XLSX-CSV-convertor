<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConvertorController extends AbstractController
{
    /**
     * @Route("/", name="convertor")
     */
    public function index()
    {
        return $this->render('convertor/index.html.twig', [
            'controller_name' => 'ConvertorController',
        ]);
    }
}
