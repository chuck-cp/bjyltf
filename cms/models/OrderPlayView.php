<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "{{%order_play_view}}".
 *
 * @property int $order_id 订单id
 * @property string $order_code 订单编号
 * @property string $salesman_name 业务合作人姓名
 * @property string $custom_service_name 广告对接人姓名
 * @property string $advert_name 广告名称
 * @property int $rate 频次
 * @property string $advert_time 广告时长
 * @property string $area_name
 * @property int $throw_province_number 覆盖了多少个省
 * @property int $throw_city_number 覆盖了多少个市
 * @property int $throw_area_number 覆盖了多少个区
 * @property int $throw_street_number 覆盖了多少个街道
 * @property string $throw_shop_number 覆盖的店铺数量
 * @property string $throw_screen_number 投放的屏幕数量
 * @property string $total_play_number 播放量
 * @property string $total_play_time 总计播放时长(秒)
 * @property int $total_play_rate 播放率(百分比)
 * @property string $total_watch_number 内容传播人数
 * @property int $large_shop_rate 大型店铺比例
 * @property int $medium_shop_rate 中型店铺比例
 * @property int $small_shop_rate 小型店铺比例
 * @property string $start_at 订单开始投放时间
 * @property string $end_at 订单结束投放时间
 * @property string $shop_number 新增店铺数量
 * @property string $screen_number 新增屏幕数量
 * @property string $new_play_number 新增播放量
 * @property string $new_play_rate 新增传播人数
 * @property double $play_number_multiple 播放量增加倍数
 * @property double $shop_number_multiple 店铺数量的倍数
 */
class OrderPlayView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_play_view}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_code', 'salesman_name', 'custom_service_name', 'advert_name', 'advert_time', 'area_name', 'total_play_time', 'medium_shop_rate', 'start_at', 'end_at'], 'required'],
            [['order_id', 'rate', 'throw_province_number', 'throw_city_number', 'throw_area_number', 'throw_street_number', 'throw_shop_number', 'throw_screen_number', 'total_play_number', 'total_play_time', 'total_play_rate', 'total_watch_number', 'large_shop_rate', 'medium_shop_rate', 'small_shop_rate', 'shop_number', 'screen_number', 'new_play_number', 'new_play_rate'], 'integer'],
            [['start_at', 'end_at'], 'safe'],
            [['play_number_multiple', 'shop_number_multiple'], 'number'],
            [['order_code'], 'string', 'max' => 20],
            [['salesman_name', 'custom_service_name', 'advert_name'], 'string', 'max' => 50],
            [['advert_time'], 'string', 'max' => 5],
            [['area_name'], 'string', 'max' => 255],
            [['order_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单id',
            'order_code' => '订单编号',
            'salesman_name' => '业务合作人姓名',
            'custom_service_name' => '广告对接人姓名',
            'advert_name' => '广告名称',
            'rate' => '频次',
            'advert_time' => '广告时长',
            'area_name' => 'Area Name',
            'throw_province_number' => '覆盖了多少个省',
            'throw_city_number' => '覆盖了多少个市',
            'throw_area_number' => '覆盖了多少个区',
            'throw_street_number' => '覆盖了多少个街道',
            'throw_shop_number' => '覆盖的店铺数量',
            'throw_screen_number' => '投放的屏幕数量',
            'total_play_number' => '播放量',
            'total_play_time' => '总计播放时长(秒)',
            'total_play_rate' => '播放率(百分比)',
            'total_watch_number' => '内容传播人数',
            'large_shop_rate' => '大型店铺比例',
            'medium_shop_rate' => '中型店铺比例',
            'small_shop_rate' => '小型店铺比例',
            'start_at' => '订单开始投放时间',
            'end_at' => '订单结束投放时间',
            'shop_number' => '新增店铺数量',
            'screen_number' => '新增屏幕数量',
            'new_play_number' => '新增播放量',
            'new_play_rate' => '新增传播人数',
            'play_number_multiple' => '播放量增加倍数',
            'shop_number_multiple' => '店铺数量的倍数',
        ];
    }
    /*
     * 入参：id,field
     * 返回：field值
     */
    public static function getFieldValue($id, $field){
        if(!$id || $field){return 0;}
        $res = self::find()->where(['order_id'=>$id])->select($field)->asArray()->one;
        $re = isset($res[$field]) ? $res[$field] : 0;
        return $re;
    }
}
