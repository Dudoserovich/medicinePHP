<?php

namespace App\DataFixtures;

use App\Entity\Medicine;
use Doctrine\Persistence\ObjectManager;

class MedicineFixtures extends BaseFixtureAbstract
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $medicine = new Medicine();
            $medicine
                ->setName($this->faker->word())
                ->setUsage($this->faker->sentence(5))
                ->setActions($this->faker->sentence(5))
                ->setEffects($this->faker->sentence(5));

            $manager->persist($medicine);
            $this->saveReference($medicine);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::DEV_GROUP];
    }
}