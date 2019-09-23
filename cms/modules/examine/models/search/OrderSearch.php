<?php

namespace cms\modules\examine\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\examine\models\Order;

/**
 * OrderSearch represents the model behind the search form of `cms\modules\examine\models\Order`.
 */
class OrderSearch extends Order
{
    public $order_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'order_price', 'unit_price', 'total_day', 'payment_type', 'payment_price', 'overdue_number', 'screen_number', 'rate', 'advert_id', 'payment_status', 'examine_status'], 'integer'],
            [['member_name', 'salesman_name', 'salesman_mobile', 'custom_service_name', 'custom_service_mobile', 'order_code', 'payment_at', 'area_name', 'advert_name', 'advert_time', 'create_at','start_at','end_at'], 'safe'],
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
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        /*$query->joinWith(['orderdate']);
        if($this->start_at){
            $query->andWhere(['>','start_at',$this->start_at]);
        }
        if($this->end_at){
            $query->andWhere(['<','end_at',$this->end_at]);
        }*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            'order_price' => $this->order_price,
            'unit_price' => $this->unit_price,
            'total_day' => $this->total_day,
            'payment_type' => $this->payment_type,
            'payment_price' => $this->payment_price,
            'payment_at' => $this->payment_at,
            'overdue_number' => $this->overdue_number,
            'screen_number' => $this->screen_number,
            'rate' => $this->rate,
            'advert_id' => $this->advert_id,
            'create_at' => $this->create_at,
            'payment_status' => $this->payment_status,
            'examine_status' => $this->examine_status,
        ]);

        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'salesman_name', $this->salesman_name])
            ->andFilterWhere(['like', 'salesman_mobile', $this->salesman_mobile])
            ->andFilterWhere(['like', 'custom_service_name', $this->custom_service_name])
            ->andFilterWhere(['like', 'custom_service_mobile', $this->custom_service_mobile])
            ->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'area_name', $this->area_name])
            ->andFilterWhere(['like', 'advert_name', $this->advert_name])
            ->andFilterWhere(['like', 'advert_time', $this->advert_time]);

        return $dataProvider;
    }
}
