<?php

namespace cms\modules\feedback\models;

use cms\modules\member\models\Order;
use cms\modules\member\models\Member;
/**
 * This is the model class for table "{{%order_complain}}".
 *
 * @property string $id
 * @property string $order_id 订单ID
 * @property string $member_id 投诉人ID
 * @property string $complain_member_id 被投诉人ID
 * @property string $complain_member_name 被投诉人姓名
 * @property int $complain_type 投诉类型(1、广告对接人 2、业务合作人)
 * @property int $complain_level 投诉等级
 * @property string $complain_content 投诉内容
 * @property string $create_at
 */
class OrderComplain extends \yii\db\ActiveRecord
{
    public $order_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_complain}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'complain_member_id', 'complain_type', 'complain_level','member_type'], 'integer'],
            [['create_at','order_code'], 'safe'],
            [['member_id'], 'string', 'max' => 10],
            [['complain_member_name'], 'string', 'max' => 50],
            [['complain_content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单号',
            'member_id' => '投诉人ID',
            'complain_member_id' => '被投诉人ID',
            'complain_member_name' => '被评价人姓名',
            'complain_type' => '投诉类型',
            'complain_level' => '投诉等级',
            'complain_content' => '投诉内容',
            'create_at' => '提交日期',
        ];
    }

    /**
     * 投诉等级
     * @param $num
     * @return mixed|string
     */
    public static function getComplainLevel($num){
        $srr = [
            '1'=>'极不满意',
            '2'=>'不满意',
            '3'=>'一般',
            '4'=>'满意',
            '5'=>'非常满意',
        ];
        return array_key_exists($num,$srr) ? $srr[$num] : '未设置';
    }


    public function getOrderInfo(){
        return $this->hasOne(Order::className(),['id'=>'order_id'])->select('id,order_code');
    }


    /**
     * 关联member
     */
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('id,name,mobile');
    }

}
