<?php

namespace console\models;

use api\modules\v1\core\ApiActiveRecord;
use cms\models\SystemAddress;
use common\libs\ToolsClass;
use console\models\SystemConfig;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * 广告剩余量统计
 */
class OrderThrowProgramScreenCount extends ActiveRecord
{

    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }
    private static $partitionIndex_ = null; // 分表ID
    /**
     * 重置分区id
     * @param unknown $order_id
     */
    private static function resetPartitionIndex($order_id = null) {
        // $partitionCount = 2000;

        self::$partitionIndex_ = ceil($order_id/2000);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_throw_program_screen_count_'.self::$partitionIndex_.'}}';
    }
    public function findtrue($order_id,$software_number){
        self::resetPartitionIndex($order_id);
       $data=self::find()->where(['and',['order_id'=>$order_id],['software_number'=>$software_number]])->select('area_id,date')->asArray()->one();
        if(!empty($data)){
          return true;
        }
        return false;
    }



}
