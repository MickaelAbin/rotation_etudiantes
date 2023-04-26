<?php

namespace App\Repository;

use App\Entity\NoRotationPeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NoRotationPeriod>
 *
 * @method NoRotationPeriod|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoRotationPeriod|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoRotationPeriod[]    findAll()
 * @method NoRotationPeriod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoRotationPeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoRotationPeriod::class);
    }

    public function add(NoRotationPeriod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NoRotationPeriod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return NoRotationPeriod[] Returns an array of NoRotationPeriod objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NoRotationPeriod
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
