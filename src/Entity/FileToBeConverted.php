<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileToBeConvertedRepository")
 */
// as this class basically takes another class as one of it's properties, I added it's namespace in order to to use it's methods
class FileToBeConverted
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="object")
     */
    private $File;

    private $fileNameFull;

    private $fileExtension;

    private $fileNameWithoutExtension;

    private const SAVED_FILES_DIRECTORY = "/var/www/XLSX-CSV-convertor/public/uploaded_files/";


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->File;
    }

    // shouldn't this be in a constructor?
    public function setFile($File): self
    {

        $this->File = $File;

        $this->setFileNameFull();
        $this->setFileExtension();
        $this->setFileNameWithoutExtension();
        $this->saveFileToServer();

        return $this;
    }

    private function setFileNameFull()
    {

        $this->fileNameFull = $this->File->getClientOriginalName();

    }

    public function getFileNameFull()
    {
        return $this->fileNameFull;
    }

    private function setFileExtension()
    {

        $this->fileExtension = $this->File->getClientOriginalExtension();
    }

    private function setFileNameWithoutExtension()
    {
        $lengthOfExtension = strlen($this->fileExtension);
        // don't forget the dot!
        $lengthOfExtension = $lengthOfExtension + 1;
        $this->fileNameWithoutExtension = substr($this->fileNameFull,0, -$lengthOfExtension);
    }

    public function getFileNameWithoutExtension()
    {
        return $this->fileNameWithoutExtension;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    private
    function saveFileToServer()
    {
        $this->File->move(self::SAVED_FILES_DIRECTORY, $this->fileNameFull);
    }

    public
    function getFullFilePathFromServer(): string
    {

        return self::SAVED_FILES_DIRECTORY . $this->fileNameFull;

    }

    public
    function checkUploadErrors()
    {
        return $this->File->getError();
    }

}
