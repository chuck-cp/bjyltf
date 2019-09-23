<?php

namespace console\controllers;



use common\libs\ToolsClass;
use console\models\Sign;
use console\models\SignMaintain;
use console\models\SignMemberCount;
use yii\console\Controller;

class SignController extends Controller
{
    // 默认评价
    public function actionDefaultEvaluate()
    {
        ToolsClass::printLog("default_evaluate","开始执行");
        $signModel = new SignMaintain();
        $signModel->defaultEvaluate();
        ToolsClass::printLog("default_evaluate","开始执行");
    }

    // 统计人员签到数据
    public function actionCountSignData() {
        ToolsClass::printLog("count_sign_data","开始执行");
        $signModel = new Sign();
        $signModel->memberSignCount();
        ToolsClass::printLog("count_sign_data","开始执行");
    }

    // 初始化成员签到数据
    public function actionInitMemberSignData() {
        ToolsClass::printLog("init_member_sign_data","开始执行");
        $signModel = new SignMemberCount();
        $signModel->initSignData();
        ToolsClass::printLog("init_member_sign_data","开始执行");
    }
}
