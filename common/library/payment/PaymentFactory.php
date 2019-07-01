<?php

namespace common\library\payment;

use common\library\exceptions\InvalidModelException;
use common\models\blockchain\PaymentAddress;
use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;
use common\models\loan\Loan;
use common\models\payment\Payment;
use yii\base\InvalidCallException;

/**
 * Class PaymentService
 * @package common\library\payment
 */
class PaymentFactory
{
    /** @var Payment */
    private $entity;


    /**
     *
     * Method creates new Payment. Now address is written to 'hash'
     * because only single wallet is created specially for single transaction.
     *
     * @param Loan $loan
     * @param int $amount
     * @param string $hash
     * @return Payment
     * @throws InvalidModelException
     * @throws InvalidCallException
     */
    public function createForLoan(Loan $loan, int $amount, string $hash = null) : Payment
    {
        $payment = new Payment();
        $payment->amount = $amount;
        $payment->hash = $hash;
        $payment->currency_type = $loan->currency_type;
        if (!$payment->save()) {
            throw new InvalidModelException($payment);
        }
        $this->entity = $payment;

        $loan->link('payments', $payment);
        return $this->entity;
    }

    /**
     *
     * Method creates new Payment. Now address is written to 'hash'
     * because only single wallet is created specially for single transaction.
     *
     * @param Collateral $collateral
     * @param int $amount
     * @param string $hash
     * @return Payment
     * @throws InvalidModelException
     * @throws InvalidCallException
     */
    public function createForCollateral(Collateral $collateral, int $amount, string $hash) : Payment
    {
        $payment = new Payment();
        $payment->amount = $amount;
        $payment->hash = $hash;
        $payment->currency_type = $collateral->currency_type;
        if (!$payment->save()) {
           throw new InvalidModelException($payment);
        }
        $this->entity = $payment;

        $collateral->link('payment', $payment);
        return $this->entity;
    }

    /**
     * @return Payment
     */
    public function getEntity(): Payment
    {
        return $this->entity;
    }

    /**
     *
     * Method creates new Payment. Now address is written to 'hash'
     * because only single wallet is created specially for single transaction.
     *
     * @param CollateralLoan $collateral
     * @param PaymentAddress $paymentAddress
     * @param int $amount
     * @return Payment
     * @throws InvalidModelException
     */
    public function createForCollateralLoan(CollateralLoan $collateral, PaymentAddress $paymentAddress, int $amount) : Payment
    {
        $payment = new Payment();
        $payment->amount = $amount;
        $payment->hash = $paymentAddress->address;
        $payment->currency_type = $collateral->currency_type;
        if (!$payment->save()) {
            throw new InvalidModelException($payment);
        }
        $this->entity = $payment;

        $paymentAddress->collateralLoanPayment->link('payment', $payment);
        return $this->entity;
    }

}
