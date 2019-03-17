<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CatalogRepository")
 * @ORM\Table(name="catalog")
 */
class Catalog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Mark")
     * @JoinTable(name="catalogs_marks")
     */
    private $marks;

    public function __construct()
    {
        $this->marks = new ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getMarks()
    {
        return $this->marks;
    }

    public function setMarks($mark): void
    {
        $this->marks = $mark;
    }

    public function addMark(Mark $mark)
    {
        if ($this->marks->contains($mark)) {
            return;
        }

        $this->marks[] = $mark;
    }

    public function removeMark(Mark $mark)
    {
        if (!$this->marks->contains($mark)) {
            return;
        }

        $this->marks->removeElement($mark);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNameSuggest()
    {
        $name = preg_replace('/^ +| +$|( ) +/m', '$1', $this->getName());

        return [
            'input' => explode(' ', $name)
        ];
    }

    public function getOutput()
    {
        return trim($this->getName());
    }
}