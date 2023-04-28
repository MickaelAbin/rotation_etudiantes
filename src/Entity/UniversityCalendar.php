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
     * @ORM\Column(name = "start_date", type = "date_immutable", nullable = false)
     */
    private ?DateTimeImmutable $startDate = null;

    /**
     * @Assert\NotBlank(message = "La date de fin doit être renseignée")
     * @Assert\GreaterThan(propertyPath = "startDate", message = "La date de fin doit être postérieure à la date de début")
     * @ORM\Column(name = "end_date", type = "date_immutable", nullable = false)
     */
    private ?DateTimeImmutable $endDate = null;


    /**
     * @ORM\OneToMany(targetEntity = PublicHoliday::class, mappedBy = "universityCalendar", cascade = {"persist"}, orphanRemoval = true)
     */
    private Collection $publicHolidays;

    /**
     * @ORM\OneToMany(targetEntity = NoRotationPeriod::class, mappedBy = "universityCalendar", cascade = {"persist"}, orphanRemoval = true)
     */
    private Collection $noRotationPeriods;

    /**
     * @ORM\OneToOne(targetEntity = AcademicLevel::class, inversedBy = "universityCalendar")
     * @ORM\JoinColumn(name = "academic_level_id", nullable = false, options = {"unsigned": true})
     */
    private ?AcademicLevel $academicLevel = null;

    public function __construct()
    {
        $this->publicHolidays = new ArrayCollection();
        $this->noRotationPeriods = new ArrayCollection();
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

    /**
     * @return Collection<int, PublicHoliday>
     */
    public function getPublicHolidays(): Collection
    {
        return $this->publicHolidays;
    }

    public function addPublicHoliday(PublicHoliday $publicHoliday): self
    {
        if (!$this->publicHolidays->contains($publicHoliday)) {
            $this->publicHolidays->add($publicHoliday);
            $publicHoliday->setUniversityCalendar($this);
        }
        return $this;
    }

    public function removePublicHoliday(PublicHoliday $publicHoliday): self
    {
        if ($this->publicHolidays->removeElement($publicHoliday)) {
            // set the owning side to null (unless already changed)
            if ($publicHoliday->getUniversityCalendar() === $this) {
                $publicHoliday->setUniversityCalendar(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, NoRotationPeriod>
     */
    public function getNoRotationPeriods(): Collection
    {
        return $this->noRotationPeriods;
    }

    public function addNoRotationPeriod(NoRotationPeriod $noRotationPeriod): self
    {
        if (!$this->noRotationPeriods->contains($noRotationPeriod)) {
            $this->noRotationPeriods->add($noRotationPeriod);
            $noRotationPeriod->setUniversityCalendar($this);
        }
        return $this;
    }

    public function removeNoRotationPeriod(NoRotationPeriod $noRotationPeriod): self
    {
        if ($this->noRotationPeriods->removeElement($noRotationPeriod)) {
            // set the owning side to null (unless already changed)
            if ($noRotationPeriod->getUniversityCalendar() === $this) {
                $noRotationPeriod->setUniversityCalendar(null);
            }
        }
        return $this;
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
