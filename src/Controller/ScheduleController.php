<?php

namespace App\Controller;

use App\Repository\DoctorRepository;
use App\Repository\ScheduleRepository;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends AbstractController
{
    #[Route('/schedule', name: 'app_schedule')]
    public function index(DoctorRepository   $doctorRepository,
                          ScheduleRepository $scheduleRepository,
                          VisitRepository    $visitRepository): Response
    {
        $doctors = $doctorRepository->findAllOrderBy();

        $firstDayOfMonth = strtotime("first day of this month");
        $strFirstDayOfMonth = date("Y-m-d", $firstDayOfMonth);
        $regexFirstDayOfMonth = mb_substr($strFirstDayOfMonth, start:0, length: 8) . '%';
        $s_dates = $scheduleRepository->findDaysByMonthRegex($regexFirstDayOfMonth);
        $daysConverted = array_map(
            fn(array $day): array => [
                "date" => $day['s_date']->format('d.m.Y'),
            ],
            $s_dates
        );

        # Получение первых дней каждого существующего месяца
        $yearRegexp = mb_substr($strFirstDayOfMonth, start:0, length: 2) . '%';
        $dayRegexp = mb_substr($strFirstDayOfMonth, start:8, length: strlen($strFirstDayOfMonth)-1);
        $regexStartOfMonth = $yearRegexp . '-%-' . $dayRegexp;
        $startOfMonths = $scheduleRepository->findDaysByMonthRegex($regexStartOfMonth);
        $startOfMonthsConverted = array_map(
            fn(array $day): array => [
                "date" => $day['s_date']->format('d.m.Y'),
            ],
            $startOfMonths
        );

        $sDatesAndFullNamesUnconcertedArray = $visitRepository->findScheduleData();
        $sDatesAndFullNamesConcertedArray = array_map(
            fn(array $dayDoc): array => [
                "date" => $dayDoc['s_date']->format('d.m.Y'),
                "full_name" => $dayDoc['full_name'],
            ],
            $sDatesAndFullNamesUnconcertedArray
        );

        return $this->render('schedule/index.html.twig',
            ['currentPath' => '/schedule',
                'selectsArray' => [
                    'Месяц' => $startOfMonthsConverted,
                    'Дни' => $daysConverted
                ],
                'doctors' => $doctors,
                'days' => $daysConverted,
                'matrix' => $sDatesAndFullNamesConcertedArray]
        );
    }
}
