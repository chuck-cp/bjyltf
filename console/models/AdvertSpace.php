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
class AdvertSpace extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }
    public static function tableName()
    {
        return '{{%advert_space}}';
    }

}
