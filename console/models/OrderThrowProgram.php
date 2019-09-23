<?php

namespace console\models;

use cms\modules\member\models\OrderThrow;
use cms\modules\member\models\OrderThrowList;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "{{%order_throw_program}}".
 *
 * @property integer $id
 * @property integer $throw_id
 * @property string $date
 */
class OrderThrowProgram extends \yii\db\ActiveRecord
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
        return '{{%order_throw_program}}';
    }

    /*
     * 创建节目单
     * @param int area_id 投放地区id
     * @param int  advert_key 广告类型
     * @param start_at date 投放日期
     */
    public static function createProgram($area_id,$advert_key,$date){
        try{
            //节目单变更的日期记录
            if(!$programModel = self::find()->where(['area_id' => $area_id,'advert_key' => $advert_key,'date' => $date])->one()) {
                $programModel = new OrderThrowProgram();
                $programModel->area_id = $area_id;
                $programModel->advert_key = $advert_key;
                $programModel->date = $date;
                $programModel->end_date = empty($throwModel) ? $date : min(array_column($throwModel, 'end_at'));
                $programModel->save();
            }
            $throwModel = OrderThrowDate::find()->where(['and', ['area_id' => $area_id], ['advert_key' => $advert_key], ['<=', 'start_at', $date], ['>=', 'end_at', $date]])->select('id,start_at,end_at,area_id,advert_key,order_id,advert_time,resource,resource_attribute,payment_at')->asArray()->all();
            //节目单详细内容，如果为空 就是下架所有的广告
            if(!empty($throwModel)){
                foreach($throwModel as $key=>$value){
                    $programList = new OrderThrowProgramList();
                    $programList->program_id = $programModel->id;
                    $programList->order_id = $value['order_id'];
                    $programList->advert_time = $value['advert_time'];
                    $programList->resource = $value['resource'];
                    $programList->resource_attribute = $value['resource_attribute'];
                    $programList->payment_at = $value['payment_at'];
                    $programList->save();
                }
            }
            return true;
        }catch (Exception $e){
            Yii::error('[create_program]'.$e->getMessage());
            return false;
        }
    }
}
