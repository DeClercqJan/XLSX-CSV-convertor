<?php

namespace App\Controller;

use App\Entity\FileToBeConverted;
use App\Form\ConvertorFormType;
use App\Form\ConvertorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use SimpleExcel\SimpleExcel;

class ConvertorController extends AbstractController
{
    /**
     * @Route("/", name="convertor", methods={"GET", "POST"})
     */
    public function convertor(Request $request)
    {
        $fileToBeConverted = new FileToBeConverted();

        $form = $this->createForm(ConvertorFormType::class, $fileToBeConverted);
        $form->handleRequest($request);
        $requestMethod = $request->getMethod();

        // to do: nog iets aan front-end weergeven indien succes
        $errors = [];

        if ($form->isSubmitted() && $form->isValid() && $requestMethod === "POST") {
            // put a lot of of the work (like storing object on right place) in this object, using methods of Symfony\Component\HttpFoundation\File\UploadedFile;
            // question: does this have to do with FileType in FormType voor mijn form-field
            // https://symfony.com/doc/current/controller/upload_file.html how I've read it: FileType: to render front-end, but you can already call on File in its buildform props
            $fileToBeConverted = $form->getData();

            // check whether the file extension is accepted/supported
            $fileExtensionOnlyOriginal = $fileToBeConverted->getFileExtension();
            $acceptedFileExtensions[] = 'xml';
            $acceptedFileExtensions[] = 'csv';
            // may need to put these checks with return statements in reusable thingie
            if (!in_array($fileExtensionOnlyOriginal, $acceptedFileExtensions)) {
                $errors[] = "file extension of original $fileExtensionOnlyOriginal is not an accepted file extension";
                return $this->render('convertor/index.html.twig', [
                    'controller_name' => 'ConvertorController',
                    'form' => $form->createView(),
                    'errors' => $errors,
                ]);
            }

            // select which button has been clicked (https://symfony.com/doc/current/form/multiple_buttons.html)
            $fileExtensionDestination = $form->getClickedButton()->getName();
            // checks if conversion is unnecessary
            if ($fileExtensionOnlyOriginal === $fileExtensionDestination) {
                $errors[] = 'file extension of original is the same as required destination file extension';
                return $this->render('convertor/index.html.twig', [
                    'controller_name' => 'ConvertorController',
                    'form' => $form->createView(),
                    'errors' => $errors,
                ]);
            }

            // wanted to put this in separate object first, but now I think: what does it add but more complexity? Security maybe
            $fileToBeConvertedFullPath = $fileToBeConverted->getFullFilePathFromServer();
            $fileNameOnly = $fileToBeConverted->getFileNameWithoutExtension();

            // stap 1: prepare before transformation
            $preparedObject = new SimpleExcel($fileExtensionOnlyOriginal);                    // instantiate new object (will automatically construct the parser & writer type as XML)
            $preparedObject->parser->loadFile($fileToBeConvertedFullPath);
            $preparedObject->parser->getField();                  // get complete array of the table
            // stap 2: transform
            $preparedObject->convertTo(strtoupper($fileExtensionDestination));
            $preparedObject->writer->saveFile($fileNameOnly); // save the file with specified name (example.xml)

        }

        return $this->render('convertor/index.html.twig', [
            'controller_name' => 'ConvertorController',
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }
}