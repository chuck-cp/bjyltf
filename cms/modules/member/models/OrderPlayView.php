<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "yl_order_play_view".
 *
 * @property int $order_id 订单id
 * @property string $order_code 订单编号
 * @property string $salesman_name 业务员姓名
 * @property string $custom_service_name 广告对接人姓名
 * @property string $advert_name 广告名称
 * @property int $advert_rate 频次
 * @property string $advert_time 广告时长
 * @property string $throw_area 广告投放地区(最多显示10个)
 * @property string $throw_province_number 覆盖了多少个省
 * @property string $throw_city_number 覆盖了多少个市
 * @property string $throw_area_number 覆盖了多少个区
 * @property string $throw_street_number 覆盖了多少个街道
 * @property string $throw_shop_number 覆盖的店铺数量
 * @property string $throw_screen_number 投放的屏幕数量
 * @property string $throw_mirror_number 辐射的镜面数量
 * @property string $screen_run_time 屏幕平均开机时长
 * @property string $total_play_number 实播总次数
 * @property string $total_play_time 总计播放时长(秒)
 * @property int $total_play_rate 播放率(百分比)
 * @property string $total_watch_number 广告直接观看人数
 * @property string $total_no_repeat_watch_number 不重复观看人数
 * @property string $total_people_watch_number 每人次平均观看次数
 * @property string $people_watch_number 每人平均观看次数
 * @property string $total_radiation_number 广告辐射人数
 * @property string $total_arrival_rate 到达率
 * @property string $total_order_play_number 应播总次数
 * @property string $start_at 订单开始投放时间
 * @property string $end_at 订单结束投放时间
 * @property string $give_shop_number 新增店铺数量
 * @property string $give_screen_number 新增屏幕数量
 * @property string $give_play_number 新增播放量
 * @property string $give_watch_number 新增观看人数
 * @property string $give_radiation_number 新增辐射人数
 */
class OrderPlayView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_order_play_view';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'order_code', 'salesman_name', 'custom_service_name', 'advert_name', 'advert_time', 'throw_area', 'start_at', 'end_at'], 'required'],
            [['order_id', 'advert_rate', 'throw_province_number', 'throw_city_number', 'throw_area_number', 'throw_street_number', 'throw_shop_number', 'throw_screen_number', 'throw_mirror_number', 'total_play_number', 'total_play_time', 'total_play_rate', 'total_watch_number', 'total_no_repeat_watch_number', 'total_radiation_number', 'total_order_play_number', 'give_shop_number', 'give_screen_number', 'give_play_number', 'give_watch_number', 'give_radiation_number'], 'integer'],
            [['start_at', 'end_at'], 'safe'],
            [['order_code'], 'string', 'max' => 20],
            [['salesman_name', 'custom_service_name', 'advert_name'], 'string', 'max' => 50],
            [['advert_time'], 'string', 'max' => 5],
            [['throw_area'], 'string', 'max' => 255],
            [['screen_run_time', 'total_people_watch_number', 'people_watch_number'], 'string', 'max' => 6],
            [['total_arrival_rate'], 'string', 'max' => 8],
            [['order_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_code' => 'Order Code',
            'salesman_name' => 'Salesman Name',
            'custom_service_name' => 'Custom Service Name',
            'advert_name' => 'Advert Name',
            'advert_rate' => 'Advert Rate',
            'advert_time' => 'Advert Time',
            'throw_area' => 'Throw Area',
            'throw_province_number' => 'Throw Province Number',
            'throw_city_number' => 'Throw City Number',
            'throw_area_number' => 'Throw Area Number',
            'throw_street_number' => 'Throw Street Number',
            'throw_shop_number' => 'Throw Shop Number',
            'throw_screen_number' => 'Throw Screen Number',
            'throw_mirror_number' => 'Throw Mirror Number',
            'screen_run_time' => 'Screen Run Time',
            'total_play_number' => 'Total Play Number',
            'total_play_time' => 'Total Play Time',
            'total_play_rate' => 'Total Play Rate',
            'total_watch_number' => 'Total Watch Number',
            'total_no_repeat_watch_number' => 'Total No Repeat Watch Number',
            'total_people_watch_number' => 'Total People Watch Number',
            'people_watch_number' => 'People Watch Number',
            'total_radiation_number' => 'Total Radiation Number',
            'total_arrival_rate' => 'Total Arrival Rate',
            'total_order_play_number' => 'Total Order Play Number',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'give_shop_number' => 'Give Shop Number',
            'give_screen_number' => 'Give Screen Number',
            'give_play_number' => 'Give Play Number',
            'give_watch_number' => 'Give Watch Number',
            'give_radiation_number' => 'Give Radiation Number',
        ];
    }

    /**
     *根据传入字段获取数据
     */
    public static function getFields($order_id,$fields){
        $obj = OrderPlayView::find()->where(['order_id'=>$order_id]);
        if(!$obj){ return []; }
        $select = '';
        if(!$fields){
            $select = '*';
        }elseif (is_array($fields)){
            $attributes = (new self())->getAttributes();
            $finally = [];
            foreach (array_filter($fields) as $k => $v){
                if(array_key_exists($v,$attributes)){
                    $finally[] = $v;
                }
            }
            if(!empty($finally)){
                $select = implode(',',$finally);
            }else{
                $select = '*';
            }
        }else{
            $select = $fields;
        }
        return is_array($order_id) ? $obj->select($select)->asArray()->all() : $obj->select($select)->asArray()->one();
    }
}
