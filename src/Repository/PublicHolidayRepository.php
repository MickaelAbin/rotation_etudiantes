<?php

namespace App\Repository;

use App\Entity\PublicHoliday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PublicHoliday>
 *
 * @method PublicHoliday|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicHoliday|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicHoliday[]    findAll()
 * @method PublicHoliday[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicHolidayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicHoliday::class);
    }

    public function add(PublicHoliday $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PublicHoliday $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PublicHoliday[] Returns an array of PublicHoliday objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PublicHoliday
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
