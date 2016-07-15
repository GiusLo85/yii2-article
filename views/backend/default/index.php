<?php

use yii\helpers\Html;
use yii\grid\GridView;
use mickey\article\models\Article;
use yii\bootstrap\Nav;
use kartik\icons\Icon;
//use yii\web\UrlManager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel \common\modules\article\models\search\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('Article', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row">
        <div class="span8">
            <?=Nav::widget([
                'items'=>[
                    [
                        'label' => Icon::show('list') . 'Все стаьи',
                        'url' => ['/article/default/index'],
                        'active'=>empty($_GET),
                    ],
                    [
                        'label' => Icon::show('eye') . 'Опубликованные',
                        'url'=>['/article/default/index', 'ArticleSearch'=>['status'=>Article::STATUS_ENABLED],
                            'active'=>$model->status==Article::STATUS_ENABLED],
                    ],
                    [
                        'label' => Icon::show('eye-slash') . 'Черновики',
                        'url'=>['/article/default/index', 'ArticleSearch'=>['status'=>Article::STATUS_DISABLED],
                            'active'=>$model->status==Article::STATUS_DISABLED],
                    ],
                ],
                'encodeLabels' => false,
                'options' => ['class' =>'nav-pills'], // set this to nav-tab to get tab-styled navigation
            ]);?>
        </div>
        <div class="pull-right">
            <?= Html::a(Yii::t('Article', 'Create Article'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
//                'label' => 'Статус',
                'format' => 'raw',
                'value' => function($data){
                    return $data->statusIcon;
                },
                'options'=>array('style'=>'width: 16px')
            ],
//            'id',
            'title',
            [
                'label'=>'Адрес',
                'format' => 'raw',
                'value' => function($data){
//                    return Html::a($data->getUrl());
                    return Html::a(Html::encode($data->getUri()), $data->getUri());
//                    return Html::encode(/*Yii::$app->urlManager->hostInfo*/$data->url);
                }
//Html::encode(Yii::app()->request->getHostInfo().$data->url)',
            ],

            'content:ntext',
//            'status',
//            'created_at',
            // 'created_by',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
