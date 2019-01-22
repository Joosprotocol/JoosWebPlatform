<?php

namespace common\models\user;

use itmaster\core\behaviors\TimestampBehavior;
use itmaster\entity\Unique;
use itmaster\storage\behaviors\StorageUploadBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_personal}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $facebook_url
 * @property string $social_url
 * @property string $mobile_number
 * @property string $facebook_friend_first_url
 * @property string $facebook_friend_second_url
 * @property string $facebook_friend_third_url
 * @property integer active
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $created
 * @property string $updated
 * @property User $user
 * @property string issuedIdUrl
 */
class UserPersonal extends ActiveRecord implements Unique
{
    const ACTIVE_NO = 0;
    const ACTIVE_YES = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_personal}}';
    }

    /**
     * Method for defining behaviors
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => StorageUploadBehavior::class,
                'rules' => [
                    [['issuedId'], 'extensions' => 'png, jpg, jpeg, gif', 'skipOnEmpty' => true, 'maxSize' => 2048 * 2048],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'facebook_url', 'social_url', 'mobile_number', 'facebook_friend_first_url', 'facebook_friend_second_url', 'facebook_friend_third_url'], 'required'],
            [['user_id', 'active'], 'integer'],
            [['facebook_url', 'social_url', 'facebook_friend_first_url', 'facebook_friend_second_url', 'facebook_friend_third_url'], 'string', 'max' => 255],
            [['mobile_number'], 'string', 'max' => 15],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * Method for defining scenarios for transactions
     * @return array
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }


    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return new static($row);
    }

    /**
     * @return string
     */
    public static function getUPrefixStatic(): string
    {
        return 'userPersonal_';
    }

    /**
     * @return string
     */
    public function getUPrefix(): string
    {
        return static::getUPrefixStatic();
    }

    /**
     * @inheritdoc
     */
    public function getUId(): string
    {
        return $this->getUPrefix() . $this->id;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User Url'),
            'facebook_url' => Yii::t('app', 'Facebook Url'),
            'social_url' => Yii::t('app', 'Social Url'),
            'mobile_number' => Yii::t('app', 'Mobile Number'),
            'facebook_friend_first_url' => Yii::t('app', 'Facebook Friend First Url'),
            'facebook_friend_second_url' => Yii::t('app', 'Facebook Friend Second Url'),
            'facebook_friend_third_url' => Yii::t('app', 'Facebook Friend Third Url'),
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
