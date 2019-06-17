<?php

namespace common\models\collateral;

use common\models\payment\Payment;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%collateral_payment}}".
 *
 * @property integer $id
 * @property integer $collateral_id
 * @property integer $payment_id
 *
 * @property Payment $payment
 * @property CollateralLoan $collateralLoan
 */
class CollateralPayment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collateral_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collateral_id', 'payment_id'], 'required'],
            [['collateral_id', 'payment_id'], 'integer'],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::class, 'targetAttribute' => ['payment_id' => 'id']],
            [['collateral_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collateral::class, 'targetAttribute' => ['collateral_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'collateral_id' => Yii::t('app', 'Collateral ID'),
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
    public function getCollateral()
    {
        return $this->hasOne(Collateral::class, ['id' => 'collateral_id']);
    }
}
