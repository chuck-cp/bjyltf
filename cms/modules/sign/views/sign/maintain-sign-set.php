<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客服电话';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="system-config-index">
    <div class="row">
        <div class="col-md-2 yw">
            签到地图微调距离：（米）
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'maintain_trimming_distance')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 yw">
            默认首次签到时间：
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'maintain_first_check_time')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 yw">
            默认签到间隔时间：（分钟）
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'maintain_check_interval_time')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 yw">
            维护每人每日签到数：（次）
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'maintain_day_sign_number')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 yw">
            默认最早下班时间：
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'maintain_earliest_closing_time')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
</div>
<?php \yii\widgets\ActiveForm::end(); ?>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
    }
</style>