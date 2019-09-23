<?php

namespace cms\modules\schedules\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\schedules\models\OrderThrowProgram;

/**
 * OrderThrowProgramSearch represents the model behind the search form of `cms\modules\schedules\models\OrderThrowProgram`.
 */
class OrderThrowProgramSearch extends OrderThrowProgram
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'area_id'], 'integer'],
            [['advert_key', 'date'], 'safe'],
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
        $query = OrderThrowProgram::find();

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
            'id' => $this->id,
            'area_id' => $this->area_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'advert_key', $this->advert_key]);

        return $dataProvider;
    }
}
