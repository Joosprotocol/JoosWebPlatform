<?php

namespace common\menu;

use itmaster\core\models\MenuItem as MenuItemCore;

/**
 * This is the model class for table "{{%menu_item}}".
 */
class MenuItem extends MenuItemCore
{
    /** @var array  */
    public $roles = [];
}
