<?php

namespace console\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * 获取配置文件
 */
class SystemConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_config}}';
    }

    public static function getAllConfigById($id){
        $configModel = self::find()->where(['id'=>$id])->select('id,content')->asArray()->all();
        if(empty($configModel)){
            return [];
        }
        return ArrayHelper::map($configModel,'id','content');
    }

    public static function getConfig($id){
        $configModel = self::find()->where(['id'=>$id])->select('content')->asArray()->one();
        if(!empty($configModel)){
            return $configModel['content'];
        }
    }
}
