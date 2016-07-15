<?php

namespace mickey\article\models\query;
use common\base\LibraryRecordQuery;

/**
 * This is the ActiveQuery class for [[News]].
 *
 * @see News
 */
class ArticleQuery extends LibraryRecordQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function init()
    {
        $modelClass = $this->modelClass;
        $tableName = $modelClass::tableName();
        $this->andFilterWhere([$tableName.'.created_by' => \Yii::$app->user->identity->id]);
        parent::init();
    }

    public function last($limit = NULL)
    {
//        return  $this->orderBy('publish_date DESC');;
    }
    /**
     * @inheritdoc
     * @return News[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return News|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
