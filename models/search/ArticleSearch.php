<?php

namespace mickey\article\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use mickey\article\models\Article;

/**
 * ArticleSearch represents the model behind the search form about `common\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['title', 'content'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Article::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        if(Yii::$app->user->can('admin')) {
            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'content', $this->content]);
        } elseif(Yii::$app->user->can('FIRM') && IS_BACKEND){
            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['=', 'created_by', Yii::$app->user->id]) //TO DO перенести функционал в отдельную модель
                ->andFilterWhere(['like', 'content', $this->content]);
        }

        return $dataProvider;
    }
}
