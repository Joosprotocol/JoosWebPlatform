<?php

namespace common\library\blockchain;

use common\library\cryptocurrency\CryptoCurrencyTypes;
use Yii;

/**
 * Class EthereumCryptoAPI
 * @package cryptoAPI
 */
class EthereumCryptoAPI
{

    /** @var CryptoAPI */
    private $cryptoApi;
    private $network;

    const FIELD_FROM_ADDRESS = 'fromAddress';
    const FIELD_TO_ADDRESS = 'toAddress';
    const FIELD_GAS_PRICE = 'gasPrice';
    const FIELD_GAS_LIMIT = 'gasLimit';
    const FIELD_VALUE = 'value';
    const FIELD_PRIVATE_KEY = 'privateKey';

    const ENDPOINT_GENERATE_ADDRESS = '/v1/bc/eth/${NETWORK}/address';
    const ENDPOINT_ADDRESS = '/v1/bc/eth/${NETWORK}/address/${ADDRESS}';
    const ENDPOINT_NEW_TRANSACTION = '/v1/bc/eth/${NETWORK}/txs/new-pvtkey';
    const ENDPOINT_SEND_ALL_AMOUNT = '/v1/bc/eth/${NETWORK}/txs/new-pvtkey/all';

    const PLACEHOLDER_NETWORK = '${NETWORK}';
    const PLACEHOLDER_ADDRESS = '${ADDRESS}';

    const GAS_PRICE_DEFAULT = 1000000000;
    const GAS_LIMIT_DEFAULT = 21000;


    /**
     * EthereumCryptoAPI constructor.
     */
    public function __construct()
    {
        $this->cryptoApi = Yii::$app->cryptoApi;
        $this->network = $this->cryptoApi->getNetworkByConfig(BlockchainType::BLOCKCHAIN_ETHEREUM);
    }


    /**
     * @return object|string
     */
    public function generateAddress()
    {
        $endpointData = [
            self::PLACEHOLDER_NETWORK => $this->network,
        ];
        $url = $this->cryptoApi->apiUrl . $this->cryptoApi->buildEndpoint(self::ENDPOINT_GENERATE_ADDRESS, $endpointData);

        return $this->cryptoApi->getApiRequestHandler()->post($url);
    }

    /**
     * @param string $address
     * @return object|string
     */
    public function getAddress(string $address)
    {
        $endpointData = [
            self::PLACEHOLDER_NETWORK => $this->network,
            self::PLACEHOLDER_ADDRESS => $address,
        ];
        $url = $this->cryptoApi->apiUrl . $this->cryptoApi->buildEndpoint(self::ENDPOINT_ADDRESS, $endpointData);

        return $this->cryptoApi->getApiRequestHandler()->get($url);
    }

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param int $value
     * @param string $privateKey
     * @return object|string
     * @internal param string $wif
     */
    public function newTransaction(string $addressFrom, string $addressTo, int $value, string $privateKey)
    {
        $endpointData = [
            self::PLACEHOLDER_NETWORK => $this->network,
        ];

        $data = [
            self::FIELD_FROM_ADDRESS => $addressFrom,
            self::FIELD_TO_ADDRESS => $addressTo,
            self::FIELD_GAS_PRICE => self::GAS_PRICE_DEFAULT,
            self::FIELD_GAS_LIMIT => self::GAS_LIMIT_DEFAULT,
            self::FIELD_VALUE => (float) $value / CryptoCurrencyTypes::GWEI_PRICE,
            self::FIELD_PRIVATE_KEY => $privateKey
        ];
        $url = $this->cryptoApi->apiUrl . $this->cryptoApi->buildEndpoint(self::ENDPOINT_NEW_TRANSACTION, $endpointData);

        return $this->cryptoApi->getApiRequestHandler()->post($url, $data);
    }

    /**
     * @param string $addressFrom
     * @param string $addressTo
     * @param string $privateKey
     * @return object|string
     * @internal param string $wif
     */
    public function sendAllAmount(string $addressFrom, string $addressTo, string $privateKey)
    {
        $endpointData = [
            self::PLACEHOLDER_NETWORK => $this->network,
        ];

        $data = [
            self::FIELD_FROM_ADDRESS => $addressFrom,
            self::FIELD_TO_ADDRESS => $addressTo,
            self::FIELD_PRIVATE_KEY => $privateKey
        ];
        $url = $this->cryptoApi->apiUrl . $this->cryptoApi->buildEndpoint(self::ENDPOINT_SEND_ALL_AMOUNT, $endpointData);

        return $this->cryptoApi->getApiRequestHandler()->post($url, $data);
    }

}
