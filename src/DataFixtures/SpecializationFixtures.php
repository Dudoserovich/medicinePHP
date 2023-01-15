<?php

namespace App\DataFixtures;

use App\Entity\Specialization;
use Doctrine\Persistence\ObjectManager;

class SpecializationFixtures extends BaseFixtureAbstract
{
    public const SPECS = [
        'Терапевт',
        'Хирург',
        'Педиатр',
        'Гинеколог',
        'Психиатр-нарколог',
        'Невролог',
        'Офтальмолог',
        'Окулист',
        'Оториноларинголог'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Генерация специализаций (является справочником)
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::SPECS as &$spec_name) {
            $spec = new Specialization();

            $spec
                ->setName($spec_name);

            $manager->persist($spec);
            $this->saveReference($spec);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::DEV_GROUP];
    }
}