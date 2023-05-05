<?php

namespace App\Entity;

use App\Repository\ClinicalRotationCategoriesRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Form\Exception\AccessException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass = ClinicalRotationCategoriesRepository::class)
 * @ORM\Table(name = "clinical_rotation_categories")
 */
class ClinicalRotationCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(name = "id", type = "integer", options = {"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message = "Le libellé doit être renseigné")
     * @Assert\Length(
     *     min = 2,
     *     max = 100,
     *     minMessage = "Le libellé doit faire au moins {{ limit }} caractères",
     *     maxMessage = "Le libellé peut faire au maximum {{ limit }} caractères"
     *     )
     * @ORM\Column(name = "label", type = "string", length = 100, nullable = false)
     */
    private ?String $label = null;

    /**
     * @Assert\NotBlank(message = "L'heure de début doit être renseignée")
     * @ORM\Column(name = "start_time", type = "time_immutable", nullable = false)
     */
    private ?DateTimeImmutable $startTime = null;

    /**
     * @Assert\NotBlank(message = "L'heure de fin doit être renseignée")
     * @Assert\GreaterThan(propertyPath = "startTime", message = "L'heure de fin doit être supérieure à l'heure de début")
     * @ORM\Column(name = "end_time", type = "time_immutable", nullable = false)
     */
    private ?DateTimeImmutable $endTime = null;

    /**
     * @Assert\NotBlank(message = "Le nombre d'étudiant doit être renseigné")
     * @Assert\Length(
     *     min = 1,
     *     max = 65535,
     *     minMessage = "Le nombre d'étudiants doit être au minimum {{ limit }}",
     *     maxMessage = "Le nombre d'étudiants ne peut pas être plus de {{ limit }}"
     *     )
     * @ORM\Column(name = "nb_students", type = "smallint", nullable = false, options = {"unsigned": true})
     */
    private ?int $nbStudents = null;

    /**
     * @Assert\Type("bool")
     * @Assert\NotNull(message = "En semaine ou weekend doit être renseigné")
     * @ORM\Column(name = "is_on_weekend", type = "boolean", nullable = false)
     */
    private ?bool $isOnWeekend = null;

    /**
     * @ORM\Column(name = "color", type = "string", length = 255, nullable = true)
     */
    private ?String $color = null;

    /**
     * @ORM\ManyToOne(targetEntity = AcademicLevel::class, inversedBy = "clinicalRotationCategories")
     * @ORM\JoinColumn(name = "academic_level_id", nullable = false, options = {"unsigned": true})
     */
    private ?AcademicLevel $academicLevel = null;

    public function __toString()
    {
        return 'catégorie de garde ' . $this->label;
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

    public function getStartTime(): ?DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(DateTimeImmutable $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): ?DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(DateTimeImmutable $endTime): self
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
