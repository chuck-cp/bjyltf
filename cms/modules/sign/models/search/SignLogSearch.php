<?php

namespace cms\modules\sign\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignLog;

/**
 * SignLogSearch represents the model behind the search form of `cms\modules\sign\models\SignLog`.
 */
class SignLogSearch extends SignLog
{
    public $create_at_end;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'team_id', 'team_type', 'member_type'], 'integer'],
            [['member_name', 'team_name', 'content', 'create_at','create_at_end'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = SignLog::find();

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

        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','create_at',$this->create_at_end.' 23:59:59']);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'team_type' => $this->team_type,
            'member_type' => $this->member_type,
        ]);

        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'team_name', $this->team_name]);
        $query->orderBy('id desc');
        return $dataProvider;
    }
}
