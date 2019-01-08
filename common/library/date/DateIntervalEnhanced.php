<?php

namespace common\library\date;

use DateInterval;
use Yii;

class DateIntervalEnhanced extends DateInterval
{
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
        return ($this->y * 365 * 24 * 60 * 60) +
            ($this->m * 30 * 24 * 60 * 60) +
            ($this->d * 24 * 60 * 60) +
            ($this->h * 60 * 60) +
            ($this->i * 60) +
            $this->s;
    }

    /**
     * @return void
     */
    public function recalculate()
    {
        $seconds = $this->to_seconds();
        $this->y = floor($seconds / 60 / 60 / 24 / 365);
        if (!empty($this->y)) {
            $this->formatted .= '%y ' . Yii::t('app', 'years') . ' ';
        }
        $seconds -= $this->y * 31536000;
        $this->m = floor($seconds / 60 / 60 / 24 / 30);
        if (!empty($this->m)) {
            $this->formatted .= '%m ' . Yii::t('app', 'months') . ' ';
        }
        $seconds -= $this->m * 2592000;
        $this->d = floor($seconds / 60 / 60 / 24);
        if (!empty($this->d)) {
            $this->formatted .= '%d ' . Yii::t('app', 'days') . ' ';
        }
        $seconds -= $this->d * 86400;
        $this->h = floor($seconds / 60 / 60);
        if (!empty($this->h)) {
            $this->formatted .= '%h ' . Yii::t('app', 'hours') . ' ';
        }
        $seconds -= $this->h * 3600;
        $this->i = floor($seconds / 60);
        if (!empty($this->i)) {
            $this->formatted .= '%i ' . Yii::t('app', 'min') . ' ';
        }
        $seconds -= $this->i * 60;
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
