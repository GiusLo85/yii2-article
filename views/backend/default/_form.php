<?php

use yii\helpers\Html;
use kartik\checkbox\CheckboxX;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model \common\modulse\article\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'pluginOptions' => [
            'allowedFileExtensions'=>['jpg','gif','png'],
            'language' => 'de',
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' =>  'Select Image',
            'initialPreview'=>[
                $model->getImageTag('thumb'),
//                Html::img("/images/moon.jpg", ['class'=>'file-preview-image', 'alt'=>'The Moon', 'title'=>'The Moon']),
            ]
        ],
        'options' => ['accept' => 'image/*'],
    ]);?>

    <?= $form->field($model, 'content')->widget('common\widgets\Redactor') ?>

    <?= $form->field($model, 'status')->widget(CheckboxX::classname(['initInputType'=>CheckboxX::INPUT_CHECKBOX]), [ 'pluginOptions'=>['threeState'=>false]]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('Article', 'Create') : Yii::t('Article', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
