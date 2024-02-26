<?php

namespace App\Repository;

use App\Entity\Prevues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prevues>
 *
 * @method Prevues|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prevues|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prevues[]    findAll()
 * @method Prevues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrevuesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prevues::class);
    }

//    /**
//     * @return Prevues[] Returns an array of Prevues objects
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

//    public function findOneBySomeField($value): ?Prevues
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
