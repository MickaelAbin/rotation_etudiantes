<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 */

class UserRepository extends ServiceEntityRepository
{
    private AdminRepository $adminRepository;
    private StudentRepository $studentRepository;

    public function __construct(ManagerRegistry $registry, AdminRepository $adminRepository, StudentRepository $studentRepository)
    {
        parent::__construct($registry, User::class);
        $this->adminRepository = $adminRepository;
        $this->studentRepository = $studentRepository;
    }

    public function findByID(int $id): ?User
    {
        $admin = $this->adminRepository->find($id);
        if(isset($admin)) {
            return $admin;
        }

        $student = $this->studentRepository->find($id);
        if (isset($student)) {
            return $student;
        }

        return null;
    }

}