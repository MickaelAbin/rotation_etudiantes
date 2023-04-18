<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @ORM\Table(name="Students")
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\Column(name="moodle_user_id", type="bigint", options={"unsigned": true})
     */
    private ?int $moodleUserID = null;

    /**
     * @ORM\Column(name="first_name", type="string", length=50, nullable=false)
     */
    private ?string $firstName = null;

    /**
     * @ORM\Column(name="last_name", type="string", length=50, nullable=false)
     */
    private ?string $lastName = null;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(name="is_on_rotation_schedule", type="boolean", nullable=false)
     */
    private ?bool $isOnRotationSchedule = null;

    public function getMoodleUserID(): ?int
    {
        return $this->moodleUserID;
    }

    public function setMoodleUserID(int $moodleUserID): self
    {
        $this->moodleUserID = $moodleUserID;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
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
}
