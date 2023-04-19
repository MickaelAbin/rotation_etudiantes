<?php

namespace App\Repository;

use App\Entity\ClinicalRotationCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClinicalRotationCategory>
 *
 * @method ClinicalRotationCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClinicalRotationCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClinicalRotationCategory[]    findAll()
 * @method ClinicalRotationCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClinicalRotationCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClinicalRotationCategory::class);
    }

    public function add(ClinicalRotationCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClinicalRotationCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ClinicalRotationCategory[] Returns an array of ClinicalRotationCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClinicalRotationCategory
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
