<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '业务合作人员配置';
$this->params['breadcrumbs'][] = '业务合作人员配置';
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
    <div class="system-config-index">
<!--        <div class="row">-->
<!--            <div class="col-md-2 yw">-->
<!--               业务员需要完成：-->
<!--            </div>-->
<!--            <div class="col-md-3">-->
<!--                --><?//= $form->field($model, 'coordinate_convert_url')->textInput(['class'=>'form-control'])->label(false) ?>
<!--            </div>-->
<!--            <span class="yw">家</span>-->
<!--        </div>-->
        <div class="row">
            <div class="col-md-2 yw">
                业务合作人员需要完成：
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'shop_number')->textInput()->label(false) ?>
            </div>
            <span class="yw">家</span>
        </div>
        <div class="row">
            <div class="col-md-2 yw">
                订单最大折扣：
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'order_maximum_discount')->textInput()->label(false) ?>
            </div>
            <span class="yw">折</span>
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