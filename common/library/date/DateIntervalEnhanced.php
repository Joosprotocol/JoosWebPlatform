<?php

namespace common\library\date;

use DateInterval;
use Yii;

class DateIntervalEnhanced extends DateInterval
{
    const PERIOD_DAYS_IN_YEAR = 365;
    const PERIOD_MONTHS_IN_YEAR = 12;

    const PERIOD_YEAR = self::PERIOD_DAY * self::PERIOD_DAYS_IN_YEAR;
    const PERIOD_MONTH = self::PERIOD_DAY * self::PERIOD_DAYS_IN_YEAR / self::PERIOD_MONTHS_IN_YEAR;
    const PERIOD_DAY = 24 * 60 * 60;
    const PERIOD_HOUR = 60 * 60;
    const PERIOD_MINUTE = 60;



    /** @var  string */
    private $formatted;

    /**
     * Keep in mind that a year is seen in this class as 365 days, and a month is seen as 30 days.
     * It is not possible to calculate how many days are in a given year or month without a point of
     * reference in time.
     *
     * @return int
     */
    public function to_seconds()
    {
        return ($this->y * self::PERIOD_YEAR) +
            ($this->m * self::PERIOD_MONTH) +
            ($this->d * self::PERIOD_DAY) +
            ($this->h * self::PERIOD_HOUR) +
            ($this->i * self::PERIOD_MINUTE) +
            $this->s;
    }

    /**
     * @return void
     */
    public function recalculate()
    {
        $seconds = $this->to_seconds();
        $this->y = floor($seconds / self::PERIOD_YEAR);
        if (!empty($this->y)) {
            $this->formatted .= '%y ' . Yii::t('app', 'years') . ' ';
        }
        $seconds -= $this->y * self::PERIOD_YEAR;
        $this->m = floor($seconds / self::PERIOD_MONTH);
        if (!empty($this->m)) {
            $this->formatted .= '%m ' . Yii::t('app', 'months') . ' ';
        }
        $seconds -= $this->m * self::PERIOD_MONTH;
        $this->d = floor($seconds / self::PERIOD_DAY);
        if (!empty($this->d)) {
            $this->formatted .= '%d ' . Yii::t('app', 'days') . ' ';
        }
        $seconds -= $this->d * self::PERIOD_DAY;
        $this->h = floor($seconds / self::PERIOD_HOUR);
        if (!empty($this->h)) {
            $this->formatted .= '%h ' . Yii::t('app', 'hours') . ' ';
        }
        $seconds -= $this->h * self::PERIOD_HOUR;
        $this->i = floor($seconds / self::PERIOD_MINUTE);
        if (!empty($this->i)) {
            $this->formatted .= '%i ' . Yii::t('app', 'min') . ' ';
        }
        $seconds -= $this->i * self::PERIOD_MINUTE;
        $this->s = $seconds;
        if (!empty($this->y)) {
            $this->formatted .= '%s ' . Yii::t('app', 'sec') . ' ';
        }
    }

    /**
     * @return string
     */
    public function getFormatted(): string
    {
        return $this->format($this->formatted);
    }


}
