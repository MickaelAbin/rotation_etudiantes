<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass = StudentRepository::class)
 */
class Student extends User
{
    /**
     * @ORM\Column(name = "is_on_rotation_schedule", type = "boolean", nullable = true)
     */
    private ?bool $isOnRotationSchedule = null;

    /**
     * @ORM\OneToMany(targetEntity = Enrolment::class, mappedBy = "student")
     */
    private Collection $enrolments;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity = AcademicLevel::class, inversedBy = "students")
     * @ORM\JoinColumn(name = "academic_level_id", nullable = true, options = {"unsigned": true})
     */
    private ?AcademicLevel $academicLevel = null;

    public function __construct()
    {
        $this->enrolments = new ArrayCollection();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_STUDENT';
        return array_unique($roles);
    }

    public function isIsOnRotationSchedule(): ?bool
    {
        return $this->isOnRotationSchedule;
    }

    public function setIsOnRotationSchedule(bool $isOnRotationSchedule): self
    {
        $this->isOnRotationSchedule = $isOnRotationSchedule;
        return $this;
    }

    /**
     * @return Collection<int, Enrolment>
     */
    public function getEnrolments(): Collection
    {
        return $this->enrolments;
    }

    public function addEnrolment(Enrolment $enrolment): self
    {
        if (!$this->enrolments->contains($enrolment)) {
            $this->enrolments->add($enrolment);
            $enrolment->setStudent($this);
        }
        return $this;
    }

    public function removeEnrolment(Enrolment $enrolment): self
    {
        if ($this->enrolments->removeElement($enrolment)) {
            // set the owning side to null (unless already changed)
            if ($enrolment->getStudent() === $this) {
                $enrolment->setStudent(null);
            }
        }
        return $this;
    }

    public function getAcademicLevel(): ?AcademicLevel
    {
        return $this->academicLevel;
    }

    public function setAcademicLevel(?AcademicLevel $academicLevel): self
    {
        $this->academicLevel = $academicLevel;
        return $this;
    }
}
