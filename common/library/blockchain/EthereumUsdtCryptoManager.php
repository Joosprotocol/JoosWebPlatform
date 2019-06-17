<?php


namespace common\library\blockchain;


use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\exceptions\APICallException;

/**
 * Class EthereumUsdtCryptoManager
 * @package cryptoAPI
 */
class EthereumUsdtCryptoManager implements CryptoManagerInterface
{
    const FIELD_PAYLOAD = 'payload';
    const FIELD_ADDRESS = 'address';
    const FIELD_BALANCE = 'balance';
    const FIELD_TOTAL_RECEIVED = 'totalReceived';
    const FIELD_HEX = 'hex';
    const FIELD_TOKEN = 'token';

    /** @var EthereumUsdtCryptoAPI  */
    private static $ethereumApi;

    /**
     * EthereumCryptoManager constructor.
     */
    public function __construct()
    {
        if (empty(self::$ethereumApi)) {
            self::$ethereumApi = new EthereumUsdtCryptoAPI();
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
     * @return object|string
     */
    public function getTokenInfo(string $address)
    {
        return self::$ethereumApi->getTokenInfo($address);
    }

    /**
     * @param string $address
     * @return int
     * @throws APICallException
     */
    public function getBalanceByAddress(string $address)
    {
        $tokenInfo = self::$ethereumApi->getTokenInfo($address);

        if (!isset($tokenInfo->{self::FIELD_PAYLOAD}->{self::FIELD_TOKEN})) {
            throw new APICallException('Can not get token amount.');
        };

        return (int) ($tokenInfo->{self::FIELD_PAYLOAD}->{self::FIELD_TOKEN} * CryptoCurrencyTypes::TETHER_MICROCENT_PRICE);
    }

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param int $value
     * @param string $privateKey
     * @return int
     * @throws APICallException
     */
    public function sendAmount(string $addressFrom, string $addressTo, int $value, string $privateKey)
    {
        $preparedValue = $value / CryptoCurrencyTypes::TETHER_MICROCENT_PRICE;
        $result = self::$ethereumApi->transferTokens($addressFrom, $addressTo, $preparedValue, $privateKey);
        if (!isset($result->{self::FIELD_PAYLOAD}->{self::FIELD_HEX})) {
            throw new APICallException('Can not get transaction hex.');
        };

        return ($result->{self::FIELD_PAYLOAD}->{self::FIELD_HEX});
    }

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param string $privateKey
     * @return int
     * @throws APICallException
     */
    public function sendAllAmount(string $addressFrom, string $addressTo, string $privateKey)
    {
        $amount = $this->getBalanceByAddress($addressFrom);
        return $this->sendAmount($addressFrom, $addressTo, $amount, $privateKey);
    }

    /**
     * @return EthereumUsdtCryptoAPI
     */
    public static function getTokenTransferFee(): EthereumUsdtCryptoAPI
    {
        return self::$ethereumApi::GAS_LIMIT_DEFAULT;
    }
}
