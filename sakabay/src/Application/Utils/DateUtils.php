<?php

namespace App\Application\Utils;

class DateUtils
{
    /**
     * Give formated date.
     *
     * @return string
     */
    public static function formatedDate($date, $default = '', $format = 'Y-m-d H:i:s')
    {
        return is_null($date) ? $default : $date->format($format);
    }

    public static function frenchMonths()
    {
        return [
            '01' => 'Jan',
            '02' => 'Fév',
            '03' => 'Mar',
            '04' => 'Avr',
            '05' => 'Mai',
            '06' => 'Jui',
            '07' => 'Juil',
            '08' => 'Août',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Déc',
        ];
    }

    /**
     * Return an array of 201 elements, containing 100 years before and after the current year.
     */
    public static function getFormsDatesOptions(): array
    {
        $currentYear = date('Y');
        $yearsOptions = [$currentYear];
        for ($i = 1; $i < 101; ++$i) {
            $yearsOptions[] = $currentYear - $i;
            $yearsOptions[] = $currentYear + $i;
        }

        return $yearsOptions;
    }

    public static function diff($date1, $date2): bool
    {
        if (is_null($date1) && is_null($date2)) {
            return false;
        } elseif (is_null($date1) || is_null($date2)) {
            return true;
        } else {
            return $date2->format('Y-m-d H:i:s') !== $date1->format('Y-m-d H:i:s');
        }
    }
}
