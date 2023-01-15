<?php

namespace App\Controller;

use App\Repository\DoctorRepository;
use App\Repository\ScheduleRepository;
use App\Repository\SpecializationRepository;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/appointment', name: 'app_appointment')]
    public function index(SpecializationRepository $specializationRepository,
                          DoctorRepository         $doctorRepository,
                          ScheduleRepository       $scheduleRepository,
                          VisitRepository          $visitRepository): Response
    {
        $specs = $specializationRepository->findAll();
        $doctors = $doctorRepository->findAllOrderBy();

        # Доступные даты
        $firstDayOfMonth = strtotime("first day of this month");
        $strFirstDayOfMonth = date("Y-m-d", $firstDayOfMonth);
        $regexFirstDayOfMonth = mb_substr($strFirstDayOfMonth, start: 0, length: 8) . '%';
        $s_dates = $scheduleRepository->findDaysByMonthRegex($regexFirstDayOfMonth);
        $daysConverted = array_map(
            fn(array $day): array => [
                "date" => $day['s_date']->format('d.m.Y'),
            ],
            $s_dates
        );

        $times = $scheduleRepository->findTimesByStartOfMonthRegex($strFirstDayOfMonth);
        $timesConverted = array_map(
            fn(array $day): array => [
                "date" => $day['starting']->format('H:i:s'),
            ],
            $times
        );
//        print_r($timesConverted);

        $currDate = new \DateTime('now');

        $startingAndFullNameUnconvertedArray = $visitRepository->findScheduleStartingAndFullName($currDate);
        $startingAndFullNameConvertedArray = array_map(
            fn(array $timeDoc): array => [
                "date" => $timeDoc['starting']->format('H:i:s'),
                "full_name" => $timeDoc['full_name'],
            ],
            $startingAndFullNameUnconvertedArray
        );

        return $this->render('appointment/index.html.twig',
            ['currentPath' => '/schedule',
                'specs' => ['Специализация' => $specs, 'Даты' => $daysConverted],
//                'selectsArray' => [],
                'doctors' => $doctors,
                'days' => $timesConverted,
                'matrix' => $startingAndFullNameConvertedArray
            ]
        );
    }
}
