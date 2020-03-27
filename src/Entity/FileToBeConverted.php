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
    private $file;

    private $fileNameFull;

    private $fileExtension;

    private $fileNameWithoutExtension;

    private const SAVED_FILES_DIRECTORY = "/var/www/XLSX-CSV-convertor/public/uploaded_files/";

    // shouldn't this be in a constructor?
    public function setFile($file): self
    {

        $this->file = $file;

        $this->setFileNameFull();
        $this->setFileExtension();
        $this->setFileNameWithoutExtension();
        $this->saveFileToServer();

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getFileNameWithoutExtension()
    {
        return $this->fileNameWithoutExtension;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    public function getFileNameFull()
    {
        return $this->fileNameFull;
    }

    public
    function getFullFilePathFromServer(): string
    {

        return self::SAVED_FILES_DIRECTORY . $this->fileNameFull;

    }

    public
    function checkUploadErrors()
    {
        return $this->file->getError();
    }

    private function setFileNameFull()
    {

        $this->fileNameFull = $this->file->getClientOriginalName();

    }

    private function setFileExtension()
    {

        $this->fileExtension = $this->file->getClientOriginalExtension();
    }

    private function setFileNameWithoutExtension()
    {
        $lengthOfExtension = strlen($this->fileExtension);
        // don't forget the dot!
        $lengthOfExtension = $lengthOfExtension + 1;
        $this->fileNameWithoutExtension = substr($this->fileNameFull, 0, -$lengthOfExtension);
    }

    private
    function saveFileToServer()
    {
        $this->file->move(self::SAVED_FILES_DIRECTORY, $this->fileNameFull);
    }

}
