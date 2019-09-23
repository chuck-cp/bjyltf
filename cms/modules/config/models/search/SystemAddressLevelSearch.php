<?php

namespace cms\modules\config\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\config\models\SystemAddressLevel;

/**
 * SystemAddressLevelSearch represents the model behind the search form of `cms\modules\config\models\SystemAddressLevel`.
 */
class SystemAddressLevelSearch extends SystemAddressLevel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'level', 'type'], 'integer'],
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
        $query = SystemAddressLevel::find();

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
            'area_id' => $this->area_id,
            'level' => $this->level,
            'type' => $this->type,
        ]);

        return $dataProvider;
    }
}
