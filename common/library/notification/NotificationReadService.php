<?php


namespace common\library\notification;


use common\models\notification\Notification;
use common\models\user\User;

/**
 * Class NotificationReadService
 * @package common\library\notification
 */
class NotificationReadService
{

    /**
     * @param User $user
     * @param array $ids
     * @return bool
     */
    public static function setAsRead(User $user, array $ids) : bool
    {
        return Notification::updateAll(
            ['status' => Notification::STATUS_READ],
            [
                'status' => Notification::STATUS_UNREAD,
                'id' => $ids,
                'user_id' => $user->id,
            ]
        );
    }

}
