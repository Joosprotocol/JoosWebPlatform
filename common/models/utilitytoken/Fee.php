<?php

namespace common\models\utilitytoken;

use common\models\loan\Loan;
use common\models\user\User;
use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%fee}}".
 *
 * @property integer $id
 * @property integer $loan_id
 * @property integer $user_id
 * @property integer $amount
 * @property integer $status
 * @property integer $currency_type
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Loan $loan
 * @property User $user
 */
class Fee extends ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_PAID = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['loan_id', 'user_id', 'status', 'currency_type', 'amount'], 'required'],
            [['loan_id', 'user_id', 'status', 'currency_type', 'amount', 'created_at', 'updated_at'], 'integer'],
            [['loan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Loan::class, 'targetAttribute' => ['loan_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'loan_id' => Yii::t('app', 'Loan ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'currency_type' => Yii::t('app', 'Currency Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoan()
    {
        return $this->hasOne(Loan::class, ['id' => 'loan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @param integer $userId
     * @param integer $loanId
     * @return array|Fee|null
     */
    public static function findByUserIdAndLoanId($userId, $loanId)
    {
        return self::find()
            ->where(['user_id' => $userId, 'loan_id' => $loanId])
            ->one();
    }
}
