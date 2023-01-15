<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use Doctrine\Persistence\ObjectManager;
use Exception;

class PatientFixtures extends BaseFixtureAbstract
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
        for ($i = 0; $i < 10; ++$i) {
            $genderBool = $i % 2 == 0;
            $gender = $genderBool ? 'male' : 'female';

            $patient = new Patient();
            $patient
                ->setFullName($this->faker->name($gender))
                ->setPolicy($this->faker->unique()->regexify('[0-9]{16}'))
                ->setSex($genderBool)
                ->setBirthday(new \DateTimeImmutable($this->faker->date(max: '2000-01-01')))
                ->setAddress($this->faker->address());

            $manager->persist($patient);
            $this->saveReference($patient);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::DEV_GROUP];
    }
}