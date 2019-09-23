<?php

namespace cms\modules\examine\models;

use cms\modules\config\models\SystemConfig;
use cms\modules\member\models\Member;
use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property string $member_id 用户ID
 * @property string $member_name 姓名
 * @property string $salesman_name 业务员姓名
 * @property string $salesman_mobile 业务员手机号
 * @property string $custom_service_name 广告对接人姓名
 * @property string $custom_service_mobile 广告对接人手机号
 * @property string $order_code 订单号
 * @property string $order_price 订单总金额(总价=单价*天数)
 * @property string $unit_price 单价
 * @property int $total_day 总天数
 * @property int $payment_type 支付类型(1、全款 2、预付款)
 * @property string $payment_price 已支付金额
 * @property string $payment_at 首付款时间
 * @property int $overdue_number 允许逾期的天数
 * @property string $screen_number 屏幕数量
 * @property int $rate 播放频率
 * @property string $area_name 投放地区
 * @property int $advert_id 广告ID
 * @property string $advert_name 广告名称
 * @property string $advert_time 广告时长
 * @property string $create_at 创建时间
 * @property int $payment_status 订单状态(-1、放弃支付 0、未付款 1、已付预付款 2、预付款已逾期 3、已付全款)
 * @property int $examine_status 审核状态(0、待提交素材 1、待审核 2、被驳回 3、已通过 4、已投放 5、投放完成)
 */
class Order extends \yii\db\ActiveRecord
{

    public $order_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'member_name', 'order_code'], 'required'],
            [['member_id', 'order_price', 'unit_price', 'total_day', 'payment_type', 'payment_price', 'overdue_number', 'screen_number', 'rate', 'advert_id', 'payment_status', 'examine_status'], 'integer'],
            [['payment_at', 'create_at'], 'safe'],
            [['member_name', 'salesman_name', 'custom_service_name', 'advert_name'], 'string', 'max' => 50],
            [['salesman_mobile', 'custom_service_mobile'], 'string', 'max' => 11],
            [['order_code'], 'string', 'max' => 20],
            [['area_name'], 'string', 'max' => 200],
            [['advert_time'], 'string', 'max' => 5],
            [['start_at','end_at'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'member_name' => 'Member Name',
            'salesman_name' => '业务合作人',
            'salesman_mobile' => '联系电话',
            'custom_service_name' => 'Custom Service Name',
            'custom_service_mobile' => 'Custom Service Mobile',
            'order_code' => '订单号',
            'order_price' => 'Order Price',
            'unit_price' => 'Unit Price',
            'total_day' => 'Total Day',
            'payment_type' => 'Payment Type',
            'payment_price' => 'Payment Price',
            'payment_at' => 'Payment At',
            'overdue_number' => 'Overdue Number',
            'screen_number' => 'Screen Number',
            'rate' => 'Rate',
            'area_name' => 'Area Name',
            'advert_id' => 'Advert ID',
            'advert_name' => '广告位',
            'advert_time' => '广告时长',
            'create_at' => '创建时间',
            'payment_status' => 'Payment Status',
            'examine_status' => '状态',
        ];
    }

    /**
     * 关联order_data表
     */
    public function getOrderDate(){
        return $this->hasOne(OrderDate::className(),['id'=>'order_id'])->select();
    }

    /**
     * join with yl_log_examine
     * @return bool
     */
    public function getLogExamine(){
        return $this->hasOne(LogExamine::className(),['id'=>'foreign_id'])->select();
    }
}
