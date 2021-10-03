<?php

namespace App\Repository;

use App\Entity\TComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method TComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method TComment[]    findAll()
 * @method TComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TComment::class);
    }

    // /**
    //  * @return TComment[] Returns an array of TComment objects
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
    public function findOneBySomeField($value): ?TComment
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
