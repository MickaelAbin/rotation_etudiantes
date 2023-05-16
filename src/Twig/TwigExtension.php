<?php

namespace App\Twig;

use App\Entity\Admin;
use App\Entity\Student;
use App\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class TwigExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('instanceOfStudent', function(User $user) { return $user instanceof Student; }),
            new TwigTest('instanceOfAdmin', function(User $user) {return$user instanceof Admin; })
        ];
    }
}