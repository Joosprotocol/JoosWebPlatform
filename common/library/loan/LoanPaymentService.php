<?php

namespace common\library\loan;

use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\ethereum\EthereumAPI;
use common\library\exceptions\InvalidModelException;
use common\library\notification\NotificationService;
use common\library\payment\PaymentFactory;
use common\models\blockchain\PaymentAddress;
use common\models\loan\Loan;
use common\models\loan\smartcontract\LoanManagerBlockChainAdapter;
use common\models\payment\Payment;
use common\models\user\User;
use Yii;
use \Exception;
use yii\helpers\ArrayHelper;

/**
 * Class LoanPaymentService
 * @package frontend\library\collateral
 */
class LoanPaymentService
{

    const HASH_MANUAL = 'manual';

    /** @var Loan  */
    private $loan;

    /** @var PaymentAddress|null  */
    private $paymentAddress;

    /** @var  Payment */
    private $payment;
    /** @var EthereumAPI */
    private $ethereumApi;

    /** @var Exception */
    private $lastException;


    /**
     * LoanOfferForm constructor.
     * @param Loan $loan
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
        $this->ethereumApi = Yii::$app->ethereumAPI;

    }

    /**
     *
     * Creates payment only for Manual loan.
     *
     * @return void
     */
    public function setAsPaid() : void
    {
        if (!in_array($this->loan->status, [Loan::STATUS_SIGNED, Loan::STATUS_PARTIALLY_PAID])) {
            throw new \LogicException('Incorrect loan status.');
        }

        if ($this->loan->currency_type !== CryptoCurrencyTypes::CURRENCY_TYPE_USD_MANUAL) {
            throw new \LogicException('Incorrect loan currency type.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            if (!$this->loan->save(false)) {
                throw new InvalidModelException($this->loan);
            }
            $paymentService = new PaymentFactory();
            $this->payment = $paymentService->createForLoan($this->loan, $this->loan->getAmountToPay(), self::HASH_MANUAL);

            $loanManagerAdapter = new LoanManagerBlockChainAdapter($this->ethereumApi);
            $loanManagerAdapter->createPayment($this->loan->hash_id, $this->loan->getAmountToPay());

            $this->sendLoanNewPaymentNotification();

            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
        }

    }


    /**
     * @return bool
     */
    public function isAllowedToPay() : bool
    {
        if (in_array($this->loan->status, $this->allowableToPayStatusList())) {
            return true;
        }
        return false;
    }

    public function allowableToPayStatusList() : array
    {
        return [
            Loan::STATUS_SIGNED,
            Loan::STATUS_PARTIALLY_PAID,
            Loan::STATUS_OVERDUE,
        ];
    }

    /**
     * @return void
     */
    protected function sendLoanNewPaymentNotification()
    {
        NotificationService::sendLoanNewPaymentNotification($this->payment);
    }

    /**
     * @return int
     */
    public function getPaymentsTotalAmount(): int
    {
        if (empty($this->loan->payments)) {
            return 0;
        }

        $values = ArrayHelper::getColumn($this->loan->payments, 'amount');
        return array_sum($values);
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

    /**
     * @param User $user
     * @return bool
     */
    public function canUserSetAsPaid(User $user)
    {
        $loanService = new LoanService($this->loan);
        $loanService->setUser($user);
        return $loanService->isLenderOfLoan();
    }

}
