<?php
namespace cms\modules\member\models;
use cms\modules\account\models\LogPayment;
use cms\models\AdvertPosition;
use cms\modules\member\models\OrderArea;
use cms\modules\member\models\OrderDate;
use common\libs\ToolsClass;
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
 * @property int $advert_id 广告ID
 * @property string $advert_name 广告名称
 * @property string $advert_time 广告时长
 * @property string $create_at 创建时间
 * @property int $payment_status 订单状态(-1、放弃支付 0、未付款 1、已付预付款 2、预付款已逾期 3、已付全款)
 * @property int $examine_status 审核状态(0、待提交素材 1、待审核 2、被驳回 3、已通过 4、已投放 5、投放完成)
 */
class Order extends \yii\db\ActiveRecord
{
    public $starts_at;
    public $ends_at;
    public $phone;
    public $arrearage;
    public $image_url;
    //播放开始(1)
    public $order_date_starts_at;
    //播放开始(2)
    public $order_date_starts_at_end;
    public $order_date_ends_at;
    public $money;
    //area search
    public $province;
    public $city;
    public $area;
    //已播放天数
    public $already_days;

    /**
     * 合同申请
     */
    public $htstarts_at;
    public $htends_at;
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
            [['member_id', 'order_price', 'unit_price', 'total_day', 'payment_type', 'payment_price', 'overdue_number', 'screen_number', 'rate', 'advert_id', 'payment_status', 'examine_status', 'already_days'], 'integer'],
            [['payment_at', 'create_at', 'starts_at', 'ends_at', 'phone','order_date_starts_at','order_date_ends_at', 'province', 'city', 'area','htstarts_at','htends_at'], 'safe'],
            [['member_name', 'salesman_name', 'custom_service_name', 'advert_name'], 'string', 'max' => 50],
            [['salesman_mobile', 'custom_service_mobile'], 'string', 'max' => 11],
            [['order_code'], 'string', 'max' => 20],
            [['advert_time'], 'string', 'max' => 5],
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
            'member_name' => '广告购买人',
            'salesman_name' => '业务合作人',
            'salesman_mobile' => '合作人电话',
            'custom_service_name' => '业务对接人',
            'custom_service_mobile' => '业务对接人电话',
            'order_code' => '订单号',
            'order_price' => '订单总金额',
            'unit_price' => 'Unit Price',
            'total_day' => '购买天数',
            'payment_type' => '购买类型',
            'payment_price' => '首付款',
            'payment_at' => '首付款日期',
            'overdue_number' => 'Overdue Number',
            'screen_number' => '屏幕数量',
            'rate' => '频次',
            'advert_id' => '广告位',
            'advert_name' => '广告位',
            'advert_time' => '广告时长',
            'create_at' => 'Create At',
            'starts_at' => 'start_at',
            'ends_at' => 'end_at',
            'payment_status' => '订单状态',
            'examine_status' => '投放状态',
            'money' => '尾款',
            'area_name' => '投放地区',
            'remarks' => '订单备注',
        ];
    }
    //csv导出
    public function getCsvAttributes(){
        return [
            'order_code' => '订单号',
            'member_name' => '广告购买人',
            'area_name' => '投放地区',
            'advert_name' => '广告位',
            'advert_time' => '广告时长',
            'rate' => '频次',
            'total_day' => '购买天数',
            //已播放天数
            'already_days' => '已播放天数',
            'starts_at' => '投放日期',
            'ends_at' => '完成日期',
            'screen_number' => '屏幕数量',
            'examine_status' => '投放状态',
        ];
    }
    /*
     * 付款状态 -1、放弃支付 0、未付款 1、已付预付款 2、预付款已逾期 3、已付全款
     */
    public static function paymentStatus()
    {
        return [
            '-1' => '放弃支付',
            '0' => '未付款',
            '1' => '已付预付款',
            '2' => '预付款已逾期',
            '3' => '已付全款',
        ];
    }

    public static function examinedesc(){
        return [
            '1' => '视频长度不合适',
            '2' => '投放日期不符',
            '3' => '广告内容不符合标准',
            '4' => '缺少相关内容产权资料',
            '5' => '其他'
        ];
    }
    /*
     * 获取状态
     */
    public static function getOrderStatus($type,$number){
        $srr = [];
        switch ($type){
            case 'payment_type':
                $srr = [
                    '1' => '全额支付',
                    '2' => '定金支付',
                ];
                break;
            case 'examine_status':
                $srr = [
                    '0' => '待提交素材',
                    '1' => '待审核',
                    '2' => '被驳回',
                    '3' => '已通过',
                    '4' => '已投放',
                    '5' => '投放完成'
                ];
                break;
            case 'payment_status':
                $srr = [
                    '-1' => '放弃支付',
                    '0' => '未付款',
                    '1' => '已付预付款',
                    '2' => '预付款已逾期',
                    '3' => '已付全款',
                ];
                break;
            case 'contact_status':
                $srr = [
                    '-1' => '放弃支付',
                    '0' => '未付款',
                    '1' => '已付预付款',
                    '2' => '预付款已逾期',
                    '3' => '已付全款',
                ];
                break;
        }
        return array_key_exists($number,$srr) ? $srr[$number] : '未设置';
    }

    //广告审核获取状态
    public static function getOrderExamineStatus($number){
        $srr = [

            '0' => '待提交素材',
            '1' => '待审核',
            '2' => '被驳回',
            '3' => '待投放',
            '4' => '投放中',
            '5' => '投放完成'
        ];
        return array_key_exists($number,$srr) ? $srr[$number] : '未设置';
    }
    /**
     * 获取投放日期
     */
   /* public static function getDeliveryDate($id){
        if(!$id){
            return '';
        }else{
            $findOne=self::findOne($id);
            return self::findOne($id) == true ? $findOne['start_at'].'至'.$findOne['end_at']:'';
        }
    }*/


    /**
     * join with order_date
     */
    public function getOrderDate(){
        return $this->hasOne(OrderDate::className(),['order_id'=>'id'])->select('order_id,start_at,end_at');
    }
    /*
     * join with member
     */
    public function getMemberInfo(){
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('id,mobile');
    }

    /**
     * join with order_copyright
     */
    public function getOrderCopyright(){
        return $this->hasOne(OrderCopyright::className(),['order_id'=>'id'])->select('image_url');
    }
    /*
     * join with order_area
     */
    public function getOrderArea(){
        return $this->hasOne(OrderArea::className(),['order_id'=>'id']);
    }

    /**
     * @param $type
     * @param $number
     * @return mixed|string
     */
    public function getLogPayment(){
        return $this->hasOne(LogPayment::className(),['order_id'=>'id']);
    }
    /*
     * 获取有无状态
     */
    public static function getIsHave($type,$number){
        $srr = [];
        switch ($type){
            case 'examine_status':
                $srr = [
                    '0' => '待提交素材',
                    '1' => '待审核',
                    '2' => '被驳回',
                    '3' => '已通过',
                    '4' => '已投放',
                    '5' => '投放完成'
                ];
                break;
        }
        return array_key_exists($number,$srr) ? $srr[$number] : '未设置';
    }
    /*
     * 告诉csv类使用哪张表查询数据
     */
    public function checkFieldFormTable($field){
        $fields = [
            'start_at'=>'orderDate',
            'end_at' => 'orderDate',
        ];
        if(isset($fields[$field])){
            return $fields[$field];
        }
        return false;
    }
    /*
     * 处理数字变成文字
     */
    public function reformExport($column,$value){
        switch ($column){
            case 'examine_status':
                return $res = self::getIsHave('examine_status',$value);
                break;
            case 'salesman_mobile':
                return $value."\t";
                break;
            case 'order_code':
                return $value."\t";
                break;
            case 'salesman_name':
                return $value."\t";
                break;
            case 'advert_name':
                return $value."\t";
                break;
            case 'advert_time':
                return $value."\t";
                break;
            case 'starts_at':
                return OrderDate::getDeliveryDate($this->id)."\t";
                break;
            case 'ends_at':
                return OrderDate::getDeliveryDate($this->id, false)."\t";
                break;
            case 'already_days':
                if($this->examine_status == 4){
                    return ToolsClass::timediff(strtotime($this->orderDate['start_at']), time(), 'day')."\t";
                }
                return $this->total_day."\t";
                break;
            default :
                return $value."\t";
        }
    }

    public static function getadkeybyorderid($orderid){
        $order = self::findOne(['id'=>$orderid]);
        $adver = AdvertPosition::findOne(['id'=>$order]);
        return $adver;
    }
}
