<?php

namespace App\Repository;

use App\Entity\AcademicLevel;
use App\Entity\ClinicalRotationCategory;
use App\Entity\Enrolment;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry, ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct($registry, Student::class);
    }

    public function save(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    public function listByAcademicLevel()
//    {
//        $qb = $this->createQueryBuilder('s');
//        $qb->where('s.academicLevel = :academic_level_id');
//        $qb->setParameter('academic_level_id', 2);
//
//        return $qb->getQuery()->getResult();
//    }
    public function listByAcademicLevel()
    {
        return $this->createQueryBuilder('student')
//            ->select('student', 'enrolment', 'category')
            ->innerJoin(Enrolment::class,'enrolment', Join::WITH, 'student.moodleUserID = enrolment.student')
            ->innerJoin(ClinicalRotationCategory::class,'category', Join::WITH, 'enrolment.clinicalRotationCategory = category.id')
            ->where('student.academicLevel = :academic_level_id')
            ->setParameter('academic_level_id', 3)
            ->orderBy('student.lastName', 'ASC')
            ->getQuery()
            ->getResult();

    }

//    **
//     * @return Student[] Returns an array of Student objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Student
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
