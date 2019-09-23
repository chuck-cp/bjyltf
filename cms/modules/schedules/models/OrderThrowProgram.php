<?php

namespace cms\modules\schedules\models;

use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "{{%order_throw_program}}".
 *
 * @property string $id
 * @property string $area_id 节目单地区ID
 * @property string $advert_key 广告标识
 * @property string $date 投放日期
 */
class OrderThrowProgram extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }

    public $startat;
    public $endat;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_throw_program}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'advert_key', 'date'], 'required'],
            [['area_id'], 'integer'],
            [['date','startat','endat'], 'safe'],
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
        ];
    }

    /**
     * 判断节目单是否提交
     */
    public static function getCountProgram($areaid,$advert_key){
        $query=OrderThrowProgram::find();
        $strdate=date('Y-m-d',strtotime('+1 day'));
        $enddate=date('Y-m-d',strtotime('+6 day'));
        $query->andFilterWhere(['like', 'area_id', $areaid]);
        $query->andWhere(['>=','date',$strdate.' 00:00:00']);
        $query->andWhere(['<=','date',$enddate.' 23:59:59']);
        if($advert_key=='a'){
            $query->andWhere(['in','advert_key',['A1','A2']]);
        }else if($advert_key=='b'){
            $query->andWhere(['in','advert_key',['B']]);
        }else if($advert_key=='c'){
            $query->andWhere(['in','advert_key',['C','CD']]);
        }else if($advert_key=='d'){
            $query->andWhere(['in','advert_key',['D','CD']]);
        }
        else{
            $query->andFilterWhere([
                'advert_key' => $advert_key,
            ]);
        }
        /*$query->andFilterWhere([
            'is_push' =>0
        ]);*/
        /*$query->asArray()->count();
        $commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();
        echo "<br />";asdadsad*/
        if($query->asArray()->count()==0){
            return '';
        }else{
            return '未提交';
        }
    }

    /**
     * 获取投放数据
     */
    public static function getProgramAll($area_id,$date,$advert_key){
        $query=OrderThrowProgram::find();
        $query->andWhere(['<=','date',$date]);
        $query->andWhere(['>=','end_date',$date]);
        if($advert_key=='a'){
            $query->andWhere(['in','advert_key',['A1','A2']]);
        }else if($advert_key=='b'){
            $query->andWhere(['in','advert_key',['B']]);
        }else if($advert_key=='c'){
            $query->andWhere(['in','advert_key',['C','CD']]);
        }else if($advert_key=='d'){
            $query->andWhere(['in','advert_key',['D','CD']]);
        }else if($advert_key=='cd'){
            $query->andWhere(['in','advert_key',['CD']]);
        }
        $query->andFilterWhere([
            'area_id' => $area_id,
        ]);
        $ProgramAll=$query->asArray()->all();
        /*$commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();*/
        if(!empty($ProgramAll)){
            foreach($ProgramAll as $k=>$v){
                if(!empty($v)){
                    $data[]=$v['id'];
                }
            }
            if(!empty($data))
                return implode(",",$data);
            return '';
        }else{
            return'';
        }
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










