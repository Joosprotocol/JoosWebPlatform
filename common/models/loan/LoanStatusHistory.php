<?php

namespace common\models\loan;

use itmaster\core\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%loan_status_history}}".
 *
 * @property integer $id
 * @property integer $loan_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Loan $loan
 */
class LoanStatusHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%loan_status_history}}';
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
            [['loan_id', 'status'], 'required'],
            [['loan_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['loan_id'], 'exist', 'skipOnError' => true, 'tar+getClass' => Loan::class, 'targetAttribute' => ['loan_id' => 'id']],
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
            'status' => Yii::t('app', 'Status'),
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
