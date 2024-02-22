<?php

namespace App\Repository;

use App\Entity\CategorieRevenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieRevenu>
 *
 * @method CategorieRevenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieRevenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieRevenu[]    findAll()
 * @method CategorieRevenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRevenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieRevenu::class);
    }

    //    /**
    //     * @return CategorieRevenu[] Returns an array of CategorieRevenu objects
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

    //    public function findOneBySomeField($value): ?CategorieRevenu
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
