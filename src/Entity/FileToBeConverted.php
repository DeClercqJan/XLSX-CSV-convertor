<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileToBeConvertedRepository")
 */
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

        $this->setOriginalName();

        return $this;
    }

    private function setOriginalName()
    {

        // as this class basically takes another class as one of it's properties, I added it's namespace in order to to use it's methods
        $this->fileNameFull = $this->File->getClientOriginalName();;

    }

    public function getFullFilePathFromDataBase($uploadedFilesDirectory, $fileNameFull)
    {

        return $uploadedFilesDirectory . $this->fileNameFull;

    }

}
