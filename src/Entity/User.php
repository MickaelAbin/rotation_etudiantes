<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass()
 */
abstract class User implements UserInterface
{
    /**
     * @Assert\NotBlank(message = "L'ID Moodle est obligatoire")
     * @Assert\Type(
     *     type = "integer",
     *     message = "L'ID Moodle n'est pas dans un format valide"
     * )
     * @ORM\Id
     * @ORM\Column(name = "moodle_user_id", type = "bigint", options = {"unsigned": true})
     */
    protected ?int $moodleUserID = null;

    /**
     * @Assert\NotBlank(message = "Le prénom est obligatoire")
     * @Assert\Type(type = "string", message = "Le prénom n'est pas dans un format valide")
     * @ORM\Column(name = "first_name", type = "string", length = 50, nullable = false)
     */
    protected ?string $firstName = null;

    /**
     * @Assert\NotBlank(message = "Le nom de famille est obligatoire")
     * @Assert\Type(type = "string", message = "Le nom de famille n'est pas dans un format valide")
     * @ORM\Column(name = "last_name", type = "string", length = 50, nullable = false)
     */
    protected ?string $lastName = null;

    /**
     * @ORM\Column(name = "roles", type = "json", nullable = false)
     */
    protected array $roles = [];

    public function __toString()
    {
        return ucfirst($this->firstName) . ' ' . strtoupper($this->lastName);
    }

    public function isInstanceOfStudent(): bool
    {
        return $this instanceof Student;
    }

    public function isInstanceOfAdmin(): bool
    {
        return $this instanceof Admin;
    }

    public function getMoodleUserId(): ?int
    {
        return $this->moodleUserID;
    }

    public function setMoodleUserId(?int $moodleUserID): self
    {
        $this->moodleUserID = $moodleUserID;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->moodleUserID;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public abstract function getRoles(): array;

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    // Méthodes à implémenter de UserInterface mais dépréciés, et peuvent être laissées vides
    public function getUsername() {}
    public function getPassword() {}
    public function getSalt() {}
    public function eraseCredentials() {}
}

