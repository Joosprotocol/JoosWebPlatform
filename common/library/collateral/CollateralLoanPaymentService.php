<?php

namespace common\library\collateral;

use common\library\blockchain\BlockchainCryptoFactory;
use common\library\blockchain\CryptoManagerInterface;
use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\ethereum\EthereumAPI;
use common\library\exceptions\InvalidModelException;
use common\library\notification\NotificationService;
use common\library\payment\PaymentFactory;
use common\models\blockchain\PaymentAddress;
use common\models\collateral\CollateralLoan;
use common\models\collateral\CollateralLoanPayment;
use common\models\collateral\smartcontract\CollateralLoanManagerBlockChainAdapter;
use common\models\payment\Payment;
use common\models\user\BlockchainProfile;
use itmaster\core\exceptions\RequiredParamException;
use Yii;
use \Exception;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;
use yii\web\UnauthorizedHttpException;

/**
 * Class CollateralLoanPaymentService
 * @package frontend\library\collateral
 */
class CollateralLoanPaymentService
{

    const CONFIG_HUB_ADDRESS = 'hubAddress';
    const CONFIG_HUB_PRIVATE_KEY = 'hubPrivateKey';
    const CONFIG_HUB_WIF = 'hubWif';

    const CONFIG_ETHEREUM_USDT = 'ethereumUsdt';
    const CONFIG_ETHEREUM = 'ethereum';
    const CONFIG_BITCOIN = 'bitcoin';

    /** @var CollateralLoan  */
    private $collateralLoan;

    /** @var PaymentAddress|null  */
    private $paymentAddress;

    /** @var CryptoManagerInterface */
    private $loanCryptoManager;
    /** @var CryptoManagerInterface */
    private $collateralCryptoManager;

    /** @var bool  */
    private $isAlreadyPaid = false;

    /** @var int  */
    private $paidAmount;
    /** @var  Payment */
    private $payment;
    /** @var EthereumAPI */
    private $ethereumApi;
    /** @var BlockchainProfile */
    private $withdrawProfile;
    /** @var Exception */
    private $lastException;
    /** @var string */
    private $hubAddress;
    /** @var string */
    private $hubSecretKey;


    /**
     * LoanOfferForm constructor.
     * @param CollateralLoan $collateralLoan
     * @throws UnauthorizedHttpException
     */
    public function __construct(CollateralLoan $collateralLoan)
    {
        $this->collateralLoan = $collateralLoan;
        $this->ethereumApi = Yii::$app->ethereumAPI;

        if ($this->collateralLoan->currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT) {
            $this->loanCryptoManager = BlockchainCryptoFactory::getManagerByCurrency($collateralLoan->currency_type);
            $this->collateralCryptoManager = BlockchainCryptoFactory::getManagerByCurrency($collateralLoan->collateral->currency_type);
        } else {
            throw new \LogicException('Incorrect collateral loan blockchain type.');
        }
    }



    public function withdraw()
    {
        if ($this->collateralLoan->status !== CollateralLoan::STATUS_PAID) {
            throw new \LogicException('The collateral can be returned after full payment of the loan.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            $this->defineHubWalletAccess();
            $this->withdrawProfile = $this->getWithdrawAddress();
            $this->collateralLoan->withdrawn_profile_id = $this->withdrawProfile->id;
            if (!$this->collateralLoan->save()) {
                throw new InvalidModelException($this->collateralLoan);
            }

            $collateralLoanManagerAdapter = new CollateralLoanManagerBlockChainAdapter($this->ethereumApi);
            $collateralLoanManagerAdapter->setAsWithdrawn($this->collateralLoan->hash_id);
            $this->collateralCryptoManager->sendAmount($this->hubAddress, $this->withdrawProfile->address, $this->collateralLoan->collateral_amount, $this->hubSecretKey);
            $this->sendCollateralLoanWithdrawNotification();

            $transaction->commit();
            return true;
        } catch (\Exception $exception) {
            $this->lastException = $exception;
            $transaction->rollBack();
            Yii::error($exception->getMessage(), 'collateral');

            return false;
        }
    }

    /**
     * @return BlockchainProfile
     * @throws RequiredParamException
     * @throws \LogicException
     */
    private function getWithdrawAddress() : BlockchainProfile
    {
        switch ($this->collateralLoan->collateral->currency_type) {
            case CryptoCurrencyTypes::CURRENCY_TYPE_BTC:
                $blockchainProfile = $this->collateralLoan->collateral->investor->bitcoinProfile;
                break;
            case CryptoCurrencyTypes::CURRENCY_TYPE_ETH:
                $blockchainProfile = $this->collateralLoan->collateral->investor->ethereumProfile;
                break;
            case CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT:
                $blockchainProfile = $this->collateralLoan->collateral->investor->ethereumProfile;
                break;
            default:
                throw new RequiredParamException('Unknown currency type.');
        }

        if (empty($blockchainProfile)) {
            throw new \LogicException('Withdraw address not set.');
        }
        return $blockchainProfile;
    }

    /**
     * @return array
     * @throws RequiredParamException
     */
    private function defineHubWalletAccess() : array
    {
        if (empty(Yii::$app->params['blockchain'])) {
            throw new RequiredParamException('"blockchain" param is not found.');
        }

        $params = Yii::$app->params['blockchain'];

        $currencyType = $this->collateralLoan->collateral->currency_type;

        if ($currencyType === CryptoCurrencyTypes::CURRENCY_TYPE_BTC) {
            $this->hubAddress = $params[self::CONFIG_BITCOIN][self::CONFIG_HUB_ADDRESS];
            $this->hubSecretKey = $params[self::CONFIG_BITCOIN][self::CONFIG_HUB_WIF];
        } elseif ($currencyType === CryptoCurrencyTypes::CURRENCY_TYPE_ETH || $currencyType === CryptoCurrencyTypes::CURRENCY_TYPE_ETH_USDT) {
            $this->hubAddress = $params[self::CONFIG_ETHEREUM][self::CONFIG_HUB_ADDRESS];
            $this->hubSecretKey = $params[self::CONFIG_ETHEREUM][self::CONFIG_HUB_PRIVATE_KEY];
        } else {
            throw new RequiredParamException('Unknown currency type.');
        }

        return $params;
    }

    /**
     * @return bool
     */
    public function prepareAddress()
    {

        if (!$this->isAllowedToPay()) {
            throw new \LogicException('Collateral Loan has incorrect status.');
        }

        $this->paymentAddress = CollateralLoanQueryLibrary::getUnusedPaymentAddress($this->collateralLoan);

        if (!empty($this->paymentAddress)) {
            return true;
        }
        try {
            $this->generateAddress();

        } catch (\Exception $exception) {
            \Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Could not create payment address. Please try again later.'));
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isAllowedToPay() : bool
    {
        if (in_array($this->collateralLoan->status, [CollateralLoan::STATUS_SIGNED, CollateralLoan::STATUS_PARTIALLY_PAID])) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->collateralLoan->save()) {
            $this->sendCollateralLoanNewPaymentNotification();
            return true;
        }
        return false;
    }

    /**
     * @return CollateralLoan
     */
    public function getCollateralLoan() : CollateralLoan
    {
        return $this->collateralLoan;
    }

    /**
     * @return void
     */
    public function refreshPayment() : void
    {
        if (in_array($this->collateralLoan->status, [CollateralLoan::STATUS_SIGNED, CollateralLoan::STATUS_PARTIALLY_PAID]) && $this->isPaid()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                if (!$this->collateralLoan->save(false)) {
                    throw new InvalidModelException($this->collateralLoan);
                }
                $paymentService = new PaymentFactory();
                $this->payment = $paymentService->createForCollateralLoan($this->collateralLoan, $this->paymentAddress, $this->paidAmount);

                $collateralLoanManagerAdapter = new CollateralLoanManagerBlockChainAdapter($this->ethereumApi);
                $collateralLoanManagerAdapter->createPayment($this->collateralLoan->hash_id, $this->paidAmount);

                $this->sendCollateralLoanNewPaymentNotification();

                $transaction->commit();
            } catch (\Exception $exception) {
                $transaction->rollBack();
            }
        }
    }

    /**
     * @return bool
     */
    public function isPaid() : bool
    {
        if (empty($this->paymentAddress)) {
            return false;
        }

        $this->isAlreadyPaid = $this->collateralLoan->status === CollateralLoan::STATUS_PAID;
        $this->paidAmount = $this->loanCryptoManager->getBalanceByAddress($this->paymentAddress->address);

        if ($this->paidAmount > 0) {
            return true;
        }
        return false;
    }

    /**
     * @return void
     */
    protected function sendCollateralLoanNewPaymentNotification()
    {
        NotificationService::sendCollateralLoanNewPaymentNotification($this->payment);
    }

    private function sendCollateralLoanWithdrawNotification()
    {
        NotificationService::sendCollateralLoanWithdrawNotification($this->collateralLoan, $this->withdrawProfile);
    }


    /**
     * @throws InvalidCallException if the method is unable to link two models.
     */
    private function generateAddress()
    {
        $addressInfo = $this->loanCryptoManager->generateAddress();

        $paymentAddress = new PaymentAddress();
        $paymentAddress->loadDefaultValues();
        $paymentAddress->address = $this->loanCryptoManager->getPaymentAddress($addressInfo);
        $paymentAddress->additional = json_encode($addressInfo);
        $paymentAddress->currency_type = $this->collateralLoan->currency_type;
        if (!$paymentAddress->save()) {
            throw new InvalidModelException($paymentAddress);
        }
        $this->paymentAddress = $paymentAddress;
        $collateralLoanPayment = new CollateralLoanPayment();
        $collateralLoanPayment->link('collateralLoan', $this->collateralLoan);
        $collateralLoanPayment->link('paymentAddress', $paymentAddress);

        return true;

    }

    /**
     * @return int
     */
    public function getPaymentsTotalAmount(): int
    {
        if (empty($this->collateralLoan->payments)) {
            return 0;
        }

        $values = ArrayHelper::getColumn($this->collateralLoan->payments, 'amount');
        return array_sum($values);
    }

    /**
     * @return string
     */
    public function getFormattedPaymentsTotalAmountWithCurrencies(): string
    {
        return (string) ($this->getPaymentsTotalAmount() / CryptoCurrencyTypes::precisionList()[$this->collateralLoan->currency_type]) . ' ' . $this->collateralLoan->currencyName;
    }

    /**
     * @return bool
     */
    public function isAlreadyPaid(): bool
    {
        return $this->isAlreadyPaid;
    }

    /**
     * @return int
     */
    public function getPaidAmount(): int
    {
        return $this->paidAmount;
    }

    /**
     * @return PaymentAddress
     */
    public function getPaymentAddress(): PaymentAddress
    {
        return $this->paymentAddress;
    }

    /**
     * @return Exception
     */
    public function getLastException(): Exception
    {
        return $this->lastException;
    }

}
