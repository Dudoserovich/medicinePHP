<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visit>
 *
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    public function save(Visit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Visit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array[] Returns an array of s_date and full_name doctor objects
     */
    public function findScheduleData(): array
    {
        return $this->createQueryBuilder('v')
            ->select('s.s_date, d.full_name')
            ->innerJoin('v.patient', 'p')
            ->leftJoin('v.doctorSchedule', 'ds')
            ->innerJoin('ds.schedule', 's')
            ->leftJoin('ds.doctor', 'd')
            ->groupBy('s.s_date, d.full_name')
            ->orderBy('s.s_date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array[] Returns an array of s_date and full_name doctor objects
     */
    public function findScheduleStartingAndFullName(\DateTime $date): array
    {
        return $this->createQueryBuilder('v')
            ->select('s.starting, d.full_name')
            ->innerJoin('v.patient', 'p')
            ->leftJoin('v.doctorSchedule', 'ds')
            ->innerJoin('ds.schedule', 's')
            ->leftJoin('ds.doctor', 'd')
            ->where('s.s_date = :date')
            ->setParameter('date', $date)
            ->groupBy('s.starting, d.full_name')
            ->orderBy('s.starting', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?Visit
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
