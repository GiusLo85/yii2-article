<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\modulse\article\models\Article */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('Article', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="container">
    <div class="col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="text"><?= $model->content?></div>

<!--        --><?//= $model->author->username?>
<!--        --><?//=Yii::$app->formatter->asDatetime($model->updated_at);?>
<!--        --><?php //if ($model->image):?>
<!--            --><?//= $model->getImageTag('thumb')?>
<!--        --><?php //endif;?>
    </div>
</div>