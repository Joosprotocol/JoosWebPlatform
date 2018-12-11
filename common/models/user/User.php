<?php

namespace common\models\user;

use common\models\loan\Loan;
use common\models\notification\Notification;
use itmaster\core\behaviors\TimestampBehavior;
use itmaster\core\models\User as CoreUser;
use itmaster\storage\behaviors\MixedUploadBehavior;


/**
 * User model class for the table "{{%user}}".
 *
 * @property Loan[] $loans
 * @property Loan[] $notifications
 */

class User extends CoreUser
{

    /**
     * Method for defining behaviors
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => MixedUploadBehavior::class,
                'rules' => [
                    [['avatar'], 'skipOnEmpty' => true, 'maxSize' => 2048 * 2048],
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
        return $this->hasMany(User::class, ['user_id' => 'id']);
    }
}
