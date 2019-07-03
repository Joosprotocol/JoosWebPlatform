<?php


namespace common\library\notification;


use common\models\notification\Notification;

/**
 * Class NotificationQueryLibrary
 * @package common\library\notification
 */
class NotificationQueryLibrary
{
    /**
     * @param int $userId
     * @return int of unread notifications for all or specified user
     */
    public static function getUnreadQuantity(int $userId = null) : int
    {
        return Notification::find()
            ->where(['status' => Notification::STATUS_UNREAD])
            ->andFilterWhere(['user_id' => $userId])
            ->count();
    }
}
