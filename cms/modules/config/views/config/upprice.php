<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '修改资金';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([]); ?>
<div class="row">
    <div class="col-xs-2 form-group">
        <label>加减：</label>
        <select class="form-control fm" name="type">
            <option value="1">加钱</option>
            <option value="2">减钱</option>
        </select>
    </div><div class="col-xs-2 form-group">
        <label>人员ID：</label>
        <input type="text" class="form-control fm" name="member_id" value="" placeholder="人员ID"/>
    </div>
    <div class="col-xs-2 form-group">
        <label>金额：</label>
        <input type="text" class="form-control fm" name="price" value="" placeholder="增加/减少的金额"/>
    </div>
    <div class="col-xs-2 form-group">
        <label>业务类型：</label>
        <select class="form-control fm" name="account_type">
            <option value="-1">提现申请</option>
            <option value="1">安装业务</option>
            <option value="2">广告业务</option>
            <option value="3">提现失败退款</option>
            <option value="4">广告订单奖励金</option>
            <option value="5">安装人补贴费用</option>
            <option value="6">设备维护费</option>
            <option value="7">系统扣款</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-xs-2 form-group">
        <label>标题：</label>
        <input type="text" class="form-control fm" name="title" value="" placeholder="xxx费用"/>
    </div>
    <div class="col-xs-2 form-group">
        <label>描述：</label>
        <input type="text" class="form-control fm" name="desc" value="" placeholder="例：XXXX店铺"/>
    </div><div class="col-xs-2 form-group">
        <label>msg描述：</label>
        <input type="text" class="form-control fm" name="message_title" value=""placeholder="例：收入安装联系费100.00元"/>
    </div>
    <div class="col-xs-2 form-group">
        <?=Html::submitButton('提交',['class'=>'btn btn-primary'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<div class="system-config-querys">
    <h5>结果：</h5>
    <p>yl_log_account</p><?=Html::encode($reslog)?>
    </br>
    <p>yl_member_account</p><?=Html::encode($resMA)?>
    </br>
    <p>yl_member_account_count</p><?=Html::encode($resMAC)?>
    </br>
    <p>yl_member_account_message</p><?=Html::encode($resmsg)?>
</div>
<style type="text/css">
 .fm{width: 150px;}
</style>