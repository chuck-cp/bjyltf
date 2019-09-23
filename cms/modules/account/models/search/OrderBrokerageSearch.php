<?php

namespace cms\modules\account\models\search;

use cms\modules\ledmanage\ledmanage;
use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\account\models\OrderBrokerage;

/**
 * OrderBrokerageSearch represents the model behind the search form of `app\modules\account\models\OrderBrokerage`.
 */
class OrderBrokerageSearch extends OrderBrokerage
{
    public $part_time_order;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'member_price',  'cooperate_money', 'total', 'real_income'], 'integer'],//'first', 'second', 'third', 'fourth', 'fifth', 'sixth',
            [['member_name', 'member_mobile', 'cooperate_member_id','man_name','man_mobile','order_code','member_id','part_time_order'], 'safe'],
            [['create_at','create_at_end','order_price'], 'string'],
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
    public function search($params,$export=0)
    {
        $query = OrderBrokerage::find()->joinWith('orderInfo');

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
        /***提交时间***/
        if(isset($this->create_at) && $this->create_at ){
            $query->andWhere(['>','yl_order.create_at',$this->create_at]);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<','yl_order.create_at',$this->create_at_end]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'member_price' => $this->member_price,
            'cooperate_money' => $this->cooperate_money,
            'total' => $this->total,
            'real_income' => $this->real_income,
            'yl_order_brokerage.member_id' => $this->member_id,
            'yl_order.order_price' => $this->order_price,
            'yl_order.order_code' => $this->order_code,
            'yl_order.part_time_order' => $this->part_time_order,
        ]);

        $query->andFilterWhere(['like', 'yl_order.member_name', $this->member_name])
            ->andFilterWhere(['like', 'yl_order_brokerage.member_name', $this->man_name])
            ->andFilterWhere(['like', 'yl_order_brokerage.member_mobile', $this->man_mobile])
            ->andFilterWhere(['like', 'member_mobile', $this->member_mobile])
            ->andFilterWhere(['like', 'cooperate_member_id', $this->cooperate_member_id]);
        $query->orderBy('id desc');
        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }
}
