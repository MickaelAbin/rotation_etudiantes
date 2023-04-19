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
     * @ORM\Column(name = "date", type = "date", nullable = false)
     */
    private ?DateTimeImmutable $date = null;

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
}
