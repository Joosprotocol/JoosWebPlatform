<?php


namespace common\library\blockchain;


use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\exceptions\APICallException;

/**
 * Class BitcoinCryptoManager
 * @package cryptoAPI
 */
class BitcoinCryptoManager implements CryptoManagerInterface
{

    const FIELD_PAYLOAD = 'payload';
    const FIELD_ADDRESS = 'address';
    const FIELD_BALANCE = 'balance';
    const FIELD_TOTAL_RECEIVED = 'totalReceived';
    const FIELD_TXID = 'txid';

    private static $bitcoinApi;

    /**
     * BitcoinCryptoManager constructor.
     */
    public function __construct()
    {
        if (empty(self::$bitcoinApi)) {
            self::$bitcoinApi = new BitcoinCryptoAPI();
        }
    }

    /**
     * @return object|string
     */
    public function generateAddress()
    {
        return self::$bitcoinApi->generateAddress();
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
        return self::$bitcoinApi->getAddress($address);
    }

    /**
     * @param string $address
     * @return int
     * @throws APICallException
     */
    public function getTotalReceivedByAddress(string $address)
    {
        $addressInfo = self::$bitcoinApi->getAddress($address);
        if (!isset($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_TOTAL_RECEIVED})) {
            throw new APICallException('Can not get address total received.');
        };

        return (int) ($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_TOTAL_RECEIVED} * CryptoCurrencyTypes::SATOSHI_PRICE);
    }

    /**
     * @param string $address
     * @return int
     * @throws APICallException
     */
    public function getBalanceByAddress(string $address)
    {
        $addressInfo = self::$bitcoinApi->getAddress($address);
        if (!isset($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_BALANCE})) {
            throw new APICallException('Can not get address balance.');
        };

        return (int) ($addressInfo->{self::FIELD_PAYLOAD}->{self::FIELD_BALANCE} * CryptoCurrencyTypes::SATOSHI_PRICE);
    }

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param int $value
     * @param string $wif
     * @return string
     * @throws APICallException
     */
    public function sendAmount(string $addressFrom, string $addressTo, int $value, string $wif)
    {
        $transaction = self::$bitcoinApi->newTransaction($addressFrom, $addressTo, $value, $wif);
        if (!isset($transaction->{self::FIELD_PAYLOAD}->{self::FIELD_TXID})) {
            throw new APICallException('Can not get transaction hex.');
        };

        return ($transaction->{self::FIELD_PAYLOAD}->{self::FIELD_TXID});
    }

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param string $wif
     * @return string
     * @throws APICallException
     */
    public function sendAllAmount(string $addressFrom, string $addressTo, string $wif)
    {
        $amount = $this->getBalanceByAddress($addressFrom);
        $amountClean = $amount - self::$bitcoinApi->getAverageFee();
        $transaction = self::$bitcoinApi->newTransaction($addressFrom, $addressTo, $amountClean, $wif);
        if (!isset($transaction->{self::FIELD_PAYLOAD}->{self::FIELD_TXID})) {
            throw new APICallException('Can not get transaction hex.');
        };

        return ($transaction->{self::FIELD_PAYLOAD}->{self::FIELD_TXID});
    }

}
