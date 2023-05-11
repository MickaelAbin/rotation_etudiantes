<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass = AdminRepository::class)
 * @ORM\Table(name = "admins")
 */

class Admin extends User
{
    public function __toString()
    {
        return 'Administrateur ' . parent::__toString();
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every admin at least has ROLE_ADMIN
        $roles[] = 'ROLE_ADMIN';
        return array_unique($roles);
    }
}
