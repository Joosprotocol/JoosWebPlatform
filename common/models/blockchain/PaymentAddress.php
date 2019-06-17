<?php

namespace common\models\blockchain;

use common\models\collateral\Collateral;
use common\models\collateral\CollateralLoanPayment;
use common\models\payment\Payment;
use itmaster\core\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payment_address}}".
 *
 * @property integer $id
 * @property string $address
 * @property integer $currency_type
 * @property string $additional
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Collateral $collateral
 * @property CollateralLoanPayment $collateralLoanPayment
 * @property Payment $paymentOfCollateralLoan
 */
class PaymentAddress extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_address}}';
    }

    /**
     * Method for defining behaviors
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'currency_type'], 'required'],
            [['currency_type', 'created_at', 'updated_at'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['additional'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'currency_type' => 'Currency Type',
            'additional' => 'Additional',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateral()
    {
        return $this->hasOne(Collateral::class, ['payment_address_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateralLoanPayment()
    {
        return $this->hasOne(CollateralLoanPayment::class, ['payment_address_id' => 'id']);
    }

    /**
     * @return $this
     */
    public function getPaymentOfCollateralLoan()
    {
        return $this->hasOne(Payment::class, ['id' => 'payment_id'])
            ->via('collateralLoanPayment');
    }
}
