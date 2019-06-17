<?php

namespace common\library\collateral;


use common\library\blockchain\BitcoinCryptoManager;
use common\library\blockchain\CryptoManagerInterface;
use common\library\blockchain\EthereumCryptoManager;
use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\exceptions\InvalidModelException;
use common\library\notification\NotificationService;
use common\library\payment\PaymentService;
use common\models\blockchain\PaymentAddress;
use common\models\collateral\Collateral;
use Yii;
use yii\base\InvalidCallException;
use yii\base\Model;
use yii\web\UnauthorizedHttpException;

class CollateralPaymentService extends Model
{

    /** @var Collateral  */
    private $collateral;

    /** @var  CryptoManagerInterface */
    private $cryptoManager;

    /** @var bool  */
    private $isAlreadyPaid = false;

    /** @var int  */
    private $paidAmount;


    /**
     * LoanOfferForm constructor.
     * @param Collateral $collateral
     * @throws UnauthorizedHttpException
     * @internal param int $init_type
     */
    public function __construct(Collateral $collateral)
    {
        parent::__construct([]);

        $this->collateral = $collateral;
        if ($this->collateral->status !== Collateral::STATUS_STARTED) {
            throw new \LogicException('Collateral has incorrect status.');
        }

        if ($this->collateral->currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_BTC) {
            $this->cryptoManager = new BitcoinCryptoManager();
        } elseif ($this->collateral->currency_type === CryptoCurrencyTypes::CURRENCY_TYPE_ETH) {
            $this->cryptoManager = new EthereumCryptoManager();
        } else {
            throw new \LogicException('Incorrect collateral blockchain type.');
        }

    }

    /**
     * @return bool
     */
    public function prepareAddress()
    {
        if (!empty($this->collateral->payment_address_id)) {
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
     * @return Collateral
     */
    public function getCollateral() : Collateral
    {
        return $this->collateral;
    }

    /**
     * @return void
     */
    public function refreshPayment() : void
    {
        if ($this->collateral->status === Collateral::STATUS_STARTED && $this->isPaid()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $this->collateral->status = Collateral::STATUS_POSTED;
                if (!$this->collateral->save(false)) {
                    throw new InvalidModelException($this->collateral);
                }
                $this->sendCollateralPaidNotification();
                $paymentService = new PaymentService();
                $paymentService->createForCollateral($this->collateral, $this->paidAmount, $this->collateral->paymentAddress->address);

                $this->isAlreadyPaid = true;

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
        if (empty($this->collateral->paymentAddress)) {
            return false;
        }

        $realBalance = $this->cryptoManager->getBalanceByAddress($this->collateral->paymentAddress->address);
        $neededBalance = $this->collateral->amount;

        $this->paidAmount = $realBalance;
        if ($realBalance >= $neededBalance) {
            return true;
        }
        return false;
    }

    /**
     * @return void
     */
    protected function sendCollateralPaidNotification()
    {
        NotificationService::sendCollateralPaidNotification($this->collateral);
    }


    /**
     * @throws InvalidCallException if the method is unable to link two models.
     */
    private function generateAddress()
    {
        $addressInfo = $this->cryptoManager->generateAddress();

        $paymentAddress = new PaymentAddress();
        $paymentAddress->loadDefaultValues();
        $paymentAddress->address = $this->cryptoManager->getPaymentAddress($addressInfo);
        $paymentAddress->additional = json_encode($addressInfo);
        $paymentAddress->currency_type = $this->collateral->currency_type;
        $paymentAddress->save();

        return $this->collateral->link('paymentAddress', $paymentAddress);
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

}
