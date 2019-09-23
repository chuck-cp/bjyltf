<?php

namespace cms\modules\config\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\config\models\SystemZonePrice;

/**
 * SystemZonePriceSearch represents the model behind the search form of `cms\modules\config\models\SystemZonePrice`.
 */
class SystemZonePriceSearch extends SystemZonePrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'price_id', 'price', 'subsidy_id', 'subsidy_price'], 'integer'],
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
        $query = SystemZonePrice::find();

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
            'price_id' => $this->price_id,
            'price' => $this->price,
            'subsidy_id' => $this->subsidy_id,
            'subsidy_price' => $this->subsidy_price,
        ]);

        return $dataProvider;
    }
}
