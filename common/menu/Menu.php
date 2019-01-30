<?php

namespace common\menu;

/**
 * Class Menu
 * @package itmaster\core\models\frontend
 */
class Menu extends \itmaster\core\models\Menu
{
    /**
     * @return MenuItem[]
     */
    public function getRootItems()
    {
        return MenuItem::find()
            ->where(['menu_id' => $this->id, 'parent_id' => null])
            ->orderBy('sorting')
            ->all();
    }
}
