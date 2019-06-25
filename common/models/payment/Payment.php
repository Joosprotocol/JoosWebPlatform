<?php

namespace common\models\payment;

use common\behaviors\AmountBehavior;
use common\models\blockchain\PaymentAddress;
use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoan;
use common\models\collateral\CollateralLoanPayment;
use common\models\collateral\CollateralPayment;
use common\models\loan\Loan;
use common\models\loan\LoanPayment;
use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payment}}".
 *
 * @property integer $id
 * @property integer $currency_type
 * @property integer $amount
 * @property string $hash
 * @property string $created
 * @property string $updated
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Loan $loan
 * @property CollateralLoan $collateralLoan
 * @property Collateral $collateral
 * @property CollateralLoanPayment $collateralLoanPayment
 * @property CollateralPayment $collateralPayment
 * @property PaymentAddress $collateralLoanPaymentAddress
 *
 * @property string $formattedAmount
 * @property string $formattedAmountWithCurrency
 * @property string $currencyName
 *
 */
class Payment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * Method for defining behaviors
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            AmountBehavior::class
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_type', 'amount'], 'required'],
            [['currency_type', 'created_at', 'updated_at', 'currency_type', 'amount'], 'integer'],
            [['hash'], 'string'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'currency_type' => Yii::t('app', 'Currency Type'),
            'amount' => Yii::t('app', 'Amount'),
            'hash' => Yii::t('app', 'Hash'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateralPayment()
    {
        return $this->hasOne(CollateralPayment::class, ['payment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateral()
    {
        return $this->hasOne(Collateral::class, ['id' => 'collateral_id'])
            ->via('collateralPayment');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateralLoanPayment()
    {
        return $this->hasOne(CollateralLoanPayment::class, ['payment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateralLoan()
    {
        return $this->hasOne(CollateralLoan::class, ['id' => 'collateral_loan_id'])
            ->via('collateralLoanPayment');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateralLoanPaymentAddress()
    {
        return $this->hasOne(CollateralLoan::class, ['id' => 'payment_address_id'])
            ->via('collateralLoanPayment');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoanPayment()
    {
        return $this->hasOne(LoanPayment::class, ['payment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoan()
    {
        return $this->hasOne(Loan::class, ['id' => 'loan_id'])
            ->via('loanPayment');
    }

}
