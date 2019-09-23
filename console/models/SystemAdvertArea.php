<?php

namespace console\models;

use common\libs\Redis;
use common\libs\ToolsClass;

class SystemAdvertArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_advert_area}}';
    }

}
