<?php

namespace App\Entity;

use App\Repository\AcademicLevelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass = AcademicLevelRepository::class)
 * @ORM\Table(name = "academic_levels")
 */
class AcademicLevel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(name = "id", type = "smallint", options = {"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name = "label", type = "string", length = 50, nullable = false)
     */
    private ?string $label = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }
}
