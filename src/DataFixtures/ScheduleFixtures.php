<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class ScheduleFixtures extends BaseFixtureAbstract
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
        $firstDayOfMonth = strtotime("first day of this month");
        $lastDayOfMonth = strtotime("last day of this month");

        $currentDayOfMonth = $firstDayOfMonth;

        $strCurrentDayOfMonth = date("Y-m-d", $currentDayOfMonth);
        $strLastDayOfMonth = date("Y-m-d", $lastDayOfMonth);

        # Пусть 13-14 у всех врачей перерыв
        $hours = [8, 9, 10, 11, 12, 14, 15, 16];
        $minutes = [0, 30];
        $times = [];
        foreach ($hours as &$hour) {
            foreach ($minutes as &$minute) {
                $times[] = ['hours' => $hour, 'minutes' => $minute];
            }
        }

        while ($strCurrentDayOfMonth != $strLastDayOfMonth) {
            $currentDateTime = new DateTimeImmutable($strCurrentDayOfMonth);

            foreach ($times as &$time) {
                $schedule = new Schedule();

                $currentDateTimeStart = $currentDateTime->setTime(hour: $time['hours'], minute: $time['minutes']);
                $currentDateTimeEnd = $currentDateTime->setTime(
                    hour: $time['minutes'] == 30 ? $time['hours'] + 1 : $time['hours'],
                    minute: $time['minutes'] == 30 ? 0 : $time['minutes']
                );
                $schedule
                    ->setSDate($currentDateTime)
                    ->setStarting($currentDateTimeStart)
                    ->setEnding($currentDateTimeEnd);

                $manager->persist($schedule);
                $this->saveReference($schedule);
            }

            $currentDayOfMonth = strtotime($strCurrentDayOfMonth . ' +1 day');
            $strCurrentDayOfMonth = date("Y-m-d", $currentDayOfMonth);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::DEV_GROUP];
    }
}