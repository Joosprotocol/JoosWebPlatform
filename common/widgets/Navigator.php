<?php


namespace common\widgets;

use common\menu\MenuItem;
use common\menu\Menu;
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
class Navigator extends CoreNavigator
{
    const MAIN_MENU_CODE = 'main';

    /**
     * Initializes the widget.
     */
    public function run()
    {
        $menu = Menu::findOne(['code' => $this->menuCode]);

        if (empty($menu)) {
            return null;
        }

        $items = [];
        if ($this->menuCode === self::MAIN_MENU_CODE) {
            $items = $this->getMainItems();
        }

        $items = array_merge($items, $menu->rootItems);

        $this->items = $this->getItems($items);

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
            $menuItems[] = [
                'label' => $item->linkName,
                'url' => $item->linkUrl,
                'items' => !empty($item->items) ? $this->getItems($item->items) : null,
                'visible' => $item->visible && $this->isVisibleForRole($item),
            ];
        }

        return $menuItems;
    }

    /**
     * @return array
     */
    private function getMainItems()
    {
        $menuArray = Yii::$app->params['frontendMenuItems'];

        $menuItems = [];
        foreach ($menuArray as $key => $menu) {
            $menuItems[$key] = new MenuItem();
            $menuItems[$key]->setAttributes($menu);
            $menuItems[$key]->roles = !empty($menu['roles']) ? $menu['roles'] : [];
        }
        return $menuItems;
    }

    /**
     * @param MenuItem $item
     * @return bool
     */
    private function isVisibleForRole($item)
    {
        if (!isset($item->roles)) {
            return true;
        }

        if (empty($item->roles)) {
            return true;
        }
        if (!Yii::$app->user->isGuest && in_array(Yii::$app->user->identity->roleName, $item->roles)) {
            return true;
        }
        return false;
    }

}
