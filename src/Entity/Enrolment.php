<?php

namespace App\Entity;

use App\Repository\EnrolmentRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass = EnrolmentRepository::class)
 * @ORM\Table(name = "enrolments")
 */
class Enrolment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(name = "id", type = "integer", options = {"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name = "date", type = "datetime_immutable", nullable = false)
     */
    private ?DateTimeImmutable $date = null;

    /**
     * @ORM\OneToOne(targetEntity = ExchangeRequest::class, mappedBy = "desiredEnrolment")
     */
    private ?ExchangeRequest $requestedExchange = null;

    /**
     * @ORM\OneToOne(targetEntity = ExchangeRequest::class, mappedBy = "proposedEnrolment")
     */
    private ?ExchangeRequest $proposedExchange = null;

    /**
     * @ORM\ManyToOne(targetEntity = ClinicalRotationCategory::class)
     * @ORM\JoinColumn(name = "clinical_rotation_category_id", nullable = false, options = {"unsigned": true})
     */
    private ?ClinicalRotationCategory $clinicalRotationCategory = null;

    /**
     * @ORM\ManyToOne(targetEntity = Student::class, inversedBy = "enrolments")
     * @ORM\JoinColumn(name = "student_id", referencedColumnName = "moodle_user_id", nullable=false, options = {"unsigned": true})
     */
    private ?Student $student = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getRequestedExchange(): ?ExchangeRequest
    {
        return $this->requestedExchange;
    }

    public function setRequestedExchange(ExchangeRequest $requestedExchange): self
    {
        // set the owning side of the relation if necessary
        if ($requestedExchange->getDesiredEnrolment() !== $this) {
            $requestedExchange->setDesiredEnrolment($this);
        }

        $this->requestedExchange = $requestedExchange;
        return $this;
    }

    public function getProposedExchange(): ?ExchangeRequest
    {
        return $this->proposedExchange;
    }

    public function setProposedExchange(ExchangeRequest $proposedExchange): self
    {
        // set the owning side of the relation if necessary
        if ($proposedExchange->getProposedEnrolment() !== $this) {
            $proposedExchange->setProposedEnrolment($this);
        }

        $this->proposedExchange = $proposedExchange;
        return $this;
    }

    public function getClinicalRotationCategory(): ?ClinicalRotationCategory
    {
        return $this->clinicalRotationCategory;
    }

    public function setClinicalRotationCategory(?ClinicalRotationCategory $clinicalRotationCategory): self
    {
        $this->clinicalRotationCategory = $clinicalRotationCategory;
        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;
        return $this;
    }
}
