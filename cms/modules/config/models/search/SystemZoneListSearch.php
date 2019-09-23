<?php

namespace cms\modules\config\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\config\models\SystemZoneList;

/**
 * SystemZoneListSearch represents the model behind the search form of `cms\modules\config\models\SystemZoneList`.
 */
class SystemZoneListSearch extends SystemZoneList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price', 'month_price', 'create_user_id'], 'integer'],
            [['update_at'], 'safe'],
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
        $query = SystemZoneList::find();

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
            'price' => $this->price,
            'month_price' => $this->month_price,
            'create_user_id' => $this->create_user_id,
            'update_at' => $this->update_at,
        ]);

        return $dataProvider;
    }
}
