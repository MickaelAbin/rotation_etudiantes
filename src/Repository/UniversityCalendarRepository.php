<?php

namespace App\Repository;

use App\Entity\UniversityCalendar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UniversityCalendar>
 *
 * @method UniversityCalendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method UniversityCalendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method UniversityCalendar[]    findAll()
 * @method UniversityCalendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UniversityCalendarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UniversityCalendar::class);
    }

    public function add(UniversityCalendar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UniversityCalendar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UniversityCalendar[] Returns an array of UniversityCalendar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UniversityCalendar
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
