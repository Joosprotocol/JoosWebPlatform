<?php


namespace common\widgets;

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
class NavigatorProfile extends BaseNavigator
{
    public function getItemParams()
    {
        return Yii::$app->params['frontendMenuProfile'];
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
        if (array_key_exists('type', $item) && $item['type'] === 'avatar') {
            $image = HTML::tag('div', '', [
                'class' => 'profile-circle-img',
                'style' => 'background-image: url("' . Yii::$app->user->identity->avatarUrl . '")']);
            $label =  HTML::tag('div', $image, ['class' => 'profile-circle']);
        } else {
            $label = $item['label'];
        }


        return $label;
    }

}
