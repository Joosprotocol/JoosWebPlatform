<?php

namespace common\models\loan;

use common\models\user\User;
use itmaster\core\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%loan_following}}".
 *
 * @property integer $id
 * @property integer $borrower_id
 * @property integer $loan_referral_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $borrower
 * @property LoanReferral $loanReferral
 */
class LoanFollowing extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%loan_following}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['borrower_id', 'loan_referral_id'], 'required'],
            [['borrower_id', 'loan_referral_id', 'created_at', 'updated_at'], 'integer'],
            [['borrower_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['borrower_id' => 'id']],
            [['loan_referral_id'], 'exist', 'skipOnError' => true, 'targetClass' => LoanReferral::class, 'targetAttribute' => ['loan_referral_id' => 'id']],
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
            'id' => 'ID',
            'borrower_id' => 'Borrower ID',
            'loan_referral_id' => 'Loan Referral ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getLoanReferral()
    {
        return $this->hasOne(LoanReferral::class, ['id' => 'loan_referral_id']);
    }

    /**
     * @param integer $id
     * @return array|null|self
     */
    public static function findByReferral($id)
    {
        return self::find()
            ->where(['loan_referral_id' => $id])
            ->one();
    }
}
