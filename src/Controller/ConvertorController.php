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
        $fileToBeConverted = new FileToBeConverted("no file has been selected");

        $form = $this->createForm(ConvertorFormType::class, $fileToBeConverted);
        $form->handleRequest($request);
        $requestMethod = $request->getMethod();

        // to do: nog iets aan front-end weergeven indien succes
        $errors = [];

        if ($form->isSubmitted() && $form->isValid() && $requestMethod === "POST") {
            // als constante ergens in steken, mss best gewoon afzonderlijke klasse ofzo
            $uploadedFilesDirectory = "/var/www/XLSX-CSV-convertor/public/uploaded_files/";

            // dit kan in een functie getFileNameWithouty() ofzoiets. edit: mss best in fileToBeConvertedSteken en hier zo weinig mogelijk werk doen, maar enkel opvragen wat ik nodig heb hier
            // had trouble uploading. Used move method to set it where I wanted it
            // foreach lijkt me veiliger
            foreach ($_FILES["convertor_form"]["error"] as $key => $error) {
                // checkt of file goed werd upgeload
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["convertor_form"]["tmp_name"][$key];
                    // basename() may prevent filesystem traversal attacks;
                    // further validation/sanitation of the filename may be appropriate
                    $fileNameFull = basename($_FILES["convertor_form"]["name"][$key]);
                    move_uploaded_file($tmp_name, "$uploadedFilesDirectory/$fileNameFull");
                }
            }
            $fileNameOnly = pathinfo($fileNameFull, PATHINFO_FILENAME);
            $fileExtensionOnlyOriginal = pathinfo($fileNameFull, PATHINFO_EXTENSION);

            $acceptedFileExtensions[] = 'xml';
            $acceptedFileExtensions[] = 'csv';
            // probably need to put these checks with return statements in reusable thingie
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

            if ($fileExtensionOnlyOriginal === $fileExtensionDestination) {
                $errors[] = 'file extension of original is the same as required destination file extension';
                return $this->render('convertor/index.html.twig', [
                    'controller_name' => 'ConvertorController',
                    'form' => $form->createView(),
                    'errors' => $errors,
                ]);
            }

            // question: does it make sense to make this clas as this object basically repeats (alternative vision is 'selects') properties of existing symfony component "UploadedFile"
            // answer: becomes messy, because I need FileType in my form, which refers to FileToBeConvertedClass ...
            // to ponder on: maybe 2 classes: one that serves form and raw input and another, that only takes a few arguments?
            $fileToBeConverted = $form->getData();
            dump($fileToBeConverted);
            $fileToBeConvertedfullFilePath = $fileToBeConverted->getFullFilePathFromDataBase($uploadedFilesDirectory, $fileNameFull);

            // wanted to put this in separate object first, but now I think: what does it add but more complexity? Security maybe
            // stap 1: naar array
            $preparedObject = new SimpleExcel($fileExtensionOnlyOriginal);                    // instantiate new object (will automatically construct the parser & writer type as XML)
            $preparedObject->parser->loadFile($fileToBeConvertedfullFilePath);  // load an XML file from server to be parsed
            $preparedObjectReturnsAllTheData = $preparedObject->parser->getField();                  // get complete array of the table
            dump($preparedObjectReturnsAllTheData); // echo the array
            // stap 2: naar XML (opgelet is geen XLXS?)
            $transformedObject = new SimpleExcel($fileExtensionDestination);
            $transformedObject->writer->setData($preparedObjectReturnsAllTheData);            // add some data to the writer
            $transformedObject->writer->saveFile($fileNameOnly); // save the file with specified name (example.xml)
        }

        return $this->render('convertor/index.html.twig', [
            'controller_name' => 'ConvertorController',
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }
}