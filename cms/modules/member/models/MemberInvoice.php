<?php

namespace cms\modules\member\models;

use Yii;
/**
 * This is the model class for table "yl_member_invoice".
 *
 * @property string $id
 * @property string $member_id 申请开票人
 * @property string $member_name 申请人姓名
 * @property string $member_phone 申请人手机
 * @property int $invoice_title_type 发票抬头 1：个人或者非企业单位 2：企业单位
 * @property string $invoice_title 发票抬头内容
 * @property string $taxplayer_id 纳税人识别号
 * @property string $receiver 收件人
 * @property string $invoice_value 发票金额单位是分
 * @property string $contact_phone 联系电话
 * @property string $address_id 省市区的id
 * @property string $address_detail 详细地址
 * @property string $remark 发票的备注和说明
 * @property string $invoice_address 发票上的购买方地址
 * @property string $invoice_phone 发票上填写的购买方电话
 * @property string $bank_name 开户银行
 * @property string $bank_account 开户账号
 * @property string $tracking_number 物流单号
 * @property string $logistics_name 物流名称
 * @property int $order_num 该发票对应的订单总数
 * @property string $create_at
 * @property string $update_at 修改时间
 * @property int $status 1 申请中 2 已开票
 */
class MemberInvoice extends \yii\db\ActiveRecord
{

    public $starts_at; //开始时间
    public $ends_at;   //结束时间

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_member_invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'invoice_title_type', 'invoice_value', 'address_id', 'order_num', 'status'], 'integer'],
            [['member_name', 'member_phone'], 'required'],
            [['create_at', 'update_at','starts_at','ends_at'], 'safe'],
            [['member_name'], 'string', 'max' => 255],
            [['member_phone', 'taxplayer_id', 'receiver', 'tracking_number'], 'string', 'max' => 20],
            [['invoice_title', 'bank_account', 'logistics_name'], 'string', 'max' => 30],
            [['contact_phone', 'invoice_phone'], 'string', 'max' => 15],
            [['address_detail'], 'string', 'max' => 80],
            [['remark'], 'string', 'max' => 50],
            [['invoice_address'], 'string', 'max' => 120],
            [['bank_name'], 'string', 'max' => 40],
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
            'member_phone' => 'Member Phone',
            'invoice_title_type' => 'Invoice Title Type',
            'invoice_title' => 'Invoice Title',
            'taxplayer_id' => 'Taxplayer ID',
            'receiver' => 'Receiver',
            'invoice_value' => 'Invoice Value',
            'contact_phone' => 'Contact Phone',
            'address_id' => 'Address ID',
            'address_detail' => 'Address Detail',
            'remark' => 'Remark',
            'invoice_address' => 'Invoice Address',
            'invoice_phone' => 'Invoice Phone',
            'bank_name' => 'Bank Name',
            'bank_account' => 'Bank Account',
            'tracking_number' => 'Tracking Number',
            'logistics_name' => 'Logistics Name',
            'order_num' => 'Order Num',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'status' => 'Status',
        ];
    }

    //物流信息
    public static function getWlInfo($type, $code){
        $curr_time = time();
        $url = 'http://m.kuaidi100.com/query?type='.$type.'&id=1&postid=' .$code. '&temp='.$curr_time;
        $res = file_get_contents($url);
        $res = json_decode($res,true);
        if ($res['status'] == 200) {
            return $res['data'];
        }
    }
    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public static function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}
