<?php
namespace common\library\collateral;


use common\models\blockchain\PaymentAddress;
use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;
use common\models\collateral\CollateralLoanPayment;
use common\models\payment\Payment;
use yii\db\ActiveQuery;

/**
 * Class CollateralLoanQueryLibrary
 * @package common\library\collateral
 */
class CollateralLoanQueryLibrary
{

    /**
     * @return Collateral[]
     */
    public static function getCollateralsWithoutLoans()
    {
        return Collateral::find()
            ->leftJoin(CollateralLoan::tableName(), CollateralLoan::tableName() . '.collateral_id = ' . Collateral::tableName() . '.id')
            ->where(CollateralLoan::tableName() . '.id IS NULL')
            ->where([Collateral::tableName() . '.status' => Collateral::STATUS_POSTED])
            ->all();
    }


    /**
     * @return ActiveQuery
     */
    private static function getUnusedPaymentAddressQuery()
    {
        return PaymentAddress::find()
            ->innerJoin(CollateralLoanPayment::tableName(), CollateralLoanPayment::tableName() . '.payment_address_id = ' . PaymentAddress::tableName() . '.id')
            ->leftJoin(Payment::tableName(), Payment::tableName() . '.id = ' . CollateralLoanPayment::tableName() . '.payment_id')
            ->where(Payment::tableName() . '.id IS NULL');
    }


    /**
     * @param CollateralLoan $collateralLoan
     * @return array|null|PaymentAddress
     */
    public static function getUnusedPaymentAddress(CollateralLoan $collateralLoan)
    {
        return self::getUnusedPaymentAddressQuery()
            ->andWhere([CollateralLoanPayment::tableName() . '.collateral_loan_id' => $collateralLoan->id])
            ->one();

    }

    /**
     * @return array|null|PaymentAddress[]
     */
    public static function getUnusedPaymentAddresses()
    {
        return self::getUnusedPaymentAddressQuery()
            ->all();
    }
}
