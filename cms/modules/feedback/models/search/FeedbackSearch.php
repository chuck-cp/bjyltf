<?php

namespace cms\modules\feedback\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\feedback\models\Feedback;

/**
 * FeedbackSearch represents the model behind the search form about `cms\modules\feedback\models\feedback`.
 */
class FeedbackSearch extends Feedback
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id'], 'integer'],
            [['question', 'content', 'create_at','name'], 'safe'],
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
        $query = feedback::find()->joinWith('member');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            //'create_at' => $this->create_at,
        ]);
        //按时间排序
        $order = '';
        if($this->create_at){
            if($this->create_at ==1){
                $order = 'create_at asc';
            }else{
                $order = 'create_at desc';
            }
        }
        if($order){
            $query->orderBy($order);
        }else{
            $query->orderBy('id desc');
        }
        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'yl_member.name', $this->name]);

        return $dataProvider;
    }
}
