<?php

namespace common\models\payment;

use common\models\loan\Loan;
use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payment}}".
 *
 * @property integer $id
 * @property integer $loan_id
 * @property string $amount
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Loan $loan
 */
class Payment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
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
            [['loan_id'], 'required'],
            [['loan_id', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['loan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Loan::class, 'targetAttribute' => ['loan_id' => 'id']],
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
            'amount' => Yii::t('app', 'Amount'),
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
}
