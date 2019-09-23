<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-23
 * Time: 13:30
 */

namespace cms\models;

use Yii;

/**
 * This is the model class for table "{{%order_message}}".
 *
 * @property string $id 主键
 * @property int $order_id yl_order 的主键
 * @property int $type 类型 1 ： 付款状态  2：投放状态
 * @property string $desc 操作说明
 * @property string $reject_reason 驳回原因
 * @property string $create_at 添加时间
 */
class OrderPlayPresentation extends \yii\db\ActiveRecord{
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_play_presentation}}';
    }

    /*
     * 入参：id,field
     * 返回：field值
     */
    public static function getFieldValue($id, $field){
        if(!$id || $field){return 0;}
        $res = self::find()->where(['order_id'=>$id])->select($field)->asArray()->one;
        $re = isset($res[$field]) ? $res[$field] : 0;
        return $re;
    }
}