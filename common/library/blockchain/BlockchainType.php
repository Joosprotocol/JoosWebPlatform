<?php


namespace common\library\blockchain;


/**
 * Class BlockchainType
 * @package cryptoAPI
 */
class BlockchainType
{
    const BLOCKCHAIN_BITCOIN = 'bitcoin';
    const BLOCKCHAIN_ETHEREUM = 'ethereum';

    /**
     * @return array
     */
    public static function blockchainList()
    {
        return [
            self::BLOCKCHAIN_BITCOIN,
            self::BLOCKCHAIN_ETHEREUM
        ];
    }
}
