<?php

namespace common\library\blockchain;



use common\library\cryptocurrency\CryptoCurrencyTypes;
use itmaster\core\models\Setting;
use Yii;

/**
 * Class BitcoinCryptoAPI
 * @package cryptoAPI
 */
class BitcoinCryptoAPI
{
    const FIELD_CREATE_TX = 'createTx';
    const FIELD_INPUTS = 'inputs';
    const FIELD_OUTPUTS = 'outputs';
    const FIELD_WIFS = 'wifs';
    const FIELD_ADDRESS = 'address';
    const FIELD_VALUE = 'value';
    const FIELD_FEE = 'fee';

    /**
     * @var CryptoAPI
     */
    private $cryptoApi;
    private $network;

    const ENDPOINT_GENERATE_ADDRESS = '/v1/bc/btc/${NETWORK}/address';
    const ENDPOINT_ADDRESS = '/v1/bc/btc/${NETWORK}/address/${ADDRESS}';
    const ENDPOINT_NEW_TRANSACTION = '/v1/bc/btc/${NETWORK}/txs/new';

    const PLACEHOLDER_NETWORK = '${NETWORK}';
    const PLACEHOLDER_ADDRESS = '${ADDRESS}';

    const FEE_WEIGHT = 374;
    const FEE_PRICE_DEFAULT = 50;
    const FEE_PRICE_SETTING = 'bitcoin_fee_price_per_byte';


    /**
     * BitcoinCryptoAPI constructor.
     */
    public function __construct()
    {
        $this->cryptoApi = Yii::$app->cryptoApi;
        $this->network = $this->cryptoApi->getNetworkByConfig(BlockchainType::BLOCKCHAIN_BITCOIN);
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
     * @param string $wif
     * @return object|string
     */
    public function newTransaction(string $addressFrom, string $addressTo, int $value, string $wif)
    {
        $endpointData = [
            self::PLACEHOLDER_NETWORK => $this->network,
        ];

        $data = [
            self::FIELD_CREATE_TX => [
                self::FIELD_INPUTS => [
                    [
                        self::FIELD_ADDRESS => $addressFrom,
                        self::FIELD_VALUE => $value / CryptoCurrencyTypes::SATOSHI_PRICE
                    ]
                ],
                self::FIELD_OUTPUTS => [
                    [
                        self::FIELD_ADDRESS => $addressTo,
                        self::FIELD_VALUE => $value / CryptoCurrencyTypes::SATOSHI_PRICE
                    ]
                ],
                self::FIELD_FEE => [
                    self::FIELD_ADDRESS => $addressFrom,
                    self::FIELD_VALUE => (float) $this->getAverageFee() / CryptoCurrencyTypes::SATOSHI_PRICE
                ],

            ],

            self::FIELD_WIFS => [
                $wif
            ]
        ];
        $url = $this->cryptoApi->apiUrl . $this->cryptoApi->buildEndpoint(self::ENDPOINT_NEW_TRANSACTION, $endpointData);

        return $this->cryptoApi->getApiRequestHandler()->post($url, $data);
    }

    /**
     * @return int
     */
    public function getAverageFee()
    {
        $feePrice = Setting::getValue(self::FEE_PRICE_SETTING);
        if (empty($feePrice)) {
            $feePrice = self::FEE_PRICE_DEFAULT;
        }
        return self::FEE_WEIGHT * $feePrice;
    }

}
