<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Entity\Specialization;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class DoctorFixtures extends BaseFixtureAbstract implements DependentFixtureInterface
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
        $specs = $this->getReferencesByEntityClass(Specialization::class);

        for ($i = 0; $i < 10; ++$i) {
            $genderBool = $i % 2 == 0;
            $gender = $genderBool ? 'male' : 'female';

            $doctor = new Doctor();
            $doctor
                ->setFullName($this->faker->name($gender))
                ->setSpecialization($this->faker->randomElement($specs))
                ->setSex($genderBool)
                ->setAddress($this->faker->address())
                ->setPhone($this->faker->phoneNumber())
                ->setWorkExperience($this->faker->randomDigit());

            $manager->persist($doctor);
            $this->saveReference($doctor);
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
          SpecializationFixtures::class
        ];
    }
}