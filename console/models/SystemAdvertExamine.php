<?php

namespace console\models;

use common\libs\Redis;
use common\libs\ToolsClass;

class SystemAdvertExamine extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%system_advert_examine}}';
    }

    public function examineSuccess()
    {
        $examineModel = self::find()->where(['date' => date('Y-m-d',strtotime("+1 day"))])->orderBy('id desc')->limit(1)->asArray()->one();
        if (empty($examineModel)) {
            return false;
        }
        return $examineModel['examine_status'] == 1;
    }
}
