<?php

namespace common\models\loan;

use common\models\user\User;
use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%loan_referral}}".
 *
 * @property integer $id
 * @property integer $loan_id
 * @property integer $digital_collector_id
 * @property string $slug
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property LoanFollowing[] $loanFollowings
 * @property User $digitalCollector
 * @property Loan $loan
 */
class LoanReferral extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%loan_referral}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['loan_id', 'digital_collector_id'], 'required'],
            [['loan_id', 'digital_collector_id', 'created_at', 'updated_at'], 'integer'],
            [['slug'], 'string', 'max' => 10],
            [['slug'], 'unique'],
            [['digital_collector_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['digital_collector_id' => 'id']],
            [['loan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Loan::class, 'targetAttribute' => ['loan_id' => 'id']],
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
            'loan_id' => 'Loan ID',
            'digital_collector_id' => 'Digital Collector ID',
            'slug' => 'Slug',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateSlug();
            }
            return true;
        }
        return false;
    }

    /**
     * @param $loanId
     * @param $collectorId
     * @return array|null|ActiveRecord
     */
    public static function findByLoanIdAndCollectorId($loanId, $collectorId)
    {
        return self::find()
            ->where(['loan_id' => $loanId])
            ->andWhere(['digital_collector_id' => $collectorId])
            ->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoanFollowings()
    {
        return $this->hasMany(LoanFollowing::class, ['loan_referral_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDigitalCollector()
    {
        return $this->hasOne(User::class, ['id' => 'digital_collector_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoan()
    {
        return $this->hasOne(Loan::class, ['id' => 'loan_id']);
    }

    /**
     * @return void
     */
    private function generateSlug()
    {
        $this->slug = Yii::$app->getSecurity()->generateRandomString(10);
    }

    /**
     * @param string $slug
     * @return array|null|self
     */
    public static function findBySlug($slug)
    {
        return self::find()
            ->where(['slug' => $slug])
            ->one();
    }
}
