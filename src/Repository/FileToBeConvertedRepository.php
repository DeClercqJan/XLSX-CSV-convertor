<?php

namespace App\Repository;

use App\Entity\FileToBeConverted;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FileToBeConverted|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileToBeConverted|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileToBeConverted[]    findAll()
 * @method FileToBeConverted[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileToBeConvertedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileToBeConverted::class);
    }

    // /**
    //  * @return FileToBeConverted[] Returns an array of FileToBeConverted objects
    //  */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?FileToBeConverted
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
