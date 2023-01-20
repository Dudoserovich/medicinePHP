<?php

namespace App\Controller;

use App\Entity\DoctorSchedule;
use App\Entity\Visit;
use App\Repository\DoctorRepository;
use App\Repository\DoctorScheduleRepository;
use App\Repository\PatientRepository;
use App\Repository\ScheduleRepository;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/patient', name: 'app_patient')]
    public function index(): Response
    {
        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }

    #[Route('/policy', name: 'app_patient_policy', methods: ['POST'])]
    public function findByPolicy(Request           $request,
                                 PatientRepository $patientRepository): Response
    {
        $request = $request->request->all();
        $patient = $patientRepository->findOneBy(['policy' => $request['policy']]);

        if ($patient) {
            return new Response(
                json_encode([
                    'full_name' => $patient->getFullName(),
                    'sex' => $patient->getSex(),
                    'address' => $patient->getAddress(),
                    'birthday' => $patient->getBirthday()
                ])
            );
        } else return new Response(json_encode('Полиса нет'));
    }

    #[Route('/new_visit', name: 'app_patient_new_visit', methods: ['POST'])]
    public function addVisit(Request                  $request,
                             PatientRepository        $patientRepository,
                             ScheduleRepository       $scheduleRepository,
                             DoctorRepository         $doctorRepository,
                             DoctorScheduleRepository $doctorScheduleRepository): Response
    {
        $request = $request->request->all();
        $patient = $patientRepository->findOneBy(['policy' => $request['policy']]);

        if ($patient) {
            $time = strtotime($request['time']);
            $time = new \DateTimeImmutable(date("H:i:s", $time));
            $s_date = strtotime($request['date']);
            $s_date = new \DateTimeImmutable(date("Y-m-d", $s_date));
            $schedule = $scheduleRepository->findOneBy([
                'starting' => $time,
                's_date' => $s_date
            ]);

            $doctor = $doctorRepository->findOneBy(['full_name' => $request['doctor_name']]);

            if (!$schedule or !$doctor)
                return new Response(json_encode('Что-то пошло не так'));

            $doctorSchedule = $doctorScheduleRepository
                ->findOneBy(['schedule' => $schedule, 'doctor' => $doctor]);

            if (!$doctorSchedule)
                return new Response(json_encode('Что-то пошло не так'));

            $visit = new Visit();
            $visit
                ->setPatient($patient)
                ->setDoctorSchedule($doctorSchedule)
                ->setSymptoms('')
                ->setDiagnosis('');

            $this->em->persist($visit);
        }

        $this->em->flush();
        return new Response(json_encode('OK'));
//        $patient = $patientRepository->findOneBy(['policy' => $request['policy']]);
//
//        if ($patient) {
//            return new Response(
//                json_encode([
//                    'full_name' => $patient->getFullName(),
//                    'sex' => $patient->getSex(),
//                    'address' => $patient->getAddress(),
//                    'birthday' => $patient->getBirthday()
//                ])
//            );
//        } else return new Response(json_encode('Полиса нет'));
    }
}
