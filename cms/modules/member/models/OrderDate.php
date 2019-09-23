<?php

namespace cms\modules\member\models;

use cms\models\OrderMessage;
use cms\models\AdvertPosition;
use cms\models\SystemAddress;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order_date}}".
 *
 * @property string $id
 * @property string $order_id 关联yl_order表的id
 * @property string $start_at 开始时间
 * @property string $end_at 结束时间
 * @property int $is_update 是否可以修改(每次修改+1,等于3时不可再修改)
 */
class OrderDate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_date}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'start_at', 'end_at'], 'required'],
            [['order_id', 'is_update'], 'integer'],
            [['start_at', 'end_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'is_update' => 'Is Update',
        ];
    }
    /*
    * 获取某广告日期
    */
    public static function getOrderDate($aid){
        $dateObj = self::findOne(['order_id'=>$aid]);
        if($dateObj){
            return $dateObj->getAttribute('start_at').'至'.$dateObj->getAttribute('end_at');
        }
        return '---';
    }

    /*
    * 获取某广告日期分开
    */
    public static function getOrderDateList($Oid){
        $dateObj = self::findOne(['order_id'=>$Oid]);
        if($dateObj){
            return [$dateObj->getAttribute('start_at'),$dateObj->getAttribute('end_at')];
        }
        return '---';
    }

    /*
   * 获取某广告连续投放日期
   */
    public static function getOrderDateSeries($Oid){
        $dateArray=self::find()->select('id,order_id,start_at,end_at')->where(['order_id'=>$Oid])->asArray()->one();
        $begin = $dateArray['start_at'];
        $end = $dateArray['end_at'];
        $begintime = strtotime($begin);
        $endtime = strtotime($end);
        for ($start = $begintime; $start <= $endtime; $start += 24 * 3600) {
            $datelist[] = date("Y-m-d", $start);
        }
        return $datelist;
    }

    /**
     * 获取投放日期
     */
    public static function getDeliveryDate($aid, $start = true){
        $dateObj = self::findOne(['order_id'=>$aid]);
        if($dateObj){
            return $start == true ? $dateObj->getAttribute('start_at') : $dateObj->getAttribute('end_at');
        }
        return '---';
    }

    //求两个日期之间相差的天数
    public static function diffBetweenTwoDays($day1, $day2){
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);
        if($second1 < $second2){
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400 + 1;
    }

    /*
     *修改广告投放时间
     * */
    public static function checkDateTime($orderarray){
        set_time_limit(0);
        $order_list = self::find()->joinWith('order',$eagerLoading = false)->where(['yl_order_date.order_id'=>$orderarray['orderid']])->select('yl_order.member_id,yl_order.total_day,yl_order.payment_status,yl_order.overdue_at, yl_order.advert_key, yl_order.number, yl_order.advert_time, yl_order.advert_id,yl_order_date.start_at,yl_order_date.end_at,yl_order_date.is_update')->asArray()->one();

        //查询订单现在的状态
        $lock = Order::find()->where(['id'=>$orderarray['orderid']])->select('lock')->asArray()->one();
        if(isset($lock['lock']) && $lock['lock'] > 0){
            return ['ORDER_LOCKED',0];
        }
        //不允许修改/已修改3次
        if(!$order_list || $order_list['is_update'] > 2){
            return ['ORDER_NOT_ALLOWED_MODIFY',0];
        }

        //未付款
        if($order_list['payment_status']<1){
            return ['ORDER_UNPAID',0];
        }

        //判断时间是否有修改,若没有修改返回
        if($order_list['start_at'] == $orderarray['start_at'] || $order_list['end_at'] == $orderarray['end_at']){
            return ['ORDER_NO_MODIFY',0];
        }

        //判断天数是否一致
        $new_days = ToolsClass::timediffunit($orderarray['end_at'], $orderarray['start_at']);
        $old_days = ToolsClass::timediffunit($order_list['end_at'], $order_list['start_at']);
        if($new_days !== $old_days){
            return ['ORDER_TOTAL_DAYS_NOT_SAME',0];
        }

        //判断修改后的日期是否在现在的时间+15天之后
        $update_days = ToolsClass::timediffunit(date('Y-m-d'),$orderarray['start_at']);
        if($update_days <= 15){
            return ['ORDER_DATE_NOT_ALLOWED',0];
        }

        if(!empty($orderarray['start_at']) && !empty($orderarray['end_at']) && $orderarray['end_at'] > $orderarray['start_at']){
            //修改订单状态
            $re = Order::updateAll(array('lock'=>2), 'id='.$orderarray['orderid']);
            //向redis 里写入具体被修改的日期
            $orderAreaModel = OrderArea::find()->where(['order_id'=>$orderarray['orderid']])->one();
            //若原订单下没有街道可买
            if(!$orderAreaModel->street_area){
                $areaArr = ToolsClass::explode(',',$orderAreaModel->area_id);
                $len = strlen($areaArr[0]);
                if($len == 12){
                    $orderAreaModel->street_area = $orderAreaModel->area_id;
                }else {
                    $newArr = SystemAddress::find()->where(['in', 'left(id,5)', $areaArr])->andWhere(['is_buy' => 1, 'level' => 6])->select('id')->asArray()->all();
                    if (empty($newArr)) {
                        return ['ERROR', 0];
                    }
                    $orderAreaModel->street_area = implode(',', ArrayHelper::getColumn($newArr, 'id'));
                }
                $orderAreaModel->save();
            }
            if($orderarray['start_at'] > $order_list['start_at'] && $orderarray['start_at'] <= $order_list['end_at']){
                $add_begin = date('Y-m-d',strtotime($order_list['end_at'])+86400);
                $add_end = $orderarray['end_at'];
                $delete_date = $order_list['start_at'].','.date('Y-m-d',strtotime("-1 day ".$orderarray['start_at']));
            }elseif($orderarray['end_at'] >= $order_list['start_at'] && $orderarray['end_at'] < $order_list['end_at']){
                $add_begin = $orderarray['start_at'];
                $add_end = date('Y-m-d',strtotime("-1 day ".$order_list['start_at']));
                $delete_date = date('Y-m-d',strtotime("+1 day ".$orderarray['end_at'])).','.$order_list['end_at'];
            }elseif($orderarray['end_at'] < $order_list['start_at'] || $orderarray['start_at'] > $order_list['end_at']){
                $add_begin = $orderarray['start_at'];
                $add_end = $orderarray['end_at'];
                $delete_date = $order_list['start_at'].','.$order_list['end_at'];
            }

            $task_number = ToolsClass::timediffunit($add_begin,$add_end)+1;
            $postionInfo = AdvertPosition::findOne(['id'=>$order_list['advert_id']]);
            RedisClass::rpush("system_create_order_list",json_encode([
                'token'=>md5("wwwbjyltfcom{$order_list['advert_time']}{$order_list['advert_key']}{$order_list['number']}{$order_list['member_id']}"),
                'overdue_at'=>date('Y-m-d',strtotime('-7 day '.$orderarray['start_at'])),
                'order_id'=>$orderarray['orderid'],
                'advert_key'=>strtolower($order_list['advert_key']),
                'rate'=>$order_list['number'],
                'start_at'=>$add_begin,
                'end_at'=>$add_end,
                'delete_date'=>$delete_date,
                'area_id'=>$orderAreaModel->street_area,
                'total_day'=>$order_list['total_day'],
                'advert_time'=>$order_list['advert_time'],
                'task_number'=>$task_number,
                'bind'=>$postionInfo->bind,
                'group'=>$postionInfo->group,
                'type'=>'update_order'
            ]),4);

            while (true){
                $result = RedisClass::get('result_update_order:'.$orderarray['orderid'],4);
                if($result == 1){
                    RedisClass::del('result_update_order:'.$orderarray['orderid'],4);
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        //用户消息
                        OrderMessage::Log($orderarray['orderid'],"投放时间更改为".$orderarray['start_at']);
                        self::updateAll(array('start_at'=>$orderarray['start_at'],'end_at'=>$orderarray['end_at'],'is_update'=>++$order_list['is_update']),'order_id='.$orderarray['orderid']);
                        $transaction->commit();
                        return ['SUCCESS',(string)(3-$order_list['is_update'])];
                    }catch (\Exception $e){
                        $transaction->rollBack();
                        return ['ERROR',0];
                    }
                }elseif($result == 2){
                    RedisClass::del('result_update_order:'.$orderarray['orderid'],4);
                    return ['ERROR',0];
                }
                sleep(1);
            }
        }
        return ['ERROR',0];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id'])->select('yl_order.id');
    }

    public static function getCommonStatus($key){
        $data = self::defaultTimeList();
        if(isset($data[$key])){
            return $data[$key];
        }
    }

    public static function defaultTimeList(){
        return [
            'a1'=>[60,120,150,180,240,300],
            'a2'=>[5,10,15,20,25,30,60],
            'b'=>[30],
            'c'=>[60,90],
            'd'=>[60,90],
            'cd'=>[60,90],
        ];
    }

    public static function prDates($startat,$endat){
        $dt_start = strtotime($startat);
        $dt_end = strtotime($endat);
        while ($dt_start<=$dt_end){
            $date[]=date('Y-m-d',$dt_start);
            $dt_start = strtotime('+1 day',$dt_start);
        }
        return $date;
    }
}
