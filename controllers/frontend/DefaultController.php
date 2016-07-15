<?php

namespace mickey\article\controllers\frontend;

use Yii;
use mickey\article\models\Article;
use mickey\article\models\search\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * ArticleController implements the CRUD actions for Article model.
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
     * Lists all Article models.
     * @return mixed
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

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSlug($slug)
    {
        $model = Article::find()->where(['slug'=>$slug])->one();
        if (!is_null($model)) {
            return $this->render('view', [
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Collect posts issued in specific month
     */

    public function actionPostedInMonth()
    {

        $month = date('n', $_GET['time']); // 1 through 12
        $year = date('Y', $_GET['time']); // 2011
        if (isset($_GET['pnc'] ) && $_GET['pnc'] == 'n') $month++;
        if (isset($_GET['pnc'] ) && $_GET['pnc'] == 'p') $month--;

        $query=Article::find()->where(['status' =>Article::STATUS_ENABLED ])
            ->andWhere(['>','created_at',($firstDay = mktime(0,0,0,$month,1,$year))])
            ->andWhere(['<','created_at',(mktime(0,0,0,$month+1,1,$year))])
            ->orderBy('updated_at DESC');

        $pages = new Pagination(['totalCount' => $query->count()]);

        $pages->pageSize = \Yii::$app->params['monthlyArchivesCount'];

        $materials = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

       return $this->render('month',array(
            'materials' => $materials,
            'pages' => $pages,
            'firstDay' => $firstDay,
        ));
    }
}
