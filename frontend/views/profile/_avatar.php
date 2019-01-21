<?php
use itmaster\core\widgets\Gallery;
use yii\helpers\Html;
use kartik\file\FileInput;

?>

<?= Gallery::widget() ?>

<?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
    'pluginOptions' => [
        'initialPreview' => [
            !empty($model->avatarUrl)
                ? '<a href="' . $model->avatarUrl . '" title="avatar" data-gallery>'
                . Html::img($model->avatarUrl, ['class' => 'img-preview', 'title' => 'avatar', 'alt' => 'avatar'])
                . '<br />'
                . '</a>'
                : null
        ],
        'overwriteInitial' => true,
        'pluginLoading' => true,
        'showCaption' => false,
        'showUpload' => false,
        'showClose' => false,
        'removeClass' => 'btn btn-danger',
        'browseClass' => 'btn btn-success',
        'browseLabel' => '',
        'removeLabel' => '',
        'browseIcon' => '<i class="glyphicon glyphicon-picture"></i>',
        'previewTemplates' => [
            'generic' => '<div class="file-preview-frame" id="{previewId}" data-fileindex="{fileindex}">
                    {content}
                </div>',
            'image' => '<div class="file-preview-frame" id="{previewId}" data-fileindex="{fileindex}">
                    <img src="{data}" class="img-preview" title="{caption}" alt="{caption}">
                </div>',
        ]
    ],
]) ?>

