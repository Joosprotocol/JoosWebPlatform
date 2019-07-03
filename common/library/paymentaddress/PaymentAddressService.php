<?php


namespace common\library\paymentaddress;


use common\config\constant\Blockchain;
use common\library\blockchain\BlockchainCryptoFactory;
use common\library\blockchain\CryptoManagerInterface;
use common\library\blockchain\EthereumUsdtCryptoAPI;
use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\models\blockchain\PaymentAddress;
use Yii;

/**
 * Class PaymentAddressService
 * @package common\library\paymentaddress
 */
class PaymentAddressService
{
    private $cryptoManager;

    const FIELD_PAYLOAD = 'payload';
    const FIELD_WIF = 'wif';
    const FIELD_PRIVATE_KEY = 'privateKey';
    /** @var string */
    private $transactionHex;

    /**
     * @param PaymentAddress $paymentAddress
     * @return bool
     * @throws \common\library\exceptions\APICallException
     */
    public function sendToHub(PaymentAddress $paymentAddress) : bool
    {
        $this->cryptoManager = $this->getCryptoManagerByCurrency($paymentAddress->currency_type);
        $this->transactionHex = null;
        if ($this->cryptoManager->getBalanceByAddress($paymentAddress->address) !== 0) {
            $secretKey = $this->getSecretKey($paymentAddress);
            $hubAddress = $this->getHubAddress($paymentAddress->currency_type);
            $this->prepareForTransaction($paymentAddress);
            $this->transactionHex = $this->cryptoManager->sendAllAmount($paymentAddress->address, $hubAddress, $secretKey);
        }
        $paymentAddress->state = PaymentAddress::STATE_NO_FUNDS;
        return $paymentAddress->save();
    }

    /**
     * @param int $currencyType
     * @return CryptoManagerInterface
     */
    protected function getCryptoManagerByCurrency(int $currencyType)
    {
        return BlockchainCryptoFactory::getManagerByCurrency($currencyType);
    }

    public function getSecretKey(PaymentAddress $paymentAddress)
    {
        if ($paymentAddress->currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_BTC) {
            return json_decode($paymentAddress->additional)->{self::FIELD_PAYLOAD}->{self::FIELD_WIF};
        }
        if ($paymentAddress->currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH) {
            return json_decode($paymentAddress->additional)->{self::FIELD_PAYLOAD}->{self::FIELD_PRIVATE_KEY};
        }
        if ($paymentAddress->currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT) {
            return json_decode($paymentAddress->additional)->{self::FIELD_PAYLOAD}->{self::FIELD_PRIVATE_KEY};
        }
        throw new \InvalidArgumentException('Unexpected currency type.');
    }

    /**
     * @param int $currency_type
     * @return string
     */
    public function getHubAddress(int $currency_type)
    {
        if ($currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_BTC) {
            return Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_BITCOIN][Blockchain::PARAM_HUB_ADDRESS];
        }
        if ($currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH) {
            return Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM][Blockchain::PARAM_HUB_ADDRESS];
        }
        if ($currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT) {
            return Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM_USDT][Blockchain::PARAM_HUB_ADDRESS];
        }
        throw new \InvalidArgumentException('Unexpected currency type.');
    }

    /**
     * @param int $currency_type
     * @return string
     */
    public function getHubSecretKey(int $currency_type)
    {
        if ($currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_BTC) {
            return Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_BITCOIN][Blockchain::PARAM_HUB_WIF];
        }
        if ($currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH) {
            return Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM][Blockchain::PARAM_HUB_PRIVATE_KEY];
        }
        if ($currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT) {
            return Yii::$app->params[Blockchain::PARAM_BLOCKCHAIN][Blockchain::PARAM_ETHEREUM_USDT][Blockchain::PARAM_HUB_PRIVATE_KEY];
        }
        throw new \InvalidArgumentException('Unexpected currency type.');
    }

    /**
     * @return string
     */
    public function getTransactionHex(): string
    {
        return $this->transactionHex;
    }

    /**
     * @param PaymentAddress $paymentAddress
     * @return bool
     * @throws \common\library\exceptions\APICallException
     */
    private function prepareForTransaction(PaymentAddress $paymentAddress)
    {
        if ($paymentAddress->currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT) {
            $ethereumManager = $this->getCryptoManagerByCurrency(CryptoCurrencyTypes::CURRENCY_TYPE_ETH);

            if ($ethereumManager->getBalanceByAddress($paymentAddress->address) >= EthereumUsdtCryptoAPI::GAS_LIMIT_DEFAULT) {
                return true;
            }
            $hubAddress = $this->getHubAddress(CryptoCurrencyTypes::CURRENCY_TYPE_ETH);
            $secretKey = $this->getHubSecretKey(CryptoCurrencyTypes::CURRENCY_TYPE_ETH);
            /** @var CryptoManagerInterface $ethereumManager */
            return (bool) $ethereumManager->sendAmount($hubAddress, $paymentAddress->address, EthereumUsdtCryptoAPI::GAS_LIMIT_DEFAULT, $secretKey);
        }
    }

}
