<?php


namespace frontend\library;


use Yii;
use yii\helpers\Html;

/* @var $dataProvider yii\data\ActiveDataProvider */

class GridHelper
{
    /**
     * @param Yii\data\ActiveDataProvider $dataProvider
     * @return string
     */
    public static function getPerPageDropdown(yii\data\ActiveDataProvider $dataProvider)
    {
        $pageSize = $dataProvider->pagination->pageSize;
        $paginationOptions = Yii::$app->params['paginationOptions'];
        $paginationDropDownElement = Html::dropDownList(
            'pageSize',
            $pageSize,
            $paginationOptions,
            [
                'id' => 'page-size',
                'class' => 'form-control'
            ]
        );
        return Html::tag(
            'form',
            $paginationDropDownElement,
            ['class' => 'form-inline pull-left pagination-dropdown']
        );
    }

}
