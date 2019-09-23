<?php

namespace cms\modules\config\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\models\AdvertPrice;

/**
 * AdvertPriceSearch represents the model behind the search form of `cms\models\AdvertPrice`.
 */
class AdvertPriceSearch extends AdvertPrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'advert_id','type', 'price_1','price_2','price_3', 'create_user_id'], 'integer'],
            [['time', 'update_at', 'create_user_name'], 'safe'],
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
        $query = AdvertPrice::find();

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
            'advert_id' => $this->advert_id,
            'price_1' => $this->price_1,
            'update_at' => $this->update_at,
            'create_user_id' => $this->create_user_id,
        ]);

        $query->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'create_user_name', $this->create_user_name]);

        return $dataProvider;
    }
}
