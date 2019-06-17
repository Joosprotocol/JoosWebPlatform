<?php
use itmaster\core\widgets\Gallery;
use yii\helpers\Html;
use kartik\file\FileInput;

/** @var string $attributeName */

?>

<?= Gallery::widget() ?>

<?= $form->field($model, $attributeName)->label(false)->widget(FileInput::classname(), [
    'pluginOptions' => [
        'initialPreview' => [
            !empty($model->{$attributeName  . 'Url'})
                ? '<a href="' . $model->{$attributeName  . 'Url'} . '" title="' . $attributeName . '" data-gallery>'
                . Html::img($model->{$attributeName  . 'Url'}, ['class' => 'img-preview', 'title' => $attributeName, 'alt' => $attributeName])
                . '<br />'
                . '</a>'
                : null
        ],
        'overwriteInitial' => true,
        'pluginLoading' => true,
        'showCaption' => false,
        'showUpload' => false,
        'showClose' => false,
        'removeClass' => 'additional-btn remove',
        'browseClass' => 'additional-btn browse',
        'browseLabel' => '',
        'removeLabel' => '',
        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
        'previewTemplates' => [
            'generic' => '<div class="file-preview-frame ' . $attributeName . '-image" id="{previewId}" data-fileindex="{fileindex}">
                    <div class="' . $attributeName . '-image-inner">{content}</div>
                </div>',
            'image' => '<div class="file-preview-frame ' . $attributeName . '-image" id="{previewId}" data-fileindex="{fileindex}">
                    <div class="' . $attributeName . '-image-inner"><img src="{data}" class="img-preview" title="{caption}" alt="{caption}"></div>
                </div>',
        ]
    ],
]) ?>

