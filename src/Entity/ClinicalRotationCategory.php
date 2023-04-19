<?php

namespace App\Entity;

use App\Repository\ClinicalRotationCategoriesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClinicalRotationCategoriesRepository::class)
 * @ORM\Table(name="clinical_rotation_categories")
 */
class ClinicalRotationCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=100, name="label")
     * @Assert\Length(min={2},max={100})
     * @Assert\NotBlank(message="Le libellé doit être renseigné")
     */
    private ?String $label;

    /**
     * @ORM\Column(type="time", name="start_time")
     * @Assert\LessThan(propertyPath="endTime",message="L'heure de début doit être plus petite que l'heure de fin")
     * @Assert\NotBlank(message="La date de début doit être renseignée")
     */
    private ?\DateTimeImmutable $startTime;

    /**
     * @ORM\Column(type="time", name="end_time")
     * @Assert\GreaterThan(propertyPath="startTime")
     * @Assert\NotBlank(message="La date de fin doit être renseignée")
     */
    private ?\DateTimeImmutable $endTime;

    /**
     * @ORM\Column(type="smallint", name="nb_students")
     * @Assert\NotBlank(message="Le nombre d'étudiant doit être renseigné")
     * @Assert\Positive()
     * @Assert\Length(min={1},max={10})
     */
    private $nbStudents;

    /**
     * @ORM\Column(type="boolean", name="is_on_weekend")
     * @Assert\NotBlank(message="La période doit être renseignée")
     */
    private ?bool $isOnWeekend;

    /**
     * @ORM\Column(type="string", length=7, nullable=true, name="color")
     */
    private ?String $color;

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

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): self
    {
        $this->start_time = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getNbStudents(): ?int
    {
        return $this->nbStudents;
    }

    public function setNbStudents(int $nbStudents): self
    {
        $this->nbStudents = $nbStudents;

        return $this;
    }

    public function isIsOnWeekend(): ?bool
    {
        return $this->isOnWeekend;
    }

    public function setIsOnWeekend(bool $isOnWeekend): self
    {
        $this->isOnWeekend = $isOnWeekend;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
