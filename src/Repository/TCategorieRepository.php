<?php

namespace App\Repository;

use App\Entity\TCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCategorie[]    findAll()
 * @method TCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCategorie::class);
    }

    // /**
    //  * @return TCategorie[] Returns an array of TCategorie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TCategorie
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
