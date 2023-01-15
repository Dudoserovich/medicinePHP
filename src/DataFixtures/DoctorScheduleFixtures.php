<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Entity\DoctorSchedule;
use App\Entity\Place;
use App\Entity\Schedule;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class DoctorScheduleFixtures extends BaseFixtureAbstract implements DependentFixtureInterface
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $doctors = $this->getReferencesByEntityClass(Doctor::class);
        $schedules = $this->getReferencesByEntityClass(Schedule::class);
        $places = $this->getReferencesByEntityClass(Place::class);

        # Образуем пару доктор+место, чтобы были более реалистичные данные
        $doctorPlaces = [];
        $index = 0;
        foreach ($doctors as $doctor) {
            $doctorPlaces[] =
                [
                    'doctor' => $doctor,
                    'place' => $places[$index]
                ];
            $index++;
        }

        # 1 день = 1 набор врачей
        # В разные дни может работать один врач, а другой нет
        $index = 0;
        $currDate = $schedules[$index];
        # Проходимя по датам
        while ($currDate->getSDate() != end($schedules)->getSDate()) {
            # Проходимя по времени
            $randIndex = $this->faker->randomDigit();
            while ($schedules[$index]->getSDate() == $currDate->getSDate()) {
                # Проходимся по всем рандомно выбранным врачам
                for ($i = 0; $i < $randIndex; ++$i) {
                    $doctorSchedule = new DoctorSchedule();
                    $randDoctorPlace = $doctorPlaces[$i];

                    $doctorSchedule
                        ->setDoctor($randDoctorPlace['doctor'])
                        ->setSchedule($schedules[$index])
                        ->setPlace($randDoctorPlace['place']);

                    $manager->persist($doctorSchedule);
                    $this->saveReference($doctorSchedule);
                }

                $index++;
            }
            # меняем текущую дату
            $currDate = $schedules[$index];
        }

//        foreach ($schedules as &$schedule) {
//            $randIndex = $this->faker->randomDigit();
////            print $randIndex . PHP_EOL;
//
//            for ($i = 0; $i < $randIndex; ++$i) {
//                $doctorSchedule = new DoctorSchedule();
//                $randDoctorPlace = $doctorPlaces[$i];
//
//                $doctorSchedule
//                    ->setDoctor($randDoctorPlace['doctor'])
//                    ->setSchedule($schedule)
//                    ->setPlace($randDoctorPlace['place']);
//
//                $manager->persist($doctorSchedule);
//                $this->saveReference($doctorSchedule);
//            }
//        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::DEV_GROUP];
    }

    public function getDependencies(): array
    {
        return [
            PlaceFixtures::class,
            ScheduleFixtures::class,
            DoctorFixtures::class
        ];
    }
}