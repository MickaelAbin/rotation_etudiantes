<?php

namespace App\Entity;

use App\Repository\AcademicLevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?UniversityCalendar $universityCalendar = null;

    /**
     * @ORM\OneToMany(targetEntity = ClinicalRotationCategory::class, mappedBy = "academicLevel")
     */
    private Collection $clinicalRotationCategories;

    /**
     * @ORM\OneToMany(targetEntity = Student::class, mappedBy = "academicLevel")
     */
    private Collection $students;

    public function __construct()
    {
        $this->clinicalRotationCategories = new ArrayCollection();
        $this->students = new ArrayCollection();
    }

    public function __toString(): String
    {
        return $this->label;
    }

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

    /**
     * @return Collection<int, ClinicalRotationCategory>
     */
    public function getClinicalRotationCategories(): Collection
    {
        return $this->clinicalRotationCategories;
    }

    public function addClinicalRotationCategory(ClinicalRotationCategory $clinicalRotationCategory): self
    {
        if (!$this->clinicalRotationCategories->contains($clinicalRotationCategory)) {
            $this->clinicalRotationCategories->add($clinicalRotationCategory);
            $clinicalRotationCategory->setAcademicLevel($this);
        }
        return $this;
    }

    public function removeClinicalRotationCategory(ClinicalRotationCategory $clinicalRotationCategory): self
    {
        if ($this->clinicalRotationCategories->removeElement($clinicalRotationCategory)) {
            // set the owning side to null (unless already changed)
            if ($clinicalRotationCategory->getAcademicLevel() === $this) {
                $clinicalRotationCategory->setAcademicLevel(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setAcademicLevel($this);
        }
        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getAcademicLevel() === $this) {
                $student->setAcademicLevel(null);
            }
        }
        return $this;
    }
}
