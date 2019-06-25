<?php

namespace common\library\cryptocurrency;


class CryptoCurrencyRateService implements CurrencyRateInterface
{
    /**
     * @return array
     */
    public static function getRateList(): array
    {
        return [
            CryptoCurrencyTypes::CURRENCY_TYPE_BTC => 5000,
            CryptoCurrencyTypes::CURRENCY_TYPE_ETH => 600,
            CryptoCurrencyTypes::CURRENCY_TYPE_USD_MANUAL => 1,
            CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT => 1,
        ];
    }

    /**
     * @param string $currency
     * @return float
     * @throws CurrencyTypeException
     */
    public static function getCurrencyRate(string $currency) : float
    {
        if (!array_key_exists($currency, self::getRateList())) {
             throw new CurrencyTypeException('Unknown currency type.');
        }
        return self::getRateList()[$currency];
    }

    /**
     * @param int $amountFrom
     * @param int $currencyTypeFrom
     * @param int $currencyTypeTo
     * @return float
     */
    public static function convertAmount(int $amountFrom, int $currencyTypeFrom, int $currencyTypeTo) : float
    {
        $precisionFrom = CryptoCurrencyTypes::precisionList()[$currencyTypeFrom];
        $precisionTo = CryptoCurrencyTypes::precisionList()[$currencyTypeTo];

        return (int) ($amountFrom * self::getCurrencyRate($currencyTypeFrom) / self::getCurrencyRate($currencyTypeTo) * $precisionTo / $precisionFrom);
    }
}
