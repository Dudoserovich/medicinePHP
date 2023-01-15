<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\DoctorSchedule;
use App\Entity\Medicine;
use App\Entity\Patient;
use App\Entity\Visit;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class AppointmentFixtures extends BaseFixtureAbstract implements DependentFixtureInterface
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
        $visits = $this->getReferencesByEntityClass(Visit::class);
        $medicines = $this->getReferencesByEntityClass(Medicine::class);

        foreach ($visits as &$visit) {
            $appointment = new Appointment();
            $appointment
                ->setMedicine($this->faker->randomElement($medicines))
                ->setVisit($visit)
                ->setAmount($this->faker->randomDigit());

            $manager->persist($appointment);
            $this->saveReference($appointment);
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
            VisitFixtures::class,
            MedicineFixtures::class
        ];
    }
}