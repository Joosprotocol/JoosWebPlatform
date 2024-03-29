<?php


namespace common\widgets;

use common\menu\MenuItem;
use Yii;
use yii\bootstrap\Nav as WidgetNav;
use yii\widgets\Menu as WidgetMenu;
use itmaster\core\widgets\Navigator as CoreNavigator;

/**
 * Class Navigator
 *
 * It's only temporary patch for menu. It provides roles dependent visibility.
 * @package common\widgets
 */
abstract class Navigator extends CoreNavigator
{

    /**
     * Initializes the widget.
     */
    public function run()
    {
        $this->items = $this->getItems($this->getItemParams());

        $options = [
            'items' => $this->items,
            'options' => $this->options,
        ];

        if ($this->isDropdown == true) {
            return WidgetNav::widget($options);
        } else {
            return WidgetMenu::widget($options);
        }
    }

    /**
     * @param $items
     * @return array
     */
    public function getItems($items)
    {
        $menuItems = [];
        foreach ($items as $item) {
            $isVisible = $this->isVisible($item);

            $menuItems[] = [
                'label' => $item['label'],
                'url' => $item['url'],
                'items' => !empty($item['items']) ? $this->getItems($item['items']) : null,
                'visible' => $isVisible,
            ];

        }

        return $menuItems;
    }

    /**
     * @param MenuItem $item
     * @return bool
     */
    protected function isVisible($item)
    {
        if ((!Yii::$app->user->isGuest && $item['visible'] === '@')
            || Yii::$app->user->isGuest && $item['visible'] === '?') {
            return true;
        }

        if (!Yii::$app->user->isGuest
            && is_array($item['visible'])
            && in_array(Yii::$app->user->identity->roleName, $item['visible'])) {
            return true;
        }
        return false;
    }

    abstract public function getItemParams();

}
