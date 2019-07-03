<?php


namespace common\library\blockchain;


use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\exceptions\APICallException;

/**
 * Class EthereumCryptoManager
 * @package cryptoAPI
 */
class EthereumCryptoManager implements CryptoManagerInterface
{
    const FIELD_PAYLOAD = 'payload';
    const FIELD_ADDRESS = 'address';
    const FIELD_BALANCE = 'balance';
    const FIELD_TOTAL_RECEIVED = 'totalReceived';
    const FIELD_HEX = 'hex';

    private static $ethereumApi;

    /**
     * EthereumCryptoManager constructor.
     */
    public function __construct()
    {
        if (empty(self::$ethereumApi)) {
            self::$ethereumApi = new EthereumCryptoAPI();
        }
    }

    /**
     * @return object|string
     */
    public function generateAddress()
    {
        return self::$ethereumApi->generateAddress();
    }

    /**
     * @param object $addressInfo
     * @return mixed
     * @throws APICallException
     */
    public function getPaymentAddress($addressInfo)
    {
        if (!isset($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_ADDRESS})) {
            throw new APICallException('Can not generate new address.');
        };

        return $addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_ADDRESS};
    }

    /**
     * @param string $address
     * @return object|string
     */
    public function getBlockchainAddress(string $address)
    {
        return self::$ethereumApi->getAddress($address);
    }

    /**
     * @param string $address
     * @return int
     * @throws APICallException
     */
    public function getBalanceByAddress(string $address)
    {
        $addressInfo = self::$ethereumApi->getAddress($address);
        if (!isset($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_BALANCE})) {
            throw new APICallException('Can not get address amount.');
        };

        return (int) ($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_BALANCE} * CryptoCurrencyTypes::GWEI_PRICE);
    }

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param int $value
     * @param string $privateKey
     * @return string
     * @throws APICallException
     */
    public function sendAmount(string $addressFrom, string $addressTo, int $value, string $privateKey)
    {
        $addressInfo = self::$ethereumApi->newTransaction($addressFrom, $addressTo, $value, $privateKey);
        if (!isset($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_HEX})) {
            throw new APICallException('Can not get transaction hex.');
        };

        return ($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_HEX});
    }

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param string $privateKey
     * @return string
     * @throws APICallException
     */
    public function sendAllAmount(string $addressFrom, string $addressTo, string $privateKey)
    {
        $addressInfo = self::$ethereumApi->sendAllAmount($addressFrom, $addressTo, $privateKey);
        if (!isset($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_HEX})) {
            throw new APICallException('Can not get transaction hex.');
        };

        return ($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_HEX});
    }

}
