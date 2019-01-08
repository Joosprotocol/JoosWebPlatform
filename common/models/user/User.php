<?php

namespace common\models\user;

use common\models\loan\Loan;
use common\models\notification\Notification;
use itmaster\core\behaviors\TimestampBehavior;
use itmaster\core\models\User as CoreUser;
use itmaster\storage\behaviors\StorageUploadBehavior;


/**
 * User model class for the table "{{%user}}".
 *
 * @property Loan[] $loans
 * @property Loan[] $notifications
 * @property UserPersonal $personal
 * @property string $avatarUrl
 * @property array $personalArray
 * @property string $fullName
 */

class User extends CoreUser
{
    const ROLE_LENDER = 'lender';
    const ROLE_BORROWER = 'borrower';
    const ROLE_DIGITAL_COLLECTOR = 'digital-collector';

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
                    [['avatar'], 'extensions' => 'png, jpg, jpeg, gif', 'skipOnEmpty' => true, 'maxSize' => 2048 * 2048],
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoans()
    {
        return $this->hasMany(Loan::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonal()
    {
        return $this->hasOne(UserPersonal::class, ['user_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getPersonalArray()
    {
        return [
            'avatarUrl' => $this->avatarUrl,
            'email' => $this->email,
            'issuedIdUrl' => $this->personal->issuedIdUrl,
            'facebook_url' => $this->personal->facebook_url,
            'social_url' => $this->personal->social_url,
            'mobile_number' => $this->personal->mobile_number,
            'facebook_friend_first_url' => $this->personal->facebook_friend_first_url,
            'facebook_friend_second_url' => $this->personal->facebook_friend_second_url,
            'facebook_friend_third_url' => $this->personal->facebook_friend_third_url,
        ];
    }
}
