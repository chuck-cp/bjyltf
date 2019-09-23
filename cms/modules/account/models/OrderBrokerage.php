<?php

namespace cms\modules\account\models;

use cms\modules\member\models\Order;
use Yii;

/**
 * This is the model class for table "{{%order_brokerage}}".
 *
 * @property string $id
 * @property string $order_id 关联yl_order表的id
 * @property string $member_name 业务员名称
 * @property string $member_mobile 业务员手机号
 * @property string $member_price 业务合作人佣金
 * @property string $first 一级提成
 * @property string $second 二级提成
 * @property string $third 三级提成
 * @property string $fourth 四级提成
 * @property string $fifth 五级提成
 * @property string $sixth 六级提成
 * @property string $cooperate_money 业务员配合费
 * @property string $cooperate_member_id 业合作人ID(多个ID以逗号分割)
 * @property string $total 总支出
 * @property string $real_income 本单实际收入
 */
class OrderBrokerage extends \yii\db\ActiveRecord
{
    public $create_at;
    public $create_at_end;
    public $order_price;
    public $order_code;
    public $man_name;
    public $man_mobile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_brokerage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'member_price', 'first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'cooperate_money', 'total', 'real_income'], 'integer'],
            [['member_name', 'cooperate_member_id'], 'string', 'max' => 50],
            [['member_mobile'], 'string', 'max' => 11],
            [['member_parent_list'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单编号',
            'member_name' => '姓名',
            'member_mobile' => '账号',
            'member_price' => '佣金',
            'first' => '上级提成（一级）',
            'second' => '上级提成（二级）',
            'third' => '上级提成（三级）',
            'fourth' => '上级提成（四级）',
            'fifth' => '上级提成（五级）',
            'sixth' => '上级提成（六级）',
            'cooperate_money' => '配合费',
            'cooperate_member_id' => 'Cooperate Member ID',
            'total' => '总支出',
            'real_income' => 'real_income',
        ];
    }

    /*
     * join with order table
     */
    public function getOrderInfo(){
        return $this->hasOne(Order::className(),['id'=>'order_id'])->select('id,order_price,deal_price,create_at,order_code,part_time_order');
    }

    //对象转数组
    public static function objtoarray($obj){
        if (is_object($obj)) {
            foreach ($obj as $key => $value) {
                $array[$key] = $value;
            }
        }
        else {
            $array = $obj;
        }
        return $array;
    }

    //数组转对象
    public static function arraytoobj($array){
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $obj->$key =$value;
            }
        }else {
            $obj = $array;
        }
        return $obj;
    }

    //业务人员等级
    public static function keytoji($key){
        if($key == 0){
            $jibie = '一级';
        }elseif($key == 1){
            $jibie = '二级';
        }elseif($key == 2){
            $jibie = '三级';
        }elseif($key == 3){
            $jibie = '四级';
        }elseif($key == 4){
            $jibie = '五级';
        }elseif($key == 5){
            $jibie = '六级';
        }
        return $jibie;
    }
}
