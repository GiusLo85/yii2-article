<?php

namespace mickey\article\controllers\backend;

use yii\web\Controller;
use Yii;
use mickey\article\models\Article;
use mickey\article\models\search\ArticleSearch;
//use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Default controller for the `article` module
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
//        if (!\Yii::$app->user->can('isAuthor', ['article' => $model])) {
//            throw new ForbiddenHttpException('Access denied');
//        }
//        if (!\Yii::$app->user->can('updateOwnProfile', ['profileId' => \Yii::$app->user->id])) {
//            throw new ForbiddenHttpException('Access denied');
//        }

        $model = $this->findModel($id);
//        dump(\Yii::$app->user->can('isAuthor', ['article' => $model]));
//        $group = \Yii::$app->user->identity->getRoleName();
//        dump($group);
        if (!\Yii::$app->user->can('AuthorRule', ['post' => $model])) {
            throw new ForbiddenHttpException('Access denied');
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }            // update post
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
