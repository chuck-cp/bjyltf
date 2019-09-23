<?php

namespace console\models;


use common\libs\Redis;
use common\libs\ToolsClass;
use yii\db\ActiveRecord;

class SignMaintain extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_maintain}}';
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->create_at = date('Y-m-d');
        }
        return parent::beforeSave($insert);
    }

    // 默认评价
    public function defaultEvaluate()
    {
        $signData = SignMaintain::find()->joinWith('sign',false)->where(['evaluate' => 0, 'D' => date('Y-m-d')])->select('yl_sign.create_at,yl_sign_maintain.sign_id,yl_sign.id,yl_sign.team_id,yl_sign_maintain.id as maintain_id')->asArray()->all();
        if (empty($signData)) {
            ToolsClass::printLog("default_evaluate","no sign data");
            return false;
        }
        SignMaintain::updateAll(['evaluate' => 1,'evaluate_at' => ToolsClass::get_date_time()], ['id' => array_column($signData,'maintain_id')]);
        foreach ($signData as $sign) {
            ToolsClass::printLog("default_evaluate",$sign['maintain_id']);
            Redis::getInstance(1)->rpush('list_json_sign_evaluate_count',json_encode([
                'evaluate' => 1,
                'oldEvaluate' => 0,
                'create_at' => date('Y-m-d',strtotime($sign['create_at'])),
                'team_id' => $sign['team_id']
            ]));
        }
    }

    public function getSign()
    {
        return $this->hasOne(Sign::className(),['id' => 'sign_id'])->select('id,team_id');
    }
}
