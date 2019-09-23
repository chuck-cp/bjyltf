<?php

namespace console\models;


use yii\db\ActiveRecord;

class SignBusiness extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_business}}';
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->create_at = date('Y-m-d');
        }
        return parent::beforeSave($insert);
    }
}
