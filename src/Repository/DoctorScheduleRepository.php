<?php

namespace App\Repository;

use App\Entity\DoctorSchedule;
use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DoctorSchedule>
 *
 * @method DoctorSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctorSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctorSchedule[]    findAll()
 * @method DoctorSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorSchedule::class);
    }

    public function save(DoctorSchedule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DoctorSchedule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array[] Returns an array of date objects
     */
    public function findSDateFullNameByRegexpMonth(string $date): array
    {
        return $this->createQueryBuilder('ds')
            ->select('s.s_date')
            ->addSelect('d.full_name')
            ->innerJoin('ds.doctor', 'd')
            ->innerJoin('ds.schedule', 's')
            ->where("to_char(s.s_date,'yyyy-mm-dd') like :date")
            ->setParameter('date', $date)
            ->groupBy('s.s_date, d.full_name')
            ->orderBy('s.s_date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findCountRegAppointment(string|null $specName, string $date) {
        return $this->createQueryBuilder('ds')
            ->select('sh.s_date, count(1) as count_reg_app')
            ->leftJoin(Visit::class, 'v', Join::WITH, 'v.doctorSchedule = ds.id')
            ->innerJoin('ds.schedule', 'sh')
            ->innerJoin('ds.doctor', 'd')
            ->innerJoin('d.specialization', 's')
            ->where("v.id is NULL")
            ->andWhere("sh.s_date > :date")
//            ->andWhere("sh.starting > :time")
            ->andWhere("s.name = :spec_name")
            ->setParameter('date', $date)
//            ->setParameter('time', $time)
            ->setParameter('spec_name', $specName)
            ->groupBy('sh.s_date')
            ->orderBy('sh.s_date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getMatrixSpecDate(string|null $specName, string $date) {
        return $this->createQueryBuilder('ds')
            ->select('sh.starting')
            ->addSelect('d.full_name')
            ->addSelect('p.full_name as patient')
            ->leftJoin(Visit::class, 'v', Join::WITH, 'ds.id = v.doctorSchedule')
            ->innerJoin('ds.schedule', 'sh')
            ->innerJoin('ds.doctor', 'd')
            ->innerJoin('d.specialization', 's')
            ->leftJoin('v.patient', 'p')
            ->where("not v.id is NULL")
            ->andWhere("sh.s_date = :date")
            ->andWhere("s.name = :spec_name")
            ->setParameter('date', $date)
            ->setParameter('spec_name', $specName)
            ->orderBy('sh.starting', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //select distinct s_date, full_name
//from doctor_schedule ds
//inner join doctor d on d.id = ds.doctor_id
//inner join schedule s on s.id = ds.schedule_id
//where s_date = '2023-01-24';

    public function findDoctorsByDayAndSpec(string $day, string $specName)
    {
        return $this->createQueryBuilder('ds')
            ->select('d.full_name')
            ->distinct()
            ->innerJoin('ds.doctor', 'd')
            ->innerJoin('ds.schedule', 's')
            ->innerJoin('d.specialization', 's2')
            ->where('to_char(s.s_date,\'dd.mm.yyyy\') = :day')
            ->andWhere('s2.name = :spec_name')
            ->setParameter('day', $day)
            ->setParameter('spec_name', $specName)
            ->getQuery()
            ->getResult();
    }

}
