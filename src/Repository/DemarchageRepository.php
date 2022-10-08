<?php

namespace App\Repository;

use App\Entity\Demarchage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demarchage>
 *
 * @method Demarchage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demarchage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demarchage[]    findAll()
 * @method Demarchage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemarchageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demarchage::class);
    }

    public function add(Demarchage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Demarchage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
        public function updateStatus()
        {
            return $this->createQueryBuilder('d')->update(Demarchage::class,'s')
                ->where('s.status=:actuel')
                ->set('s.status',0)
                ->setParameter('actuel',1)->getQuery()->execute();
        }
    public function show_Columns()
    {

       $con = $this->getEntityManager()->getConnection();
        return $con->executeQuery("SHOW COLUMNS from Demarchage")->fetchAllAssociative();
    }
//    /**
//     * @return Demarchage[] Returns an array of Demarchage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Demarchage
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
