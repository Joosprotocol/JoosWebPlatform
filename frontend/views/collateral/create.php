<?php

use common\models\collateral\Collateral;
use itmaster\core\models\Setting;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\Slider;

/* @var $this yii\web\View */
/* @var $model frontend\forms\collateral\CollateralCreateForm */
/* @var $currencyRateList array */

$this->title = Yii::t('app', 'New') . ' ' . Yii::t('app', 'Collateral');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collaterals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collateral-create">

    <div id="title-line">
        <div class="title-text"><?= Html::encode($this->title) ?></div>
        <div class="clearfix"></div>

    </div>

    <div id="collateral-create-data"
         data-required-amount-max = "<?= $model::REQUIRED_AMOUNT_MAX ?>"
         data-required-amount-min = "<?= $model::REQUIRED_AMOUNT_MIN ?>"
         data-currency-rates = '<?= json_encode($currencyRateList) ?>'
         data-lvr = <?= $model->getLvr() ?>>

    </div>

    <div class="row">
        <div class="col-lg-8">

            <?php $form = ActiveForm::begin(); ?>

            <div class="panel panel-black">

                <div class="panel-heading">
                    <div class="panel-heading-icon icon-plus"></div>
                    <span class="panel-heading-text">
                        <?= Yii::t('app', 'New') . ' ' . Yii::t('app', 'Collateral') ?>
                    </span>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'currency_type')->dropdownList(Collateral::currencyPostingTypeList()) ?>
                        </div>
                        <div class="col-md-12">
                            <?=  Slider::widget([
                                'clientOptions' => [
                                    'min' => $model::REQUIRED_AMOUNT_MIN,
                                    'max' => $model::REQUIRED_AMOUNT_MAX,
                                    'step' => $model::REQUIRED_AMOUNT_STEP,
                                    'value' => $model::REQUIRED_AMOUNT_MIN,
                                ],
                                'options' => [
                                    'id' => 'collateral-required-amount-range',
                                ],

                            ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'amountRequired')->textInput(['maxlength' => true, 'value' => $model::REQUIRED_AMOUNT_MIN]) ?>
                        </div>
                    </div>

                    <div class="pull-right buttons-line">
                        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
                    </div>

                </div>

            </div>

            <?php ActiveForm::end(); ?>

            <div class="clearfix"></div>

        </div>
    </div>

</div>
