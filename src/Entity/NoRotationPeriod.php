<?php

namespace App\Entity;

use App\Repository\NoRotationPeriodRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass = NoRotationPeriodRepository::class)
 * @ORM\Table(name = "no_rotation_period")
 */
class NoRotationPeriod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(name = "id", type = "integer", options = {"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name = "start_date", type = "datetime_immutable", nullable = false)
     */
    private ?DateTimeImmutable $startDate = null;

    /**
     * @Assert\GreaterThan(propertyPath = "startDate", message = "La date de fin doit être postérieure à la date de début")
     * @ORM\Column(name = "end_date", type = "datetime_immutable", nullable = false)
     */
    private ?DateTimeImmutable $endDate = null;

    /**
     * @ORM\ManyToOne(targetEntity = UniversityCalendar::class, inversedBy = "noRotationPeriods", cascade = {"persist"})
     * @ORM\JoinColumn(name = "university_calendar_id", nullable=false, options = {"unsigned": true})
     */
    private ?UniversityCalendar $universityCalendar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUniversityCalendar(): ?UniversityCalendar
    {
        return $this->universityCalendar;
    }

    public function setUniversityCalendar(?UniversityCalendar $universityCalendar): self
    {
        $this->universityCalendar = $universityCalendar;
        return $this;
    }
}
