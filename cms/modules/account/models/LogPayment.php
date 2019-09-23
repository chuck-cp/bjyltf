<?php

namespace cms\modules\account\models;

use cms\modules\member\models\Order;
use common\libs\ToolsClass;
use Yii;
use cms\modules\account\models\OrderBrokerage;
/**
 * This is the model class for table "{{%log_payment}}".
 *
 * @property string $id
 * @property string $serial_number 流水号
 * @property string $order_code 订单号
 * @property string $price 交易总金额
 * @property int $pay_type 支付方式(1、支付宝 2、银联 3、微信 4、线下付款)
 * @property int $pay_status 付款状态(0、未付款 1、已付款)
 * @property string $payment_code 随机交易码
 * @property string $other_account 第三方平台帐号
 * @property string $other_serial 第三方平台流水号
 * @property string $pay_at 收款时间
 */
class LogPayment extends \yii\db\ActiveRecord
{
    public $order_price;
    public $pay_at_end;
    public $member_name;
    public $salesman_name;
    public $salesman_mobile;
    public $custom_service_name;
    public $custom_service_mobile;
    public $create_at;
    public $create_at_end;
    public $payment_price;
    public $count_payment_price;
    public $member_id;
    //csv others
    public $total;
    public $real_income;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial_number', 'order_code', 'price'], 'required'],
            [['price', 'pay_type', 'pay_status', 'payment_code', 'pay_style'], 'integer'],
            [['pay_at', 'pay_at_end', 'member_name'], 'safe'],
            [['serial_number'], 'string', 'max' => 30],
            [['order_code'], 'string', 'max' => 20],
            [['other_account', 'other_serial'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => '流水号',
            'order_code' => '订单号',
            'price' => '本次收款金额',
            'pay_style'=>'支付类型',
            'pay_type' => '支付方式',
            'payment_code' => '交易码',
            'other_account' => '第三方平台帐号',
            'other_serial' => '第三方平台流水号',

            //others
            'order_price' => '订单总额',
            'member_name' => '用户姓名',
            'total' => '业务合作人支出',
            'real_income' => '广告实际收入',
            'salesman_name' => '合作人',
            'salesman_mobile' => '合作人手机',
            'pay_status' => '付款状态',
            'pay_at' => '收款时间',

            'custom_service_name' => '对接人',
            'pay_at_end' => 'pay at end',

        ];
    }
    /*
     * 支付类型
     */
    public static function getPayStyle($type = false, $key = 0){
        $srr = [
            '1' => '全款支付',
            '2' => '定金支付',
            '3' => '尾款支付',
        ];
        if($type){
            return array_key_exists($key, $srr) ? $srr[$key] : '未设置';
        }
        return $srr;
    }

    /**
     * 付款状态
     * @param bool $type
     * @param int $key
     * @return array|mixed|string
     */
    public static function getPayStatus($type = false, $key = 0){
        $srr = [
            '0'=>'未付款',
            '1'=>'已付款'
        ];
        if($type){
            return array_key_exists($key, $srr) ? $srr[$key] : '未设置';
        }
        return $srr;
    }
    /*
     * 支付方式
     */
    public static function getPayType($type = false, $key = 0){
        $srr = [
            '1' => '支付宝',
//            '2' => '银联',
            '3' => '微信',
            '4' => '线下付款',
        ];
        if($type){
            return array_key_exists($key, $srr) ? $srr[$key] : '未设置';
        }
        return $srr;
    }
    //导出字段处理
    public function reformExport($column,$value){
        switch ($column){
            case 'pay_style':
                return self::getPayStyle(true,$value);
                break;
            case 'pay_type':
                return self::getPayType(true,$value);
                break;
            case 'pay_status':
                return self::getPayStatus(true,$value);
                break;
            case 'price':
                return ToolsClass::priceConvert($value);
                break;
            case 'serial_number':
                return $value."\t";
            case 'order_price':
                if($this->pay_style ==2){
                    return '0.00';
                }else{
                    return $value/100;
                }
            case 'total':
                if($this->pay_style ==2){
                    return '0.00';
                }else{
                    return $value/100;
                }
            case 'real_income':
                if($this->pay_style ==2){
                    return '0.00';
                }else{
                    return $value/100;
                }
            default:
                return $value;
        }
    }
    //csv导出用
    public function checkFieldFormTable($field){
        $fields = [
            'order_price' => 'orderInfo',
            'member_name' => 'orderInfo',
            'total' => 'brokerage',
            'real_income' => 'brokerage',
            'salesman_name' => 'orderInfo',
            'salesman_mobile' => 'orderInfo',
            'custom_service_name' => 'orderInfo',
        ];
        if(isset($fields[$field])){
            return $fields[$field];
        }
        return false;
    }
    /*
     * 计算总收益 pay_status=1
     */
    public static function getTotalMoney(){
        return number_format(self::find()->sum('price')/100,2);
    }
    /*
     * join with order table
     */
    public function getOrderInfo(){
        return $this->hasOne(Order::className(),['order_code'=>'order_code'])->select('id,member_id,order_code,order_price, member_name,salesman_name,salesman_mobile,custom_service_name,custom_service_mobile,create_at,payment_price,payment_status,salesman_id,custom_member_id,preferential_way,final_price,remarks');
    }
    /*
     * join with yl_order_brokerage
     */
    public function getBrokerage(){
        return $this->hasOne(OrderBrokerage::className(),['order_id'=>'order_id'])->select('order_id, cooperate_money, total, real_income');
    }
}
