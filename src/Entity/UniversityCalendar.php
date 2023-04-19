<?php

namespace App\Entity;

use App\Repository\UniversityCalendarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UniversityCalendarRepository::class)
 * @ORM\Table(name="university_calendars")
 */
class UniversityCalendar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="date_immutable", name="start_date")
     * @Assert\LessThan(propertyPath="endDate",message="La date de début doit être plus petite que la date de fin")
     * @Assert\NotBlank(message="La date de début doit être renseignée")
     */
    private  ?\DateTimeImmutable $startDate;

    /**
     * @ORM\Column(type="date_immutable", name="end_date")
     * @Assert\GreaterThan(propertyPath="startDate",message="La date de fin doit être plus grande que la date de début")
     * @Assert\NotBlank(message="La date de fin doit être renseignée")
     */
    private ?\DateTimeImmutable $endDate;

    /**
     * @ORM\Column(type="json", nullable=false, name="public_holiddays_with_rotations")
     */
    private $publicHoliddaysWithRotations = [];

    /**
     * @ORM\Column(type="json", nullable=false, name="days_without_rotation")
     */
    private $daysWithoutRotation = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPublicHoliddaysWithRotations(): ?array
    {
        return $this->publicHoliddaysWithRotations;
    }

    public function setPublicHoliddaysWithRotations(?array $publicHoliddaysWithRotations): self
    {
        $this->publicHoliddaysWithRotations = $publicHoliddaysWithRotations;

        return $this;
    }

    public function getDaysWithoutRotation(): ?array
    {
        return $this->daysWithoutRotation;
    }

    public function setDaysWithoutRotation(?array $daysWithoutRotation): self
    {
        $this->daysWithoutRotation = $daysWithoutRotation;

        return $this;
    }
}
