<?php

namespace App\Service;

class DateTime
{
    private const MONTH_NAMES = [
        "01" => "Январь",
        "02" => "Февраль",
        "03" => "Март",
        "04" => "Апрель",
        "05" => "Май",
        "06" => "Июнь",
        "07" => "Июль",
        "08" => "Август",
        "09" => "Сентябрь",
        "10" => "Октябрь",
        "11" => "Ноябрь",
        "12" => "Декабрь"
    ];

    static public function getMonthYear(\DateTime $date): string
    {
        $month_num = $date->format('m');
        $year = $date->format('Y');

        return self::MONTH_NAMES[$month_num] . ' ' . $year;
    }

    static public function getMonthNumByName(string|null $name): string|null
    {
        return $name ? array_search($name, self::MONTH_NAMES) : null;
    }

    static public function getDay(\DateTime $date): string
    {
        return $date->format('d');
    }

}