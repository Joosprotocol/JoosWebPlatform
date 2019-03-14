<?php

namespace frontend\widgets;

use kartik\grid\GridView as BaseGridView;
use yii\base\Model;
use yii\grid\Column;
use yii\helpers\Html;

class GridView extends BaseGridView
{
    /**
     * Renders the filter.
     * @return string the rendering result.
     */
    public function renderFilters()
    {
        if ($this->filterModel !== null) {
            $cells = [];

            foreach ($this->columns as $column) {
                /* @var $column Column */

                $isActive = $this->filterModel instanceof Model && $column->attribute !== null && $this->filterModel->isAttributeActive($column->attribute);
                $icon = '';
                if ($isActive) {
                    $icon .= '  <span class="glyphicon glyphicon-search"></span>';
                }

                $filterContent = Html::tag('td', $column->renderFilterCellContent() . $icon , $this->filterOptions);

                $cells[] = $filterContent;
            }

            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        }

        return '';
    }
}
