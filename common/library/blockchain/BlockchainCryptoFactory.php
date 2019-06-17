<?php

namespace common\library\blockchain;

use common\library\cryptocurrency\CryptoCurrencyTypes;

/**
 * Class BlockchainCryptoFactory
 * @package cryptoAPI
 */
class BlockchainCryptoFactory
{
    const MANAGER_TYPE_BITCOIN = 'btc';
    const MANAGER_TYPE_ETHEREUM = 'eth';

    /**
     * @param int $currencyType
     * @return BitcoinCryptoManager|EthereumCryptoManager|EthereumUsdtCryptoManager
     */
    public static function getManagerByCurrency(int $currencyType)
    {
        if ($currencyType === CryptoCurrencyTypes::CURRENCY_TYPE_BTC) {
            return new BitcoinCryptoManager();
        }
        if ($currencyType === CryptoCurrencyTypes::CURRENCY_TYPE_ETH) {
            return new EthereumCryptoManager();
        }
        if ($currencyType === CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT) {
            return new EthereumUsdtCryptoManager();
        }
        throw new \InvalidArgumentException('Unexpected currency type.');
    }
}
