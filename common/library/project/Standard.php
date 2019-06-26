<?php

namespace common\library\project;


use Yii;

/**
 * Class Standard contains global constants for the basic values of the project
 */
class Standard
{
    const PERCENT_PRECISION = 100;

    const BOOL_YES = 1;
    const BOOL_NO = 0;

    /**
     * @return array
     */
    public static function booleanList()
    {
        return [
            self::BOOL_YES => Yii::t('app', 'Yes'),
            self::BOOL_NO => Yii::t('app', 'No'),
        ];

    }
}
