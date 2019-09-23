<?php

namespace cms\modules\account\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\models\ScreenRunTimeByMonth;

/**
 * ScreenRunTimeShopSubsidySearch represents the model behind the search form of `cms\models\ScreenRunTimeShopSubsidy`.
 */
class ScreenRunTimeByMonthSearch extends ScreenRunTimeByMonth
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id','date'], 'safe'],
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
        $query = ScreenRunTimeByMonth::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'shop_id' => $this->shop_id,
            'is_show' => 1,
            'date' => $this->date,
        ]);
        return $dataProvider;
    }
}
