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
            客服电话：
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'service_phone')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-2 yw">
            邮箱：
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'e_mail')->textInput(['class'=>'form-control'])->label(false) ?>
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