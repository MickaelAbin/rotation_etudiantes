<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass = UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name = "discr", type = "string", length = 20)
 * @ORM\DiscriminatorMap({"user" = "User", "student" = "Student"})
 * @ORM\Table(name = "users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name = "moodle_user_id", type = "bigint", options = {"unsigned": true})
     */
    protected ?int $moodleUserID = null;

    /**
     * @ORM\Column(name = "first_name", type = "string", length = 50, nullable = false)
     */
    protected ?string $firstName = null;

    /**
     * @ORM\Column(name = "last_name", type = "string", length = 50, nullable = false)
     */
    protected ?string $lastName = null;

    /**
     * @ORM\Column(name = "email", type = "string", length = 255, nullable = false)
     */
    protected ?string $email = null;

    /**
     * @ORM\Column(name = "roles", type = "json", nullable = false)
     */
    protected array $roles = [];

    public function __toString()
    {
        return $this->firstName . strtoupper($this->lastName);
    }

    public function getMoodleUserId(): int
    {
        return $this->moodleUserID;
    }

    /**
     * A visual identifier that represents this user.
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }


    // Méthodes à implémenter de UserInterface mais dépréciés, et peuvent être laissées vides
    public function getUsername()
    {
    }
    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
