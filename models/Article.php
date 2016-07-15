<?php

namespace mickey\article\models;

use common\base\LibraryRecord;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use mickey\article\models\query\ArticleQuery;
use common\behaviors\ImageBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $updated_at
 */
class Article extends LibraryRecord
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                // 'slugAttribute' => 'slug',
                'immutable' => true, //keep the slug the same after it's first created—even if the message is updated
                'ensureUnique'=>true, //automatically append a unique suffix to duplicates
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
            ],
            'image' => [
                'class' => ImageBehavior::className(),
                'fileAttribute' => 'image',
                'sizes' => [
                    'thumb' => [75, 75, 'resize' => 'outbound', 'required' => '@web/img/75x75-required.jpg'],
                    'default' => [1024, 768, 'required' => '@web/img/1024x768-required.jpg'],
                ],
            ],
        ];
    }

    public function init()
    {
        $this->status = self::STATUS_ENABLED;
    }


    public static function tableName()
    {
        return '{{%article}}';
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['title'], 'filter', 'filter'=>'strip_tags'],
            [['title'], 'filter', 'filter'=>'trim'],
            [['title', 'content'], 'required'],
            [['status'], 'integer'],
            [['content'], 'string'],
            [['image'], 'file', 'extensions'=>'jpg, jpeg, gif, png']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('Article', 'ID'),
            'title' => Yii::t('Article', 'Title'),
            'content' => Yii::t('Article', 'Content'),
            'status' => Yii::t('Article', 'Status'),
            'created_at' => Yii::t('Article', 'Created At'),
            'created_by' => Yii::t('Article', 'Created By'),
            'updated_by' => Yii::t('Article', 'Updated By'),
            'updated_at' => Yii::t('Article', 'Updated At'),
            'image' => Yii::t('Article', 'Image'),
        ];
    }

    public static function find()
    {
        return new ArticleQuery(get_called_class());
    }

    public function getUri($params=[])
    {
        switch(Yii::$app->id) {
            case 'app-backend':
                $urlManager = Yii::$app->get('frontendUrlManager');
                break;
            default:
                $urlManager = Yii::$app->urlManager;
                break;
        }
//        $urlManager = Yii::$app->urlManager; //разобраться с frontendUrlManager
        $route=strtolower(Yii::$app->controller->id.'/'.$this->slug);
//        $params = ArrayHelper::merge($params, [$this->owner->slug]);
        return $urlManager->createAbsoluteUrl([$route] + $params);

        return \yii\helpers\Url::to('article/'.$this->slug);
    }

    /**
     * Находит материалы,опубликованные в этом месяце
     * @возвращает массив материалов этого месяца
     */
    public function findMaterialPostedThisMonth()
    {
        if (!empty($_GET['time'])) {
            $month = date('n', $_GET['time']);
            $year = date('Y', $_GET['time']);
            if (!empty($_GET['pnc']) && $_GET['pnc'] == 'n') $month++;
            if (!empty($_GET['pnc']) && $_GET['pnc'] == 'p') $month--;
        } else {
            $month = date('n');
            $year = date('Y');
        }

        return $this->find()->where(['status' =>self::STATUS_ENABLED ])
            ->andWhere(['>','created_at',($firstDay = mktime(0,0,0,$month,1,$year))])
            ->andWhere(['<','created_at',(mktime(0,0,0,$month+1,1,$year))]);
    }

}
