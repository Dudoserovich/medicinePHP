<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Persistence\ObjectManager;

class PlaceFixtures extends BaseFixtureAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Генерация мест: 'Кабинет №$i', где $i in [1, 10]
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 11; ++$i) {
            $place = new Place();
            $place
                ->setName('Кабинет №' . $i);
            $manager->persist($place);
            $this->saveReference($place);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::DEV_GROUP];
    }
}