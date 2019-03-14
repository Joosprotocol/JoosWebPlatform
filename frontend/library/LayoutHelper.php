<?php


namespace frontend\library;


use common\models\notification\Notification;
use Yii;

class LayoutHelper
{
    /**
     * @return string
     */
    public static function getUser()
    {
        return Yii::$app->user->identity;
    }

    /**
     * @return string
     */
    public static function isGuest()
    {
        return Yii::$app->user->isGuest;
    }

    /**
     * @return \yii\base\View|\yii\web\View
     */
    public static function getView()
    {
        return Yii::$app->view;
    }

    /**
     * @return int
     */
    public static function getNotificationsQuantity()
    {
        return Notification::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->count();
    }
}
