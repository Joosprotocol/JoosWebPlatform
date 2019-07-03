<?php

namespace common\library\blockchain;

use common\config\constant\Blockchain;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class EthereumUsdtCryptoAPI
 * @package cryptoAPI
 */
class EthereumUsdtCryptoAPI
{
    /** @var CryptoAPI */
    private $cryptoApi;
    /** @var string  */
    private $network;
    /** @var string  */
    private $getUsdtContractAddress;

    const FIELD_FROM_ADDRESS = 'fromAddress';
    const FIELD_TO_ADDRESS = 'toAddress';
    const FIELD_GAS_PRICE = 'gasPrice';
    const FIELD_GAS_LIMIT = 'gasLimit';
    const FIELD_VALUE = 'value';
    const FIELD_PRIVATE_KEY = 'privateKey';
    const FIELD_DATA = 'data';
    const FIELD_TO_CONTRACT = 'contract';
    const FIELD_TOKEN = 'token';

    const ENDPOINT_GENERATE_ADDRESS = '/v1/bc/eth/${NETWORK}/address';
    const ENDPOINT_ADDRESS = '/v1/bc/eth/${NETWORK}/address/${ADDRESS}';
    const ENDPOINT_NEW_TRANSACTION = '/v1/bc/eth/${NETWORK}/txs/new-pvtkey';
    const ENDPOINT_SEND_ALL_AMOUNT = '/v1/bc/eth/${NETWORK}/txs/new-pvtkey/all';
    const ENDPOINT_TOKEN_BALANCE = '/v1/bc/eth/${NETWORK}/tokens/${ADDRESS}/${CONTRACT}/balance';
    const ENDPOINT_TRANSFER_TOKENS = '/v1/bc/eth/${NETWORK}/tokens/transfer';

    const PLACEHOLDER_NETWORK = '${NETWORK}';
    const PLACEHOLDER_ADDRESS = '${ADDRESS}';
    const PLACEHOLDER_CONTRACT = '${CONTRACT}';

    const GAS_PRICE_DEFAULT = 1000000000;
    const GAS_LIMIT_DEFAULT = 60000;


    const PREFIX_HEX = '0x';
    const OP_CODE_TRANSACT = 'a9059cbb';

    /**
     * EthereumCryptoAPI constructor.
     */
    public function __construct()
    {
        $this->cryptoApi = Yii::$app->cryptoApi;
        $this->network = $this->cryptoApi->getNetworkByConfig(BlockchainType::BLOCKCHAIN_ETHEREUM);
        $this->getUsdtContractAddress = $this->getUsdtContractAddress();
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
     * @param string $address
     * @return object|string
     */
    public function getTokenInfo(string $address)
    {
        $endpointData = [
            self::PLACEHOLDER_NETWORK => $this->network,
            self::PLACEHOLDER_ADDRESS => $address,
            self::PLACEHOLDER_CONTRACT => $this->getUsdtContractAddress(),
        ];
        $url = $this->cryptoApi->apiUrl . $this->cryptoApi->buildEndpoint(self::ENDPOINT_TOKEN_BALANCE, $endpointData);

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
    public function transferTokens(string $addressFrom, string $addressTo, int $value, string $privateKey)
    {
        $endpointData = [
            self::PLACEHOLDER_NETWORK => $this->network,
        ];

        $data = [
            self::FIELD_FROM_ADDRESS => $addressFrom,
            self::FIELD_TO_ADDRESS => $addressTo,
            self::FIELD_TO_CONTRACT => $this->getUsdtContractAddress,
            self::FIELD_GAS_PRICE => self::GAS_PRICE_DEFAULT,
            self::FIELD_GAS_LIMIT => self::GAS_LIMIT_DEFAULT,
            self::FIELD_PRIVATE_KEY => $privateKey,
            self::FIELD_TOKEN => $value,

        ];
        $url = $this->cryptoApi->apiUrl . $this->cryptoApi->buildEndpoint(self::ENDPOINT_TRANSFER_TOKENS, $endpointData);

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

    /**
     * @param string $address
     * @param int $amount
     * @return string
     * This method is needed for generation additional data for token transfer.
     * Useless now. "token transfer" method by cryptoApi is used.
     */
    private function generateTransferData(string $address, int $amount)
    {
        $zeroBlock = $this->generateZeroBlock();
        $address = substr_replace(self::PREFIX_HEX, '', $address);
        $addressBlock = substr_replace($zeroBlock, $address, -strlen($address));

        $amountHex = dechex($amount);
        $amountBlock = substr_replace($zeroBlock, $amountHex, -strlen($amountHex));

        return self::PREFIX_HEX . self::OP_CODE_TRANSACT . $addressBlock . $amountBlock;
    }

    private function generateZeroBlock()
    {
        return str_repeat('0', 32);
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function getUsdtContractAddress()
    {
        if (empty(Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM_USDT][Blockchain::PARAM_CONTRACT_ADDRESS])) {
            throw new InvalidConfigException(Blockchain::PARAM_CONTRACT_ADDRESS . ' param is not configured.');
        }
        return Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM_USDT][Blockchain::PARAM_CONTRACT_ADDRESS];
    }

}
