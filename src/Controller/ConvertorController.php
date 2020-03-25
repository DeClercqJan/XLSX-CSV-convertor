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
        if ($form->isSubmitted() && $form->isValid() && $requestMethod === "POST") {
            // question: does it make sense to make this clas as this object basically repeats (alternative vision is 'selects') properties of existing symfony component "UploadedFile"
            // answer: becomes messy, because I need FileType in my form, which refers to FileToBeConvertedClass ...
            // to ponder on: maybe 2 classes: one that serves form and raw input and another, that only takes a few arguments?
            $fileToBeConverted = $form->getData();
            dump($fileToBeConverted);

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
            $fileExtensionOnly = pathinfo($fileNameFull, PATHINFO_EXTENSION);

            $fileToBeConvertedfullFilePath = $fileToBeConverted->getFullFilePathFromDataBase($uploadedFilesDirectory, $fileNameFull);

            // select which button has been clicked (https://symfony.com/doc/current/form/multiple_buttons.html)
            $XMLButtonClickedOrNot = $form->get("SubmitXML")->isClicked();
            $CSVButtonClickedOrNot = $form->get("SubmitCSV")->isClicked();
            if (true === $XMLButtonClickedOrNot && 'csv' === $fileExtensionOnly) {
                // is dit niet state-machine patroon?
                // stap 1: naar array
                $csv = new SimpleExcel('CSV');                    // instantiate new object (will automatically construct the parser & writer type as XML)
                $csv->parser->loadFile($fileToBeConvertedfullFilePath);  // load an XML file from server to be parsed
                $arrayWithAllTheData = $csv->parser->getField();                  // get complete array of the table
                dump($arrayWithAllTheData); // echo the array
                // stap 2: naar XML (opgelet is geen XLXS?)
                $excel = new SimpleExcel('xml');
                $excel->writer->setData($arrayWithAllTheData);            // add some data to the writer
                $excel->writer->saveFile($fileNameOnly); // save the file with specified name (example.xml)
            }
            if (true === $CSVButtonClickedOrNot && 'xml' === $fileExtensionOnly) {
                dump("to do");
            }

            // nog opvangen fouten: user laten weten, logger ...?
            // 1) mogelijk verkeerde knop voor verkeerde extensie
            // 2) mogelijk impact van grote of kleine letters in extensie (18 mogelijkheden voor 2 types van lengte van 3. Maar is niet echt extendable als ik ook in toekomst bv. XLXS wil doen

            // to do: nog iets aan front-end weergeven indien succes + fouten(array?);
        }
        return $this->render('convertor/index.html.twig', [
            'controller_name' => 'ConvertorController',
            'form' => $form->createView(),
        ]);
    }
}