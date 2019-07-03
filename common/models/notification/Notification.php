<?php

namespace common\models\notification;

use common\models\user\User;
use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $text
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Notification extends ActiveRecord
{
    const STATUS_UNREAD = 0;
    const STATUS_READ = 1;

    const TYPE_INFO = 0;
    const TYPE_SUCCESS = 1;
    const TYPE_ERROR = 2;

    const MESSAGE_NEW_LOAN_CREATED = 1;
    const MESSAGE_LOAN_SIGNED = 2;
    const MESSAGE_DIGITAL_COLLECTOR_JOINED = 3;
    const MESSAGE_BORROWER_FOLLOWED_LINK = 4;
    const MESSAGE_LOAN_STATUS_CHANGED = 5;
    const MESSAGE_NEW_COLLATERAL_CREATED = 6;
    const MESSAGE_NEW_COLLATERAL_PAID = 7;
    const MESSAGE_COLLATERAL_LOAN_PAYMENT_ADDRESS_ERROR = 8;
    const MESSAGE_NEW_COLLATERAL_LOAN_PAYMENT_PLATFORM = 9;
    const MESSAGE_COLLATERAL_LOAN_NEW_PAYMENT = 10;
    const MESSAGE_COLLATERAL_LOAN_SIGNED = 11;
    const MESSAGE_COLLATERAL_LOAN_STATUS_CHANGED = 12;
    const MESSAGE_COLLATERAL_LOAN_WITHDRAWN = 13;
    const MESSAGE_LOAN_NEW_PAYMENT = 14;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * @param $userId
     * @param string $text
     * @return bool
     */
    public static function create($userId, $text)
    {
        $notification = new self;
        $notification->text = $text;
        $notification->user_id = $userId;
        return $notification->save();
    }

    public static function getMessages()
    {
        return [
            self::MESSAGE_NEW_LOAN_CREATED => Yii::t('app', 'New loan was created.'),
            self::MESSAGE_LOAN_SIGNED => Yii::t('app', 'Loan was signed.'),
            self::MESSAGE_COLLATERAL_LOAN_SIGNED => Yii::t('app', 'Collateral Loan was signed.'),
            self::MESSAGE_DIGITAL_COLLECTOR_JOINED => Yii::t('app', 'New digital collector joined.'),
            self::MESSAGE_BORROWER_FOLLOWED_LINK => Yii::t('app', 'Borrower followed referral link.'),
            self::MESSAGE_LOAN_STATUS_CHANGED => Yii::t('app', 'Loan status changed.'),
            self::MESSAGE_COLLATERAL_LOAN_STATUS_CHANGED => Yii::t('app', 'Collateral Loan status changed.'),
            self::MESSAGE_NEW_COLLATERAL_CREATED => Yii::t('app', 'New collateral was created.'),
            self::MESSAGE_NEW_COLLATERAL_PAID => Yii::t('app', 'New collateral paid.'),
            self::MESSAGE_COLLATERAL_LOAN_NEW_PAYMENT => Yii::t('app', 'Payment for Collateral Loan is successfully received.'),
            self::MESSAGE_LOAN_NEW_PAYMENT => Yii::t('app', 'Payment for Loan is successfully received.'),
            self::MESSAGE_COLLATERAL_LOAN_PAYMENT_ADDRESS_ERROR => Yii::t('app', 'Payment by collateral was reverted. Please set you Ethereum payment address on "profile page".'),
            self::MESSAGE_NEW_COLLATERAL_LOAN_PAYMENT_PLATFORM => Yii::t('app', 'Collateral is successfully loaned by "Joos" platform.'),
            self::MESSAGE_COLLATERAL_LOAN_WITHDRAWN => Yii::t('app', 'Collateral funds successfully withdrawn.'),
        ];
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
            [['user_id', 'text'], 'required'],
            [['user_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string', 'max' => 255],
            [['type'], 'in', 'range' => array_keys(self::typeList())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function typeList()
    {
        return [
            self::TYPE_INFO => Yii::t('app', 'Info'),
            self::TYPE_SUCCESS => Yii::t('app', 'Success'),
            self::TYPE_ERROR => Yii::t('app', 'Error'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'text' => Yii::t('app', 'Text'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
