<?php

namespace App\Repository;

use App\Entity\AcademicLevel;
use App\Entity\ClinicalRotationCategory;
use App\Entity\Enrolment;
use App\Entity\ExchangeRequest;
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

    public function findEnrolmentsByAcademicLevel(int $academicLevelID)
    {
        return $this->createQueryBuilder('enrolment')
//            ->select('student', 'enrolment', 'category', 'requestedExchange', 'proposedExchange')
            ->innerJoin(Student::class,'student', Join::WITH, 'student.moodleUserID = enrolment.student')
            ->innerJoin(ClinicalRotationCategory::class,'category', Join::WITH, 'enrolment.clinicalRotationCategory = category.id')
            ->leftJoin(ExchangeRequest::class, 'requestedExchange', Join::WITH,
                'enrolment.id = requestedExchange.requestedEnrolment AND requestedExchange.isAccepted != 1')
            ->leftJoin(ExchangeRequest::class, 'proposedExchange', Join::WITH,
                'enrolment.id = proposedExchange.proposedEnrolment AND proposedExchange.isAccepted != 1')
            ->where('student.academicLevel = :academic_level_id')
            ->setParameter('academic_level_id', $academicLevelID)
            ->getQuery()
            ->getResult();

    }
    public function findLastEnrolmentForAcademicLevel(int $academicLevelID): ?Enrolment
    {
        return $this->createQueryBuilder('e')
            ->innerJoin(Student::class,'student', Join::WITH, 'student.moodleUserID = e.student')
            ->where('student.academicLevel = :academic_level_id')
            ->setParameter('academic_level_id', $academicLevelID)
            ->orderBy('e.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
