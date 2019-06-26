<?php


namespace common\library\cryptocurrency;


use Yii;

class CryptoCurrencyTypes
{
    const CURRENCY_TYPE_BTC = 1;
    const CURRENCY_TYPE_ETH = 2;
    const CURRENCY_TYPE_USD_MANUAL = 100;
    const CURRENCY_TYPE_ETH_USDT = 3;
    const CURRENCY_TYPE_JOOS = 4;

    const NETWORK_TYPE_BITCOIN = 0;
    const NETWORK_TYPE_ETHEREUM = 1;
    const NETWORK_TYPE_REAL = 100;

    const GWEI_PRICE = 1000000000;
    const SATOSHI_PRICE = 100000000;
    const CENT_PRICE = 100;
    const TETHER_MICROCENT_PRICE = 1000000;

    /**
     * @return array
     */
    public static function currencyTypeList()
    {
        return [
            self::CURRENCY_TYPE_BTC => Yii::t('app', 'BTC'),
            self::CURRENCY_TYPE_ETH => Yii::t('app', 'ETH'),
            self::CURRENCY_TYPE_USD_MANUAL => Yii::t('app', 'USD'),
            self::CURRENCY_TYPE_ETH_USDT => Yii::t('app', 'USDT'),
            self::CURRENCY_TYPE_JOOS => Yii::t('app', 'JOOS'),
        ];
    }

    /**
     * @return array
     */
    public static function precisionList()
    {
        return [
            self::CURRENCY_TYPE_BTC => self::SATOSHI_PRICE,
            self::CURRENCY_TYPE_ETH => self::GWEI_PRICE,
            self::CURRENCY_TYPE_USD_MANUAL => self::CENT_PRICE,
            self::CURRENCY_TYPE_ETH_USDT => self::TETHER_MICROCENT_PRICE,
            self::CURRENCY_TYPE_JOOS => self::GWEI_PRICE,
        ];
    }

    /**
     * @return array
     */
    public static function currencyNetworkAffiliation()
    {
        return [
            self::CURRENCY_TYPE_BTC => self::NETWORK_TYPE_BITCOIN,
            self::CURRENCY_TYPE_ETH => self::NETWORK_TYPE_ETHEREUM,
            self::CURRENCY_TYPE_USD_MANUAL => self::NETWORK_TYPE_REAL,
            self::CURRENCY_TYPE_ETH_USDT => self::NETWORK_TYPE_ETHEREUM,
            self::CURRENCY_TYPE_JOOS => self::NETWORK_TYPE_ETHEREUM,
        ];
    }

    /**
     * @return array
     */
    public static function networksNameList()
    {
        return [
            self::NETWORK_TYPE_BITCOIN => 'Bitcoin',
            self::NETWORK_TYPE_ETHEREUM => 'Ethereum',
            self::NETWORK_TYPE_REAL => 'Real',
        ];
    }
}
