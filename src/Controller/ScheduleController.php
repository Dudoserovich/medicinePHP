<?php

namespace App\Controller;

use App\Entity\DoctorSchedule;
use App\Repository\DoctorRepository;
use App\Repository\DoctorScheduleRepository;
use App\Repository\ScheduleRepository;
use App\Service\DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/schedule',
        name: 'app_schedule',
        methods: ['GET'])
    ]
    public function index(Request                  $request,
                          DoctorRepository         $doctorRepository,
                          ScheduleRepository       $scheduleRepository,
                          DoctorScheduleRepository $doctorSchedule): Response
    {
        # Вытягиваем день
        $days_get = $request->query->get('День', null);

        # Вытягиваем месяц и год
        $month_year = $request->query->get('Месяц', null);
        $month_year = lcfirst($month_year);
        $exploded_month_year = explode(" ", $month_year);
        list($month, $year) = count($exploded_month_year) > 1 ? $exploded_month_year : [null, null];
        if (!is_null($month)) {
            $day = $days_get == '' ? '%' : $days_get;
            $regexFirstDayOfMonth = "$year-" . DateTime::getMonthNumByName($month) . '-' . $day;
        } else $regexFirstDayOfMonth = '%';

        # получение массива дней месяца
        $s_dates = $scheduleRepository->findDaysByMonthRegex($regexFirstDayOfMonth);
        $daysConverted = array_map(
            fn(array $day): array => [
                "date" => DateTime::getDay($day['s_date']),
            ],
            $s_dates
        );

        # Получение первых дней каждого существующего месяца
        if ($month != '')
            $regexFirstDayOfMonth = '%-%-01';
        else
            $regexFirstDayOfMonth = substr($regexFirstDayOfMonth, 0, $days_get == '' ? -1 : -2) . '%01';

        # Получение месяца для select
        $startOfMonths = $scheduleRepository->findDaysByMonthRegex($regexFirstDayOfMonth);
        $startOfMonthsConverted = array_map(
            fn(array $day): array => [
                "date" => DateTime::getMonthYear($day['s_date']),
            ],
            $startOfMonths
        );

        # Получение дней месяца для select
        if ($days_get != '' and $month != '' or $year != '') {
            $num_month = DateTime::getMonthNumByName($month);
            $startOfMonths = $scheduleRepository->findDaysByMonthRegex("$year-$num_month-%");
            $all_days = array_map(
                fn(array $day): array => [
                    "date" => DateTime::getDay($day['s_date']),
                ],
                $startOfMonths
            );
        } else $all_days = [];

        // Если не указан месяц, то нам и нечего отображать на странице
        if ($month != '') {
            $doctors = $doctorRepository->findAllOrderBy();

            $num_month = "$year-" . DateTime::getMonthNumByName($month) . '-%';
            $sDatesAndFullNamesUnconcertedArray =
                $doctorSchedule->findSDateFullNameByRegexpMonth($num_month);
            $sDatesAndFullNamesConcertedArray = array_map(
                fn(array $dayDoc): array => [
                    "date" => DateTime::getDay($dayDoc['s_date']),
                    "full_name" => $dayDoc['full_name'],
                ],
                $sDatesAndFullNamesUnconcertedArray
            );
        } else {
            $doctors = [];
            $sDatesAndFullNamesConcertedArray = [];
        }

        return $this->render('schedule/index.html.twig',
            ['currentPath' => '/schedule',
                'selectsArray' => [
                    'Месяц' => $startOfMonthsConverted,
                    'День' => $all_days
                ],
                'currSelects' => ['Месяц' => $month_year, 'День' => $days_get],
                'doctors' => $doctors,
                'days' => $daysConverted,
                'matrix' => $sDatesAndFullNamesConcertedArray]
        );
    }


    #[Route('/schedule/new_schedule',
        name: 'app_new_schedule',
        methods: ['POST'])
    ]
    public function submitSchedule(Request                  $request,
                                   ScheduleRepository       $scheduleRepository,
                                   DoctorRepository         $doctorRepository,
                                   DoctorScheduleRepository $doctorScheduleRep): Response
    {
        $request = $request->request->all()['data'];

        foreach ($request as $select) {
            $doctor = $doctorRepository->findOneBy(['id' => $select['column_id']]);

            $monthYear = $select['month_year'];
            $exploded_month_year = explode(" ", $monthYear);
            list($month, $year) = count($exploded_month_year) > 1 ? $exploded_month_year : [null, null];
            $date = strtotime($select['day'] . '.' . DateTime::getMonthNumByName($month) . '.' . $year);
            $dateStr = date("Y-m-d", $date);
            $currDateTime = new DateTimeImmutable($dateStr);

            $schedules = $scheduleRepository->findBy(['s_date' => $currDateTime]);

            foreach ($schedules as $schedule) {
                $dsr = $doctorScheduleRep->findOneBy(['doctor' => $doctor,
                    'schedule' => $schedule]);
                if (!$dsr) {
                    $doctorSchedule = new DoctorSchedule();
                    $doctorSchedule
                        ->setSchedule($schedule)
                        ->setDoctor($doctor);

                    $this->em->persist($doctorSchedule);
                }
            }

            $this->em->flush();
        }

        return new Response(json_encode('Рабочие дни для врачей были записаны'));
    }
}
