<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \common\modulse\article\models\Article */

$this->title = Yii::t('Article', 'Create Article');
$this->params['breadcrumbs'][] = ['label' => Yii::t('Article', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
