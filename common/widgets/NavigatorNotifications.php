<?php


namespace common\widgets;

use common\models\notification\Notification;
use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\Nav as WidgetNav;
use common\widgets\Navigator as BaseNavigator;

/**
 * Class Navigator
 *
 * It's only temporary patch for menu. It provides roles dependent visibility.
 * @package common\widgets
 */
class NavigatorNotifications extends BaseNavigator
{
    public function getItemParams()
    {
        return Yii::$app->params['frontendMenuNotifications'];
    }

    /**
     * Initializes the widget.
     */
    public function run()
    {
        $this->items = $this->getItems($this->getItemParams());

        $options = [
            'items' => $this->items,
            'options' => $this->options,
            'encodeLabels' => false
        ];


        return WidgetNav::widget($options);
    }

    /**
     * @param $items
     * @return array
     */
    public function getItems($items)
    {
        if (Yii::$app->user->isGuest) {
            return [];
        }

        $menuItems = [];
        foreach ($items as $item) {
            $menuItems[] = [
                'label' => $this->getLabel($item),
                'url' => $item['url'],
                'items' => !empty($item['items']) ? $this->getItems($item['items']) : null,
                'visible' => $this->isVisible($item),
            ];
        }

        return $menuItems;
    }

    /**
     * @param array $item
     * @return string
     */
    private function getLabel($item)
    {
        $label = HTML::tag('span', '', ['class' => $item['iconClass']]);

        $counter = Notification::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->count();

        if ($counter != 0) {
            $label .= HTML::tag('div', $counter, ['class' => 'notification-circle']);
        }

        return $label;
    }

}
