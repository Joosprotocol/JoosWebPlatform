<?php

namespace common\models\collateral;

use common\models\blockchain\PaymentAddress;
use common\models\payment\Payment;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%collateral_loan_payment}}".
 *
 * @property integer $id
 * @property integer $collateral_loan_id
 * @property integer $payment_id
 * @property integer $payment_address_id
 *
 * @property Payment $payment
 * @property CollateralLoan $collateralLoan
 */
class CollateralLoanPayment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collateral_loan_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collateral_loan_id', 'payment_id'], 'required'],
            [['collateral_loan_id', 'payment_id'], 'integer'],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::class, 'targetAttribute' => ['payment_id' => 'id']],
            [['collateral_loan_id'], 'exist', 'skipOnError' => true, 'targetClass' => CollateralLoan::class, 'targetAttribute' => ['collateral_loan_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'collateral_loan_id' => Yii::t('app', 'Collateral Loan ID'),
            'payment_id' => Yii::t('app', 'Payment ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::class, ['id' => 'payment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentAddress()
    {
        return $this->hasOne(PaymentAddress::class, ['id' => 'payment_address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateralLoan()
    {
        return $this->hasOne(CollateralLoan::class, ['id' => 'collateral_loan_id']);
    }
}
