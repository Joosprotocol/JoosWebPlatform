<?php

namespace common\models\collateral;

use common\behaviors\HashIdBehavior;
use common\library\business\OverdueInterface;
use common\library\cryptocurrency\CryptoCurrencyTypes;
use common\library\date\DateIntervalEnhanced;
use common\models\payment\Payment;
use common\models\user\BlockchainProfile;
use common\models\user\User;
use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%collateral_loan}}".
 *
 * @property integer $id
 * @property integer $lender_id
 * @property integer $collateral_id
 * @property integer $status
 * @property integer $amount
 * @property integer $collateral_amount
 * @property integer $period
 * @property float $lvr
 * @property float $fee
 * @property integer $is_platform
 * @property integer $currency_type
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $hash_id
 * @property integer $withdrawn_profile_id
 *
 * @property Collateral $collateral
 * @property User $lender
 * @property CollateralLoanPayment[] $collateralLoanPayment
 * @property Payment[] $payments
 *
 * @property string $formattedPeriod
 * @property string $formattedAmount
 * @property string $formattedAmountToPay
 * @property string $currencyName
 * @property string $statusName
 * @property int $amountToPay
 * @property int signed_at
 */
class CollateralLoan extends ActiveRecord implements OverdueInterface
{
    const STATUS_STARTED = 0; // after creation
    const STATUS_SIGNED = 1; // signed by person+person or person+platform
    const STATUS_PARTIALLY_PAID = 2; // loan partially paid
    const STATUS_PAID = 3; // loan paid
    const STATUS_PAUSED = 4; // loan paid
    const STATUS_OVERDUE = 5;
    const STATUS_WITHDRAWN = 6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collateral_loan}}';
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
            [['amount', 'collateral_amount', 'lender_id',  'collateral_id', 'period', 'status', 'currency_type', 'created_at', 'updated_at', 'withdrawn_profile_id'], 'integer'],
            [['status', 'currency_type', 'is_platform', 'period'], 'required'],
            [['lvr', 'fee'], 'double'],
            ['is_platform', 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['withdrawn_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => BlockchainProfile::class, 'targetAttribute' => ['withdrawn_profile_id' => 'id']],
            [['collateral_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collateral::class, 'targetAttribute' => ['collateral_id' => 'id']],
            [['lender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['lender_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lender_id' => Yii::t('app', 'Lender ID'),
            'is_platform' => Yii::t('app', 'Is From Platform'),
            'collateral_id' => Yii::t('app', 'Collateral ID'),
            'status' => Yii::t('app', 'Status'),
            'amount' => Yii::t('app', 'Amount'),
            'collateral_amount' => Yii::t('app', 'Collateral Amount'),
            'lvr' => Yii::t('app', 'LVR'),
            'fee' => Yii::t('app', 'Fee'),
            'currency_type' => Yii::t('app', 'Currency Type'),
            'withdrawn_profile_id' => Yii::t('app', 'Withdrawn Profile Id'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public static function statusList()
    {
        return [
            self::STATUS_STARTED => Yii::t('app', 'Started'),
            self::STATUS_SIGNED => Yii::t('app', 'Signed'),
            self::STATUS_PAID => Yii::t('app', 'Paid'),
            self::STATUS_PARTIALLY_PAID => Yii::t('app', 'Partially Paid'),
            self::STATUS_PAUSED => Yii::t('app', 'Paused'),
            self::STATUS_OVERDUE => Yii::t('app', 'Overdue'),
            self::STATUS_WITHDRAWN => Yii::t('app', 'Withdrawn'),
        ];
    }

    /**
     * @return string
     */
    public function getFormattedPeriod()
    {
        $interval = new DateIntervalEnhanced('PT' . $this->period . 'S');
        $interval->recalculate();
        return $interval->getFormatted();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateral()
    {
        return $this->hasOne(Collateral::class, ['id' => 'collateral_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLender()
    {
        return $this->hasOne(User::class, ['id' => 'lender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollateralLoanPayment()
    {
        return $this->hasMany(CollateralLoanPayment::class, ['collateral_loan_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['id' => 'payment_id'])
            ->via('collateralLoanPayment');
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
    public function getCurrencyName()
    {
        return self::currencyTypeList()[$this->currency_type];
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
     * @return int
     */
    public function getAmountToPay()
    {
        return (int) ($this->amount * (100 + $this->fee) / 100);
    }

    /**
     * @return float|int
     */
    public function getFormattedAmountToPay()
    {
        return $this->getAmountToPay() / CryptoCurrencyTypes::precisionList()[$this->currency_type];
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

    /**
     * @return int
     */
    public function getSignedAt(): int
    {
        return $this->signed_at;
    }

    /**
     * @return int
     */
    public function getPeriod(): int
    {
        return $this->period;
    }

}
