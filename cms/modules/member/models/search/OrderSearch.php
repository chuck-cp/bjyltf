<?php

namespace cms\modules\member\models\search;

use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\modules\member\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'order_price', 'unit_price', 'total_day', 'payment_type', 'payment_price', 'overdue_number', 'screen_number', 'rate', 'advert_id', 'payment_status', 'examine_status'], 'integer'],
            [['member_name', 'salesman_name', 'salesman_mobile', 'custom_service_name', 'custom_service_mobile', 'order_code', 'payment_at', 'advert_name', 'advert_time', 'create_at', 'starts_at', 'ends_at', 'phone','order_date_starts_at','order_date_ends_at', 'order_date_starts_at_end', 'province', 'city', 'area','htstarts_at','htends_at','preferential_way'], 'safe'],
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
    public function search($params,$export = 0,$examine_status = [], $report = false)
    {
        $query = $report == 'report' ? Order::find()->joinWith('orderDate')->joinWith('orderArea')->joinWith('logPayment') : Order::find()->joinWith('orderDate')->joinWith('memberInfo')->joinWith('logPayment');
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
        // $query->limit();
        // grid filtering conditions
        $query->andFilterWhere([
            'payment_type' => $this->payment_type,
            'advert_id' => $this->advert_id,
            'payment_status' => $this->payment_status,
        ]);
        /*只查询，待审核、已通过、被驳回*/
        if(!empty($examine_status)){
            $query->andWhere(['in','examine_status',$examine_status]);
        }
        /*首付款日期查询*/
        if(isset($this->starts_at) && $this->starts_at){
            $query->andWhere(['>=','payment_at',$this->starts_at]);
        }
        if(isset($this->ends_at) && $this->ends_at){
            $query->andWhere(['<=','payment_at',$this->ends_at]);
        }

        //优惠方式
        if(isset($this->preferential_way) && $this->preferential_way){
            $query->andWhere(['=','yl_order.preferential_way',$this->preferential_way]);
        }
        //$export==6的时候为合同申请  只查询已申请和已完成数据（1：已申请，2：已完成）
        if($export==6){
            if(isset($this->htstarts_at) && $this->htstarts_at){
                $query->andWhere(['>=','yl_order.create_at',$this->htstarts_at.' 00-00-00']);
            }
            if(isset($this->htends_at) && $this->htends_at){
                $query->andWhere(['<=','yl_order.create_at',$this->htends_at.' 23-59-59']);
            }
            $query->andWhere(['!=','contact_status',0]);
        }
        //ToolsClass::p($params);

        /*投放日期查询*/
        /*
        if($this->order_date_starts_at && !$this->order_date_ends_at){
            $query->andWhere(['>=','yl_order_date.start_at',$this->order_date_starts_at]);
        }
        if($this->order_date_ends_at && !$this->order_date_starts_at){
            $query->andWhere(['<=','yl_order_date.end_at',$this->order_date_ends_at]);
        }

        if($this->order_date_starts_at && $this->order_date_ends_at){
            $query->andWhere(['>=','yl_order_date.start_at',$this->order_date_starts_at]);
            $query->andWhere(['<=','yl_order_date.end_at',$this->order_date_ends_at]);
        }
        */
        if($this->order_date_starts_at){
            $query->andWhere(['>=','yl_order_date.start_at',$this->order_date_starts_at]);
        }
        //order_date_starts_at_end
        if($this->order_date_starts_at_end){
            $query->andWhere(['<=','yl_order_date.start_at',$this->order_date_starts_at_end]);
        }
        if($this->order_date_ends_at){
            $query->andWhere(['<=','yl_order_date.end_at',$this->order_date_ends_at]);
        }
        //ToolsClass::p($query);die;
        //播放管理按地区搜索
        $area = max([$this->province, $this->city, $this->area]);
        if($area){
            if(strlen($area) == 9){
                $province = substr($area, 0, 5 );
                $city = substr($area, 0, 7 );

                $query->andWhere(['or', ['regexp', 'yl_order_area.area_id', $area], ['regexp', 'yl_order_area.area_id', $city.','], ['regexp', 'yl_order_area.area_id', $province.','], ['=', 'yl_order_area.area_id', $province]]);
            }elseif(strlen($area) == 7){
                $province = substr($area, 0, 5 );

                $query->andWhere(['or', ['regexp', 'yl_order_area.area_id', $area],  ['regexp', 'yl_order_area.area_id', $province.','],  ['=', 'yl_order_area.area_id', $province]]);
            }else{
                $query->andWhere(['or', ['regexp', 'yl_order_area.area_id', $area]]);
            }
        }
        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'salesman_name', $this->salesman_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->phone])
            ->andFilterWhere(['=', 'total_day', $this->total_day])
            ->andFilterWhere(['=', 'examine_status', $this->examine_status])
            ->andFilterWhere(['=', 'screen_number', $this->screen_number])
            ->andFilterWhere(['like', 'salesman_mobile', $this->salesman_mobile])
            ->andFilterWhere(['like', 'custom_service_name', $this->custom_service_name])
            ->andFilterWhere(['like', 'custom_service_mobile', $this->custom_service_mobile])
            ->andFilterWhere(['like', 'yl_order.order_code', $this->order_code])
            ->andFilterWhere(['like', 'advert_name', $this->advert_name])
            ->andFilterWhere(['like', 'advert_time', $this->advert_time]);
        //->andFilterWhere(['regexp', 'yl_order_area.area_id', $area]);
        $query->orderBy('id desc');
        /*$commandQuery = clone $query;
        echo  $commandQuery->createCommand()->getRawSql();*/
        if($export == 1){
            return $query;
        }


        return $dataProvider;
    }



    public function AdvertSearch($params){
        $query = Order::find()->joinWith('memberInfo');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        /*首付款日期查询*/
        if(isset($this->starts_at) && $this->starts_at){
            $query->andWhere(['>=','payment_at',$this->starts_at.' 00-00-00']);
        }
        if(isset($this->ends_at) && $this->ends_at){
            $query->andWhere(['<=','payment_at',$this->ends_at.' 23-59-59']);
        }

        $query->andFilterWhere([
            'advert_id' => $this->advert_id,
            'payment_type' => $this->payment_type,
            'payment_status' => $this->payment_status,
        ]);

        if($this->examine_status!=''){
//            $query->andFilterWhere(['and',['=','yl_order.examine_status',$this->examine_status],['=','yl_order.payment_status', 3]]);
            $query->andFilterWhere(['=','yl_order.examine_status',$this->examine_status]);
            if($this->payment_status!=''){
                $query->andFilterWhere(['=','yl_order.payment_status',$this->payment_status]);
            }else{
                $query->andFilterWhere(['=','yl_order.payment_status', 3]);
            }
        }

        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->phone])
            ->andFilterWhere(['like', 'salesman_name', $this->salesman_name])
            ->andFilterWhere(['like', 'advert_name', $this->advert_name])
            ->andFilterWhere(['like', 'custom_service_name', $this->custom_service_name])
            ->andFilterWhere(['like', 'advert_time', $this->advert_time]);
        $query->orderBy('yl_order.id desc');
        //->andFilterWhere(['regexp', 'yl_order_area.area_id', $area]);
        /*$commandQuery = clone $query;
        echo  $commandQuery->createCommand()->getRawSql();*/
        return $dataProvider;

    }
    /**
     * 广告排期管理 订单列表
     * wpw
     */
    public function ordersearch($OrderIdAll){
        $query = Order::find()->joinWith('orderDate');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->andWhere(['in','yl_order.id',$OrderIdAll]);
        return $dataProvider;
    }
}
