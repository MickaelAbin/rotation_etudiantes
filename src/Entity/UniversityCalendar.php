<?php

namespace App\Entity;

use App\Repository\UniversityCalendarRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass = UniversityCalendarRepository::class)
 * @ORM\Table(name = "university_calendars")
 */
class UniversityCalendar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(name = "id", type = "integer", options = {"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message = "La date de début doit être renseignée")
     * @ORM\Column(name = "start_date", type = "date", nullable = false)
     */
    private  ?DateTimeImmutable $startDate = null;

    /**
     * @Assert\NotBlank(message = "La date de fin doit être renseignée")
     * @Assert\GreaterThan(propertyPath = "startDate", message = "La date de fin doit être postérieure à la date de début")
     * @ORM\Column(name = "end_date", type = "date", nullable = false)
     */
    private ?DateTimeImmutable $endDate = null;

    /**
     * @ORM\Column(name = "public_holidays_with_rotation", type = "json", nullable = true)
     */
    private Collection $publicHolidaysWithRotation;

    /**
     * @ORM\Column(name = "days_without_rotation", type = "json", nullable = true)
     */
    private Collection $daysWithoutRotation;

    /**
     * @ORM\OneToOne(targetEntity = AcademicLevel::class, inversedBy = "universityCalendar", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name = "academic_level_id", nullable = false, options = {"unsigned": true})
     */
    private $academicLevel;

    public function __construct()
    {
        $this->publicHolidaysWithRotation = new ArrayCollection();
        $this->daysWithoutRotation = new ArrayCollection();
    }

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

    public function getPublicHolidaysWithRotation(): Collection
    {
        return $this->publicHolidaysWithRotation;
    }

    public function addPublicHolidayWithRotation(DateTimeImmutable $date): void
    {
        if(!$this->publicHolidaysWithRotation->contains($date)) {
            $this->publicHolidaysWithRotation->add($date);
        }
    }

    public function removePublicHolidayWithRotation(DateTimeImmutable $date): void
    {
        $this->publicHolidaysWithRotation->removeElement($date);
    }

    public function getDaysWithoutRotation(): Collection
    {
        return $this->daysWithoutRotation;
    }

    public function addDayWithoutRotation(DateTimeImmutable $date): void
    {
        if(!$this->daysWithoutRotation->contains($date)) {
            $this->daysWithoutRotation->add($date);
        }
    }

    public function removeDayWithoutRotation(DateTimeImmutable $date): void
    {
        $this->daysWithoutRotation->removeElement($date);
    }

    public function getAcademicLevel(): ?AcademicLevel
    {
        return $this->academicLevel;
    }

    public function setAcademicLevel(AcademicLevel $academicLevel): self
    {
        $this->academicLevel = $academicLevel;

        return $this;
    }

}
