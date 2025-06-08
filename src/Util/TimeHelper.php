<?php

namespace App\Util;

use DateTime;
use DateTimeInterface;
use DateTimeZone;

class TimeHelper
{
    private const DEFAULT_TIMEZONE = 'Europe/Kyiv';

    public static function formatForCurrentUser(
        string|DateTimeInterface $utcTime,
        string $format = 'd.m.Y H:i:s'
    ): string {
        if (is_string($utcTime)) {
            $utcDateTime = new DateTime($utcTime, new DateTimeZone('UTC'));
        } else {
            $utcDateTime = DateTime::createFromInterface($utcTime);
            $utcDateTime->setTimezone(new DateTimeZone('UTC'));
        }

        $utcDateTime->setTimezone(new DateTimeZone(self::DEFAULT_TIMEZONE));

        return $utcDateTime->format($format);
    }
}
