<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=255)
     */
    private $FileType;

    /**
     * @ORM\Column(type="object")
     */
    private $File;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileType(): ?string
    {
        return $this->FileType;
    }

    public function setFileType(string $FileType): self
    {
        $this->FileType = $FileType;

        return $this;
    }

    public function getFile()
    {
        return $this->File;
    }

    public function setFile($File): self
    {
        $this->File = $File;

        return $this;
    }
}
