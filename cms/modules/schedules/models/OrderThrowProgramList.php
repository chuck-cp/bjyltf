<?php

namespace cms\modules\schedules\models;

use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "{{%order_throw_program_list}}".
 *
 * @property string $id
 * @property string $program_id 和order_throw_program表ID关联
 * @property string $order_id 订单ID
 * @property int $start_at 开始时间
 * @property int $end_at 结束时间
 * @property int $total_time 总时长
 * @property int $batch 批次
 */
class OrderThrowProgramList extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_throw_program_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'order_id', 'start_at', 'end_at', 'total_time', 'batch'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'program_id' => 'Program ID',
            'order_id' => 'Order ID',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'total_time' => 'Total Time',
            'batch' => 'Batch',
        ];
    }

    /**
     * 根据条件统计时长
     */
    public static function getTotalTimeSum($program_id,$batch){
        $query=OrderThrowProgramList::find();
        $query->andWhere(['in','program_id',explode(',',$program_id)]);
        $query->andFilterWhere([
            'batch' => $batch,
        ]);
        if($program_id && $batch){
            $sum=$query->sum('advert_time');
            return $sum?$sum:'';
        }else{
            return'';
        }
    }
    /**
     * 根据条件获取orderid
     */
    public static function getOrderId($program_id,$batch,$advert_key){
        $query=OrderThrowProgramList::find();
        if($advert_key=='a'){
            $query->andWhere(['in','program_id',explode(',',$program_id)]);
            $query->andFilterWhere([
                'batch' => $batch,
            ]);
        }else{
            $query->andWhere(['in','program_id',explode(',',$program_id)]);
        }
        if($program_id && $batch){
            $ProgramListAll=$query->asArray()->all();
            foreach($ProgramListAll as $k=>$v){
                if(!empty($v)){
                    $data[]=$v['order_id'];
                }
            }
            if(!empty($data))
                return implode(",",$data);
            return '';
        }else{
            return '';
        }
    }

    /**
     * 根据条件获取B C D屏广告数量
     */
    public static function getCount($program_id){
        $query=OrderThrowProgramList::find();
        $query->andWhere(['in','program_id',explode(',',$program_id)]);
        /*$query->andFilterWhere([
            'program_id' => $program_id,
        ]);*/
        if($program_id){
            return $query->count();
        }else{
            return 0;
        }
    }
}
