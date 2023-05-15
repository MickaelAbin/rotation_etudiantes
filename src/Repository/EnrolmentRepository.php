<?php

namespace App\Repository;

use App\Entity\AcademicLevel;
use App\Entity\ClinicalRotationCategory;
use App\Entity\Enrolment;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @extends ServiceEntityRepository<Enrolment>
 *
 * @method Enrolment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enrolment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enrolment[]    findAll()
 * @method Enrolment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnrolmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enrolment::class);
    }

    public function add(Enrolment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Enrolment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function listByAcademicLevelCalendrier(int $academicLevelID)
    {
        return $this->createQueryBuilder('enrolment')
//            ->select('student', 'enrolment', 'category')
            ->innerJoin(Student::class,'student', Join::WITH, 'student.moodleUserID = enrolment.student')
            ->innerJoin(ClinicalRotationCategory::class,'category', Join::WITH, 'enrolment.clinicalRotationCategory = category.id')
            ->where('student.academicLevel = :academic_level_id')
            ->setParameter('academic_level_id', $academicLevelID)
            ->getQuery()
            ->getResult();

    }

    public function findFirstEnrolmentForAcademicLevel(int $academicLevel): ?Enrolment
    {
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.id = :academicLevel')
            ->orderBy('e.date', 'ASC')
            ->setParameter('academicLevel', $academicLevel)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
