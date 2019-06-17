<?php

namespace common\models\collateral;

use common\behaviors\HashIdBehavior;
use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\models\blockchain\PaymentAddress;
use common\models\payment\PaymentAmountInterface;
use common\models\payment\Payment;
use common\models\user\User;
use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%collateral}}".
 *
 * @property integer $id
 * @property integer $investor_id
 * @property integer $status
 * @property integer $amount
 * @property integer $start_amount
 * @property integer $currency_type
 * @property integer $payment_address_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $hash_id
 *
 * @property User $investor
 * @property PaymentAddress $paymentAddress
 * @property CollateralLoan[] $collateralLoans
 *
 * @property string $statusName
 * @property string $currencyName
 * @property float|int $formattedAmount
 * @property Payment $payment
 * @property CollateralPayment $collateralPayment
 */
class Collateral extends ActiveRecord
{
    const STATUS_STARTED = 0; // after creation
    const STATUS_POSTED = 1; // after successful crypto payment
    const STATUS_PARTIALLY_LOANED = 2; // partially loaned by user/platform
    const STATUS_FULLY_LOANED = 3; // fully loaned
    const STATUS_PAUSED = 4;
    const STATUS_OVERDUE = 5;
    const STATUS_FINISHED = 6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collateral}}';
    }

    /**
     * Method for defining behaviors
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            HashIdBehavior::class
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'start_amount', 'investor_id', 'status', 'currency_type', 'created_at', 'updated_at'], 'integer'],
            [['amount', 'start_amount', 'status', 'currency_type'], 'required'],
            [['currency_type'], 'in', 'range' => array_keys(self::currencyPostingTypeList())],
            [['investor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['investor_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public static function statusList()
    {
        return [
            self::STATUS_STARTED => Yii::t('app', 'Started'),
            self::STATUS_POSTED => Yii::t('app', 'Posted'),
            self::STATUS_FULLY_LOANED => Yii::t('app', 'Fully Loaned'),
            self::STATUS_PARTIALLY_LOANED => Yii::t('app', 'Partially Loaned'),
            self::STATUS_PAUSED => Yii::t('app', 'Paused'),
            self::STATUS_OVERDUE => Yii::t('app', 'Overdue'),
            self::STATUS_FINISHED => Yii::t('app', 'Finished'),
        ];
    }

    /**
     * @return array
     */
    public static function currencyTypeList()
    {
        return CryptoCurrencyTypes::currencyTypeList();
    }

    /**
     * @return array
     */
    public static function currencyPostingTypeList()
    {
        return [
            CryptoCurrencyTypes::CURRENCY_TYPE_BTC => Yii::t('app', 'BTC'),
            CryptoCurrencyTypes::CURRENCY_TYPE_ETH => Yii::t('app', 'ETH'),
        ];
    }

    /**
     * @return array
     */
    public function getCurrencyName()
    {
        return self::currencyTypeList()[$this->currency_type];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'investor_id' => Yii::t('app', 'Investor ID'),
            'status' => Yii::t('app', 'Status'),
            'amount' => Yii::t('app', 'Amount'),
            'start_amount' => Yii::t('app', 'Start Amount'),
            'currency_type' => Yii::t('app', 'Currency Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return string|null
     */
    public function getStatusName()
    {
        if (array_key_exists($this->status, self::statusList())) {
            return self::statusList()[$this->status];
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getLoanedAmount()
    {
        return Collateral::find()
            ->where([Collateral::tableName() . '.id'  => $this->id])
            ->leftJoin(CollateralLoan::tableName(), CollateralLoan::tableName() . '.collateral_id = ' . Collateral::tableName() . '.id')
            ->sum(CollateralLoan::tableName() . '.amount');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvestor()
    {
        return $this->hasOne(User::class, ['id' => 'investor_id']);
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
    public function getCollateralLoans()
    {
        return $this->hasMany(CollateralLoan::class, ['collateral_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateralPayment()
    {
        return $this->hasOne(CollateralPayment::class, ['collateral_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::class, ['id' => 'payment_id'])
            ->via('collateralPayment');
    }

    /**
     * @return float|int
     */
    public function getFormattedAmount()
    {
        return $this->amount / CryptoCurrencyTypes::precisionList()[$this->currency_type];
    }

    /**
     * @return string
     */
    public function getFormattedAmountWithCurrency()
    {
        return (string) $this->getFormattedAmount() . ' ' . $this->currencyName;
    }

}
