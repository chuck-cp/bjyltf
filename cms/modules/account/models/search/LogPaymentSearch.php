<?php

namespace cms\modules\account\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\account\models\LogPayment;

/**
 * LogPaymentSearch represents the model behind the search form of `app\modules\account\models\LogPayment`.
 */
class LogPaymentSearch extends LogPayment
{
    public $pay_at_end;
    public $salesman_name;
    public $custom_service_mobile;
    public $salesman_id;
    public $custom_member_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price', 'pay_style', 'pay_status', 'payment_code'], 'integer'],

            [['serial_number', 'order_code', 'other_account', 'other_serial', 'pay_at', 'pay_at_end', 'salesman_name','custom_service_name','custom_service_mobile','create_at','create_at_end', 'pay_type', 'member_id', 'member_name','salesman_id','custom_member_id'], 'safe'],

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
    public function search($params, $export = 0)
    {
        $query = LogPayment::find()->joinWith('orderInfo')->joinWith('brokerage');

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
        /***付款时间***/
        if(isset($this->pay_at) && $this->pay_at){
            $query->andWhere(['>=','pay_at',$this->pay_at.' 00:00:00']);
        }
        if(isset($this->pay_at_end) && $this->pay_at_end){
            $query->andWhere(['<=','pay_at',$this->pay_at_end.' 23:59:59']);
        }
        /***提交时间***/
        if(isset($this->create_at) && $this->create_at ){

            $query->andWhere(['>','yl_order.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<','yl_order.create_at',$this->create_at_end.' 23:59:59']);
        }
        /***支付方式****/
        if(isset($this->pay_type) && !empty($this->pay_type)){
            $query->andWhere(['in','yl_log_payment.pay_type',$this->pay_type]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'pay_style' => $this->pay_style,
            'pay_status' => $this->pay_status,
            'payment_code' => $this->payment_code,
            'yl_order.member_id' => $this->member_id,
            'yl_order.salesman_id' => $this->salesman_id,
            'yl_order.custom_member_id' => $this->custom_member_id,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'yl_log_payment.order_code', $this->order_code])
            ->andFilterWhere(['like', 'yl_order.salesman_name', $this->salesman_name])
            ->andFilterWhere(['like', 'yl_order.member_name', $this->member_name])
            ->andFilterWhere(['like', 'other_serial', $this->other_serial])
            ->andFilterWhere(['like', 'yl_order.custom_service_name', $this->custom_service_name])
            ->andFilterWhere(['like', 'yl_order.custom_service_mobile', $this->custom_service_mobile]);
        $query->orderBy('id desc');
        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }
}
