<?php

namespace cms\modules\feedback\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\feedback\models\OrderComplain;


/**
 * OrderComplainSearch represents the model behind the search form of `cms\modules\feedback\models\OrderComplain`.
 */
class OrderComplainSearch extends OrderComplain
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'member_id', 'complain_type', 'complain_level'], 'integer'],
            [['member_id', 'complain_member_name', 'complain_content', 'create_at','order_code'], 'safe'],
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
    public function search($params,$complain_type)
    {
        $query = OrderComplain::find()->joinWith('orderInfo')->joinWith('member');

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
           // 'id' => $this->id,
           // 'order_id' => $this->order_id,
            'member_id' => $this->member_id,
            'complain_type' => $complain_type,
            'complain_level' => $this->complain_level,
            //'create_at' => $this->create_at,
        ]);
        //按时间排序
        $order = '';
        if($this->create_at){
            if($this->create_at ==1){
                $order = 'create_at asc';
            }else{
                $order = 'create_at desc';
            }
        }
        if($order){
            $query->orderBy($order);
        }else{
            $query->orderBy('id desc');
        }
        $query->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'complain_member_name', $this->complain_member_name])
            ->andFilterWhere(['like', 'yl_order.order_code', $this->order_code])
            ->andFilterWhere(['like', 'complain_content', $this->complain_content]);
        return $dataProvider;
    }
}
