<?php

namespace common\models\loan;

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
 * @property integer $type
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
            [['lender_id', 'borrower_id', 'status', 'period', 'type', 'created_at', 'updated_at'], 'integer'],
            [['status', 'type'], 'required'],
            [['amount'], 'number'],
            [['secret_key'], 'string', 'max' => 255],
            [['ref_slug'], 'string', 'max' => 10],
            [['borrower_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['borrower_id' => 'id']],
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
            'borrower_id' => Yii::t('app', 'Borrower ID'),
            'status' => Yii::t('app', 'Status'),
            'amount' => Yii::t('app', 'Amount'),
            'period' => Yii::t('app', 'Period'),
            'type' => Yii::t('app', 'Type'),
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
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateSecretKey();
                $this->generateRefSlug();
            }
            return true;
        }
        return false;
    }

    private function generateSecretKey()
    {
        $this->secret_key = Yii::$app->getSecurity()->generateRandomString(32);
    }

    private function generateRefSlug()
    {
        $this->ref_slug = Yii::$app->getSecurity()->generateRandomString(10);
    }
}
