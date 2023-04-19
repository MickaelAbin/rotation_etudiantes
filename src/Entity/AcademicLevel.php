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

    /**
     * @ORM\OneToOne(targetEntity = UniversityCalendar::class, mappedBy = "academicLevel")
     */
    private $universityCalendar;

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

    public function getUniversityCalendar(): ?UniversityCalendar
    {
        return $this->universityCalendar;
    }

    public function setUniversityCalendar(UniversityCalendar $universityCalendar): self
    {
        // set the owning side of the relation if necessary
        if ($universityCalendar->getAcademicLevel() !== $this) {
            $universityCalendar->setAcademicLevel($this);
        }

        $this->universityCalendar = $universityCalendar;

        return $this;
    }
}
