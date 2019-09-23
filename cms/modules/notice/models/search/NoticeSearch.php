<?php

namespace cms\modules\notice\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\notice\models\SystemNotice;

/**
 * NoticeSearch represents the model behind the search form of `app\modules\notice\models\SystemNotice`.
 */
class NoticeSearch extends SystemNotice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'top'], 'integer'],
            [['title', 'image_url', 'content', 'create_at', 'create_user','status'], 'safe'],
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
        $query = SystemNotice::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
//            'id' => $this->id,
//            'top' => $this->top,
//            'create_at' => $this->create_at,
              'status' => $this->status
        ]);
        //按时间远近排序
        if($this->create_at){
            if($this->create_at == 1){
                $query->orderBy('create_at desc');
            }else{
                $query->orderBy('create_at asc');
            }
        }
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'create_user', $this->create_user]);
        $query->orderBy('id desc');
        return $dataProvider;
    }
}
