<?php

namespace cms\modules\config\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\config\models\SystemTrain;

/**
 * SystemTrainSearch represents the model behind the search form of `cms\modules\config\models\SystemTrain`.
 */
class SystemTrainSearch extends SystemTrain
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'type', 'status', 'sort', 'create_user_id'], 'integer'],
            [['name', 'thumbnail', 'create_at', 'create_user_name'], 'safe'],
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
        $query = SystemTrain::find();

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
            'type' => $this->type,
            'status' => $this->status,
            'sort' => $this->sort,
            'create_at' => $this->create_at,
            'create_user_id' => $this->create_user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'create_user_name', $this->create_user_name]);
        $query->orderBy('sort asc');
        return $query;
    }
}
