<?php

namespace console\models;

use api\modules\v1\models\tb\TbInfoReference;
use Yii;
use yii\base\Exception;
use yii\web\IdentityInterface;

/**
 * member表的model
 */
class Member extends \yii\db\ActiveRecord
{
       /**
     * 表名
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /*
     * 获取我的某个字段
     * */
    public static function getMemberFieldByWhere($where,$field){
        $memberModel = Member::find()->where($where)->select($field)->asArray()->one();
        if(!empty($memberModel)){
            return $memberModel[$field];
        }
    }
}
