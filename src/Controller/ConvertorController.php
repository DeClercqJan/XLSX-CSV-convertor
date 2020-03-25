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
     * @Route("/", name="convertor", methods={"GET", "POST"})
     */
    public function convertor(Request $request)
    {
        // secho phpinfo();

        dump($request->getMethod());
        $requestMethod = $request->getMethod();

        // need to store this
        dump($_FILES);
        if (isset($_FILES) && !empty($_FILES)) {
            dump($_FILES["convertor_form"]["type"]);
            dump($_FILES["convertor_form"]["name"]["File"]);
            dump($_FILES["convertor_form"]["tmp_name"]["File"]);
        }

        $fileToBeConverted = new FileToBeConverted();
        $fileToBeConverted->setFileType('XLXS-test-filetype');
        // $fileToBeConverted->setFile('need to pass in object');
        $form = $this->createForm(ConvertorFormType::class, $fileToBeConverted);

        dump($request->request->get('convertor_form'));
        $form->handleRequest($request);

        // select which button has been clicked (https://symfony.com/doc/current/form/multiple_buttons.html)
        // dump($form->get("SubmitXLXS"));
        dump($form->get("SubmitCSV"));

        if ($form->isSubmitted() && $form->isValid() && $requestMethod === "POST") {
            $fileToBeConverted = $form->getData();
            dump($fileToBeConverted);

            // had trouble uploading. Used move method to set it where I wanted it
            //
            $uploads_dir = '/var/www/XLSX-CSV-convertor/public/uploaded_files';
            // $uploads_dir = '/uploads';
            foreach ($_FILES["convertor_form"]["error"] as $key => $error) {
// foreach ($_FILES["pictures"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["convertor_form"]["tmp_name"][$key];
                    // $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
                    // basename() may prevent filesystem traversal attacks;
                    // further validation/sanitation of the filename may be appropriate
                    $name = basename($_FILES["convertor_form"]["name"][$key]);
                    // $name = basename($_FILES["pictures"]["name"][$key]);
                    $test = move_uploaded_file($tmp_name, "$uploads_dir/$name");
                }
            }

            $nameOnly = pathinfo($name, PATHINFO_FILENAME);
            // even patchen met het vorige alvorens op te kuisen
            $fileToBeConverted = "/var/www/XLSX-CSV-convertor/public/uploaded_files" . "/" .
                $name;
            dump($fileToBeConverted);

            // logic csv to xml
            // naar array
            $csv = new SimpleExcel('CSV');                    // instantiate new object (will automatically construct the parser & writer type as XML)
            // $csv->parser->loadFile('SampleCSVFile_2kb.csv');            // load an XML file from server to be parsed
            $csv->parser->loadFile($fileToBeConverted);
            $foo = $csv->parser->getField();                  // get complete array of the table
            dump($foo); // echo the array

            // naar XML (Excel?)
            $excel = new SimpleExcel('xml');
            $excel->writer->setData($foo);            // add some data to the writer
            // to see that the post method works indeed, just dump it
            dump($excel);
            // $excel->writer->saveFile('example.xml');                // save the file with specified name (example.xml)
            $excel->writer->saveFile($nameOnly);
        }
        return $this->render('convertor/index.html.twig', [
            'controller_name' => 'ConvertorController',
            'form' => $form->createView(),
        ]);
    }
}