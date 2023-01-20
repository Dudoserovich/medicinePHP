<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Repository\DoctorRepository;
use App\Repository\DoctorScheduleRepository;
use App\Repository\PatientRepository;
use App\Repository\ScheduleRepository;
use App\Repository\SpecializationRepository;
use App\Repository\VisitRepository;
use App\Service\DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/', name: 'app_appointment')]
    public function index(Request                  $request,
                          SpecializationRepository $specializationRepository,
                          DoctorScheduleRepository $doctorScheduleRepository,
                          ScheduleRepository       $scheduleRepository,
                          DoctorRepository         $doctorRepository): Response
    {
        # Получение всех специализаций
        $specs = $specializationRepository->findAll();

        # Вытягиваем специализацию
        $spec_get = $request->query->get('Специализация', null);
        # Вытягиваем Дату
        $date_with_count_get = $request->query->get('Дата', null);
        $date_get = explode(" ", $date_with_count_get)[0];

        // Получение свободных дат, больших текущей(+ времени) для каждой специализации
        $date = new DateTimeImmutable();
        $date_now = $date->format('Y-m-d');
        $dates = $doctorScheduleRepository->findCountRegAppointment($spec_get, $date_now);
        $datesConverted = array_map(
            fn(array $day): array => [
                "date" => $day['s_date']->format('d.m.Y'),
                "option" => "Свободно: " . $day['count_reg_app'],
            ],
            $dates
        );

        # Получение всех времён выбранной даты
        if ($date_get != '') {
            $times = $scheduleRepository->findTimesByStartOfMonthRegex($date_get);
            $all_times = array_map(
                fn(array $time): array => [
                    "date" => $time['starting']->format('H:i'),
                ],
                $times
            );
        } else $all_times = [];

        # Матрица Врачи/время
        if ($date_get != '' or $spec_get != '') {
            # Обработка списка врачей
            $doctors = $doctorScheduleRepository
                ->findDoctorsByDayAndSpec($date_get, $spec_get);

            $doctorsConvert = [];
            foreach ($doctors as $doctor) {
                $newDoctorEntity = new Doctor();
                $newDoctorEntity
                    ->setFullName($doctor['full_name']);
                $doctorsConvert[] = $newDoctorEntity;
            }

            # === Формируем матрицу ===
            # Формируем строковый вид текущей даты
            $selectedDate = strtotime($date_get);
            $selectedDateStr = date("Y-m-d", $selectedDate);

            # Матрица на основе выбранной специализации и времени
            $matrix = $doctorScheduleRepository->getMatrixSpecDate($spec_get, $selectedDateStr);
            $matrixConverted = array_map(
                fn(array $dayDoc): array => [
                    "date" => $dayDoc['starting']->format('H:i'),
                    "full_name" => $dayDoc['full_name'],
                    "patient" => $dayDoc['patient']
                ],
                $matrix
            );
//            print_r($matrixConverted);
            # ======
        } else {
            $doctorsConvert = [];
            $matrixConverted = [];
            $all_times = [];
        }

        return $this->render('appointment/index.html.twig',
            ['currentPath' => '/',
                'specs' => ['Специализация' => $specs, 'Дата' => $datesConverted],
                'currSelects' => ['Специализация' => $spec_get, 'Дата' => $date_get],
                'doctors' => $doctorsConvert,
                'days' => $all_times,
                'matrix' => $matrixConverted
            ]
        );
    }

}
