<?php


namespace common\library\cryptocurrency;


use Yii;

class CryptoCurrencyTypes
{
    const CURRENCY_TYPE_BTC = 0;
    const CURRENCY_TYPE_ETH = 1;
    const CURRENCY_TYPE_USD = 2;
    const CURRENCY_TYPE_ETH_USDT = 3;

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
            self::CURRENCY_TYPE_USD => Yii::t('app', 'USD'),
            self::CURRENCY_TYPE_ETH_USDT => Yii::t('app', 'USDT'),
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
            self::CURRENCY_TYPE_USD => self::CENT_PRICE,
            self::CURRENCY_TYPE_ETH_USDT => self::TETHER_MICROCENT_PRICE,
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
            self::CURRENCY_TYPE_USD => self::NETWORK_TYPE_REAL,
            self::CURRENCY_TYPE_ETH_USDT => self::NETWORK_TYPE_ETHEREUM,
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
        ];
    }
}
