<?php


namespace common\widgets;

use Yii;
use common\widgets\Navigator as BaseNavigator;
use yii\bootstrap\Html;
use yii\bootstrap\Nav as WidgetNav;

/**
 * Class Navigator
 *
 * It's only temporary patch for menu. It provides roles dependent visibility.
 * @package common\widgets
 */
class NavigatorSidebar extends BaseNavigator
{
    public function getItemParams()
    {
        return Yii::$app->params['frontendMenuSidebarItems'];
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
        $menuItems = [];
        foreach ($items as $item) {
            $isVisible = $this->isVisible($item);

            $menuItems[] = [
                'label' => $this->getLabel($item),
                'url' => $item['url'],
                'items' => !empty($item['items']) ? $this->getItems($item['items']) : null,
                'visible' => $isVisible,
            ];

            if ($isVisible) {
                $menuItems[] = '<li class="divider"></li>';
            }
        }
        return $menuItems;
    }

    /**
     * @param array $item
     * @return string
     */
    private function getLabel($item)
    {
        $label = '';
        if (array_key_exists('iconClass', $item)) {
            $label .= HTML::tag('div', '', ['class' => 'sidebar-item-icon ' . $item['iconClass']]);
        }
        $label .= HTML::tag('div', $item['label'], ['class' => 'item-label']);

        return $label;
    }

}
