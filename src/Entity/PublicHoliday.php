<?php

namespace App\Entity;

use App\Repository\PublicHolidayRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicHolidayRepository::class)
 * @ORM\Table(name = "public_holiday")
 */
class PublicHoliday
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
     * @ORM\ManyToOne(targetEntity = UniversityCalendar::class, inversedBy = "publicHolidays", cascade = {"persist"})
     * @ORM\JoinColumn(name = "university_calendar_id", nullable=false, options = {"unsigned": true})
     */
    private ?UniversityCalendar $universityCalendar = null;

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
