<?php

namespace cms\modules\guest\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\Order;

/**
 * OrderkfSearch represents the model behind the search form of `cms\modules\examine\models\Order`.
 */
class OrderkfSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public $phone;
    public function rules()
    {
        return [
            [['id', 'member_id', 'salesman_id', 'custom_member_id', 'order_price', 'unit_price', 'deal_price', 'total_day', 'payment_type', 'payment_price', 'overdue_number', 'screen_number', 'number', 'rate', 'company_area_id', 'advert_id', 'download_number', 'payment_status', 'examine_status', 'schedule_status', 'report_status', 'lock', 'resource_duration', 'line_pay', 'is_billing', 'contact_status', 'buy_agreed'], 'integer'],
            [['member_name', 'member_mobile', 'salesman_name', 'salesman_mobile', 'custom_service_name', 'custom_service_mobile', 'order_code', 'payment_at', 'overdue_at', 'last_payment_at', 'area_name', 'advert_key', 'advert_name', 'advert_time', 'resource', 'resource_name', 'resource_thumbnail', 'video_id', 'video_trans_url', 'create_at', 'resource_attribute', 'contact_number', 'contact_at','phone'], 'safe'],
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
    public function Ordersearch($params)
    {
//        $query = Order::find()->joinWith('orderDate')->joinWith('memberInfo')->joinWith('logPayment');
        $query = Order::find()->joinWith('orderDate')->joinWith('orderArea')->joinWith('logPayment');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {

            return $dataProvider;
        }

        if (!trim($this->order_code) && !trim($this->phone) && !trim($this->salesman_name) && !trim($this->advert_id) && !trim($this->custom_service_name) && !trim($this->payment_type) && !trim($this->payment_status) && !trim($this->starts_at) && !trim($this->ends_at)) {
            $query->andWhere(['yl_order.id' => 0]);
        } else {
            $query->andFilterWhere([
                'payment_type' => $this->payment_type,
                'advert_id' => $this->advert_id,
                'payment_status' => $this->payment_status,
            ]);
            /*只查询，待审核、已通过、被驳回*/
            if (!empty($examine_status)) {
                $query->andWhere(['in', 'examine_status', $examine_status]);
            }
            /*首付款日期查询*/
            if (isset($this->starts_at) && $this->starts_at) {
                $query->andWhere(['>=', 'payment_at', $this->starts_at]);
            }
            if (isset($this->ends_at) && $this->ends_at) {
                $query->andWhere(['<=', 'payment_at', $this->ends_at]);
            }

            if ($this->order_date_starts_at) {
                $query->andWhere(['>=', 'yl_order_date.start_at', $this->order_date_starts_at]);
            }
            //order_date_starts_at_end
            if ($this->order_date_starts_at_end) {
                $query->andWhere(['<=', 'yl_order_date.start_at', $this->order_date_starts_at_end]);
            }
            if ($this->order_date_ends_at) {
                $query->andWhere(['<=', 'yl_order_date.end_at', $this->order_date_ends_at]);
            }

            //播放管理按地区搜索
            $area = max([$this->province, $this->city, $this->area]);
            if ($area) {
                if (strlen($area) == 9) {
                    $province = substr($area, 0, 5);
                    $city = substr($area, 0, 7);
                    $query->andWhere(['or', ['regexp', 'yl_order_area.area_id', $area], ['regexp', 'yl_order_area.area_id', $city . ','], ['regexp', 'yl_order_area.area_id', $province . ','], ['=', 'yl_order_area.area_id', $province]]);
                } elseif (strlen($area) == 7) {
                    $province = substr($area, 0, 5);

                    $query->andWhere(['or', ['regexp', 'yl_order_area.area_id', $area], ['regexp', 'yl_order_area.area_id', $province . ','], ['=', 'yl_order_area.area_id', $province]]);
                } else {
                    $query->andWhere(['or', ['regexp', 'yl_order_area.area_id', $area]]);
                }
            }

            // grid filtering conditions
            $query->andFilterWhere([
                'yl_order.id' => $this->id,
                'yl_order.member_id' => $this->member_id,
                'yl_order.salesman_id' => $this->salesman_id,
                'yl_order.custom_member_id' => $this->custom_member_id,
                'yl_order.order_price' => $this->order_price,
                'yl_order.order_code' => $this->order_code,
                'yl_order.unit_price' => $this->unit_price,
                'yl_order.deal_price' => $this->deal_price,
                'yl_order.total_day' => $this->total_day,
                'yl_order.payment_type' => $this->payment_type,
                'yl_order.payment_price' => $this->payment_price,
                'yl_order.payment_at' => $this->payment_at,
                'yl_order.overdue_at' => $this->overdue_at,
                'yl_order.last_payment_at' => $this->last_payment_at,
                'yl_order.overdue_number' => $this->overdue_number,
                'yl_order.screen_number' => $this->screen_number,
                'yl_order.number' => $this->number,
                'yl_order.rate' => $this->rate,
                'yl_order.company_area_id' => $this->company_area_id,
                'yl_order.advert_id' => $this->advert_id,
                'yl_order.download_number' => $this->download_number,
                'yl_order.create_at' => $this->create_at,
                'yl_order.payment_status' => $this->payment_status,
                'yl_order.examine_status' => $this->examine_status,
                'yl_order.schedule_status' => $this->schedule_status,
                'yl_order.report_status' => $this->report_status,
                'yl_order.lock' => $this->lock,
                'yl_order.resource_duration' => $this->resource_duration,
                'yl_order.line_pay' => $this->line_pay,
                'yl_order.is_billing' => $this->is_billing,
                'yl_order.contact_status' => $this->contact_status,
                'yl_order.contact_at' => $this->contact_at,
                'yl_order.buy_agreed' => $this->buy_agreed,
            ]);

            $query->andFilterWhere(['like', 'yl_order.member_name', $this->member_name])
                ->andFilterWhere(['like', 'yl_order.member_mobile', $this->member_mobile])
                ->andFilterWhere(['like', 'yl_order.salesman_name', $this->salesman_name])
                ->andFilterWhere(['like', 'yl_order.salesman_mobile', $this->salesman_mobile])
                ->andFilterWhere(['like', 'yl_order.custom_service_name', $this->custom_service_name])
                ->andFilterWhere(['like', 'yl_order.custom_service_mobile', $this->custom_service_mobile])
                ->andFilterWhere(['like', 'yl_order.area_name', $this->area_name])
                ->andFilterWhere(['like', 'yl_order.advert_key', $this->advert_key])
                ->andFilterWhere(['like', 'yl_order.advert_name', $this->advert_name])
                ->andFilterWhere(['like', 'yl_order.advert_time', $this->advert_time])
                ->andFilterWhere(['like', 'yl_order.resource', $this->resource])
                ->andFilterWhere(['like', 'yl_order.resource_name', $this->resource_name])
                ->andFilterWhere(['like', 'yl_order.resource_thumbnail', $this->resource_thumbnail])
                ->andFilterWhere(['like', 'yl_order.video_id', $this->video_id])
                ->andFilterWhere(['like', 'yl_order.video_trans_url', $this->video_trans_url])
                ->andFilterWhere(['like', 'yl_order.resource_attribute', $this->resource_attribute])
                ->andFilterWhere(['like', 'yl_order.contact_number', $this->contact_number]);
        }
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();

        $query->orderBy('id desc');

        return $dataProvider;

    }
}
