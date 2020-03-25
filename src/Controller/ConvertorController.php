<?php

namespace App\Controller;

use App\Entity\FileToBeConverted;
use App\Form\ConvertorFormType;
use App\Form\ConvertorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConvertorController extends AbstractController
{
//    /**
//     * @Route("/", name="convertor")
//     */
//    public function index()
//    {
//        return $this->render('convertor/index.html.twig', [
//            'controller_name' => 'ConvertorController',
//        ]);
//    }

    /**
     * @Route("/", name="convertor")
     */
    public function convertor(Request $request)
    {

        $fileToBeConverted = new FileToBeConverted();
        $fileToBeConverted->setFileType('XLXS-test-filetype');
        // $fileToBeConverted->setFile('need to pass in object');
        $form = $this->createForm(ConvertorFormType::class, $fileToBeConverted);

        dump($request->request->get('convertor_form'));
        $form->handleRequest($request);
        // select which button has been clicked (https://symfony.com/doc/current/form/multiple_buttons.html)
        dump($form->get("SubmitXLXS"));
        if ($form->isSubmitted() && $form->isValid()) {
            $fileToBeConverted = $form->getData();
            dump($fileToBeConverted);
        }
        return $this->render('convertor/index.html.twig', [
            'controller_name' => 'ConvertorController',
            'form' => $form->createView(),
        ]);
    }
}