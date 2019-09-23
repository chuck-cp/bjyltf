<?php

namespace cms\modules\schedules\models;

use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_order_throw_program_space_list".
 *
 * @property string $id
 * @property string $spece_id 和order_throw_program_space表ID关联
 * @property string $order_id 订单ID
 * @property int $advert_time 广告时长
 * @property string $position 广告存放位置
 */
class OrderThrowProgramSpaceList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_order_throw_program_space_list';
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
            [['spece_id', 'order_id', 'advert_time'], 'integer'],
            [['position'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'spece_id' => 'Spece ID',
            'order_id' => 'Order ID',
            'advert_time' => 'Advert Time',
            'position' => 'Position',
        ];
    }

    public static function getProgramSpaceListId($spece_id){
        $query=OrderThrowProgramSpaceList::find();
        $query->andFilterWhere([
            'space_id' => $spece_id,
        ]);
        $ProgramSpaceListAll=$query->asArray()->all();
        if($ProgramSpaceListAll){
            foreach($ProgramSpaceListAll as $k=>$v){
                $OrderIdAll[]=$v['order_id'];
            }
            return implode(",",$OrderIdAll);
        }else{
            return '';
        }
    }

    /**
     * @param $spece_id
     * @return string
     * 查询C D CD
     */
    public static function getProgramSpaceListIdIn($spece_id){
       // ToolsClass::p($spece_id);
        $query=OrderThrowProgramSpaceList::find();
        $query->andWhere(['in','space_id',$spece_id]);
        $ProgramSpaceListAll=$query->asArray()->all();
        if($ProgramSpaceListAll){
            foreach($ProgramSpaceListAll as $k=>$v){
                $OrderIdAll[]=$v['order_id'];
            }
            return implode(",",$OrderIdAll);
        }else{
            return '';
        }
    }

    /*
     * 历史排期订单id
     */
    public static function getProgramSpaceListIdali($i,$spece_id){
        $query=OrderThrowProgramSpaceList::find();
        $query->andFilterWhere([
            'space_id' => $spece_id,
        ]);
        $ProgramSpaceListAll=$query->asArray()->all();
        if($ProgramSpaceListAll){
            foreach($ProgramSpaceListAll as $k=>$v){
                $position=explode(',',$v['position']);
                for($j=0;$j<10;$j++){
                    if($position[$j]==1){
                        $cc[$j][]=$v['order_id'];
                    }
                }
                //$OrderIdAll[]=$v['order_id'];
            }
            $count=count($cc);
            if($i<$count){
                return implode(",",$cc[$i]);
            }
        }else{
            return '';
        }
    }
}
