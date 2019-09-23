<?php

namespace cms\modules\schedules\models;

use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_order_throw_program_space".
 *
 * @property string $id
 * @property string $area_id 地区ID
 * @property string $advert_key 广告位标识
 * @property string $date 日期
 * @property int $total_time 总时长
 * @property string $space_time 每个频次的剩余时间(数据格式:50,50,50,50,50,50,50,50,50,50)
 */
class OrderThrowProgramSpace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_order_throw_program_space';
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
            [['area_id', 'advert_key', 'date', 'space_time'], 'required'],
            [['area_id', 'total_time'], 'integer'],
            [['date'], 'safe'],
            [['space_time'], 'string'],
            [['advert_key'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area_id' => 'Area ID',
            'advert_key' => 'Advert Key',
            'date' => 'Date',
            'total_time' => 'Total Time',
            'space_time' => 'Space Time',
        ];
    }


    /**
     * @param $area_id 地区id
     * @param $date    日期
     * @param $advert_key  广告标识
     * @return mixed|string
     * 返回每个频次的剩余时间
     */
    public static function getProgramSpaceTime($area_id,$date,$advert_key){
        $query=OrderThrowProgramSpace::find();
        $query->andWhere(['=','date',$date]);
        $query->andFilterWhere([
            'area_id' => $area_id,
            'advert_key' => $advert_key,
        ]);
        $ProgramSpaceAll=$query->asArray()->all();
        if($ProgramSpaceAll){
            foreach($ProgramSpaceAll as $k=>$v){
                $SpaceTime[]= $v['id'];
                $SpaceTime[]= $v['space_time'];
            }
            return $SpaceTime;
        }
        return '';
    }


}
