<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '提现验证配置';
$this->params['breadcrumbs'][] = $this->title;
$action = \Yii::$app->controller->action->id;
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="system-config-index">
    <ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
        <li <? if($action == 'bonus'):?>class="active"<?endif;?>>
            <?=Html::a('广告业务提成',['place'])?>
        </li>
        <li <? if($action == 'contact-shop-bonus'):?>class="active"<?endif;?>>
            <?=Html::a('联系店铺提成',['contact-shop-bonus'])?>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-5 yw">
            广告业务个人分成：
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'proportions')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
        <div class="col-md-3" style="line-height: 40px;">
            <span>%基数</span>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-5 yw">
            兼职广告业务个人分成：
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'proportions_part_time_business')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
        <div class="col-md-3" style="line-height: 40px;">
            <span>%基数</span>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-3 yw">
            广告业务上级分成：
        </div>
        <div class="col-md-2" style="line-height: 40px;">
            <span>占总金额6%的 </span>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'proportions_first')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
        <div class="col-md-3" style="line-height: 40px;">
            <span>%，为上级代理员提成占比。</span>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-3 yw">
            广告业务区域配合费提成占比：
        </div>
        <div class="col-md-2" style="line-height: 40px;">
            <span>占总金额6%的 </span>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cooperation')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
        <div class="col-md-3" style="line-height: 40px;">
            <span>%，为区域配合费提成占比。</span>
        </div>
    </div>
    <hr/>
    <div class="col-md-2" style="margin-left: 33%;margin-top: 15px;">
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>

</div>
<?php \yii\widgets\ActiveForm::end(); ?>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
    }
</style>