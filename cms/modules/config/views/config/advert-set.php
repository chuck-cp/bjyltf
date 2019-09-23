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
            <input <?if($model->advert_advance_upload_time_set==2):?>checked<?endif;?> type="checkbox" name="advert_advance_upload_time_set"  value="2" >广告提前上传时长：
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'advert_advance_upload_time')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-2 yw">
            <input <?if($model->advert_timing_push_time_set==2):?>checked<?endif;?> type="checkbox" name="advert_timing_push_time_set"  value="2" >广告定时推送时长：
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'advert_timing_push_time')->textInput(['class'=>'form-control'])->label(false) ?>
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