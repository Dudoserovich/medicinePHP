<?php

namespace App\Repository;

use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Schedule>
 *
 * @method Schedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Schedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Schedule[]    findAll()
 * @method Schedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    public function save(Schedule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Schedule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array[] Returns an array of date objects
     */
    public function findDaysByMonthRegex(string $regex): array
    {
        return $this->createQueryBuilder('s')
            ->select('DISTINCT s.s_date')
            ->where("to_char(s.s_date,'yyyy-mm-dd') like :date")
            ->setParameter('date', $regex)
            ->orderBy('s.s_date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array[] Returns an array of date objects
     */
    public function findTimesByStartOfMonthRegex(string $month): array
    {
        return $this->createQueryBuilder('s')
            ->select('DISTINCT s.starting')
            ->where("to_char(s.s_date,'dd.mm.yyyy') = :date")
            ->setParameter('date', $month)
            ->orderBy('s.starting', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
