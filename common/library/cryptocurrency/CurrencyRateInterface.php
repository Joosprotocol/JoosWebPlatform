<?php

namespace common\library\cryptocurrency;


interface CurrencyRateInterface
{
    /**
     * @return array
     */
    public static function getRateList() : array;

    /**
     * @param string $currency
     * @return float
     */
    public static function getCurrencyRate(string $currency) : float;

}
