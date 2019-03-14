<?php


namespace frontend\widgets;

use \yii\bootstrap\Alert as AlertBase;
use yii\bootstrap\Html;

class Alert extends AlertBase
{

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        Html::addCssClass($this->options, ['alert', 'fade', 'in', 'flash-alert']);

        if ($this->closeButton !== false) {
            $this->closeButton = array_merge([
                'data-dismiss' => 'alert',
                'aria-hidden' => 'true',
                'class' => 'close',
            ], $this->closeButton);
        }
    }
}
