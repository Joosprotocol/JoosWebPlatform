<?php

namespace common\models\notification;

use common\models\user\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Notification extends ActiveRecord
{
    const TYPE_INFO = 0;
    const TYPE_SUCCESS = 1;
    const TYPE_ERROR = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'text'], 'required'],
            [['user_id', 'type', 'created_at', 'updated_at'], 'integer'],
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
