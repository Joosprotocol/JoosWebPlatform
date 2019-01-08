<?php

namespace common\models\loan;

use common\library\date\DateIntervalEnhanced;
use common\models\payment\Payment;
use common\models\user\User;
use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%loan}}".
 *
 * @property integer $id
 * @property integer $lender_id
 * @property integer $borrower_id
 * @property integer $status
 * @property string $amount
 * @property integer $period
 * @property integer $init_type
 * @property integer $currency_type
 * @property string $secret_key
 * @property string $ref_slug
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $borrower
 * @property User $lender
 * @property LoanStatusHistory[] $loanStatusHistories
 */
class Loan extends ActiveRecord
{

    const INIT_TYPE_OFFER = 0;
    const INIT_TYPE_REQUEST = 1;

    const CURRENCY_TYPE_MANUAL = 0;
    const CURRENCY_TYPE_JOOS = 1;

    const STATUS_STARTED = 0; // after creation
    const STATUS_SIGNED = 1; // signed by two person
    const STATUS_PAID = 2; // loan paid
    const STATUS_PAUSED = 3; // loan paid
    const STATUS_OVERDUE = 4; // loan paid

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%loan}}';
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
            [['lender_id', 'borrower_id', 'status', 'period', 'currency_type', 'init_type', 'created_at', 'updated_at'], 'integer'],
            [['status', 'currency_type', 'init_type'], 'required'],
            [['amount'], 'number'],
            [['secret_key'], 'string', 'max' => 255],
            [['ref_slug'], 'string', 'max' => 10],
            [['currency_type'], 'in', 'range' => array_keys(self::currencyTypeList())],
            [['init_type'], 'in', 'range' => array_keys(self::initTypeList())],
            [['status'], 'in', 'range' => array_keys(self::statusList())],
            [['borrower_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['borrower_id' => 'id']],
            [['lender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['lender_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public static function currencyTypeList()
    {
        return [
            self::CURRENCY_TYPE_MANUAL => Yii::t('app', 'Manual'),
            self::CURRENCY_TYPE_JOOS => Yii::t('app', 'JOOS'),
        ];
    }

    /**
     * @return array
     */
    public static function initTypeList()
    {
        return [
            self::INIT_TYPE_OFFER => Yii::t('app', 'Offer'),
            self::INIT_TYPE_REQUEST => Yii::t('app', 'Request'),
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
            self::STATUS_PAUSED => Yii::t('app', 'Paused'),
            self::STATUS_OVERDUE => Yii::t('app', 'Overdue'),
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
            'borrower_id' => Yii::t('app', 'Borrower ID'),
            'status' => Yii::t('app', 'Status'),
            'amount' => Yii::t('app', 'Amount'),
            'period' => Yii::t('app', 'Period'),
            'init_type' => Yii::t('app', 'Init Type'),
            'currency_type' => Yii::t('app', 'Currency'),
            'secret_key' => Yii::t('app', 'Secret Key'),
            'ref_slug' => Yii::t('app', 'Ref Slug'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBorrower()
    {
        return $this->hasOne(User::class, ['id' => 'borrower_id']);
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
    public function getLoanStatusHistories()
    {
        return $this->hasMany(LoanStatusHistory::class, ['loan_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['loan_id' => 'id']);
    }

    /**
     * @return string|null
     */
    public function getCurrencyTypeName()
    {
        if (array_key_exists($this->currency_type, self::currencyTypeList())) {
            return self::currencyTypeList()[$this->currency_type];
        }
        return null;
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
     * @return string|null
     */
    public function getInitTypeName()
    {
        if (array_key_exists($this->init_type, self::initTypeList())) {
            return self::initTypeList()[$this->init_type];
        }
        return null;
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
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateSecretKey();
            }
            return true;
        }
        return false;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert === true || array_key_exists('status', $changedAttributes) && ((int) $changedAttributes['status'] !== (int) $this->status)) {
            $this->createStatusHistory();
        }
    }

    /**
     * @return void
     */
    private function generateSecretKey()
    {
        $this->secret_key = Yii::$app->getSecurity()->generateRandomString(32);
    }

    /**
     * @return void
     */
    private function createStatusHistory()
    {
        $statusHistory = new LoanStatusHistory();
        $statusHistory->status = $this->status;
        $statusHistory->link('loan', $this);
        $statusHistory->save(false);
    }

}
