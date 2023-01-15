<?php

namespace App\DataFixtures;

use App\Entity\DoctorSchedule;
use App\Entity\Patient;
use App\Entity\Visit;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class VisitFixtures extends BaseFixtureAbstract implements DependentFixtureInterface
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
        $patients = $this->getReferencesByEntityClass(Patient::class);
        $doctorSchedules = $this->getReferencesByEntityClass(DoctorSchedule::class);

        foreach ($doctorSchedules as &$doctorSchedule) {
            $randIndex = $this->faker->randomDigit();
//            print $randIndex . PHP_EOL;

            if ($randIndex % 2 == 0) {
                $visit = new Visit();
                $visit
                    ->setPatient($this->faker->randomElement($patients))
                    ->setDoctorSchedule($doctorSchedule)
                    ->setDiagnosis($this->faker->sentence(3))
                    ->setSymptoms($this->faker->sentence());

                $manager->persist($visit);
                $this->saveReference($visit);
            }

        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::DEV_GROUP];
    }

    public function getDependencies(): array
    {
        return [
            PatientFixtures::class,
            DoctorScheduleFixtures::class
        ];
    }
}