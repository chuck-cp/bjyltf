<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '安装价格配置';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="system-config-index">
    <div class="row">
    <div class="rowleft" style="display: grid;float: left;width: 45%;">
        <div class="col-md-2 yw">
            外部电工费用
        </div>
        <div style="overflow:hidden;">
        <div class="col-md-2 yw" style="float: left">
            一级区域：
        </div>
        <div class="col-md-3" style="float: left">
            <?= $form->field($model, 'system_price_install_2_1')->textInput(['value'=>$model->system_price_install_2_1/100,'class'=>'form-control'])->label('安装屏幕') ?>
            <?= $form->field($model, 'system_price_replace_2_1')->textInput(['value'=>$model->system_price_replace_2_1/100,'class'=>'form-control'])->label('更换屏幕') ?>
            <?= $form->field($model, 'system_price_remove_2_1')->textInput(['value'=>$model->system_price_remove_2_1/100,'class'=>'form-control'])->label('拆除屏幕') ?>
        </div>
        </div>
        <div style="overflow:hidden;">
        <div class="col-md-2 yw" style="float: left">
            二级区域：
        </div>
        <div class="col-md-3" style="float: left">
            <?= $form->field($model, 'system_price_install_2_2')->textInput(['value'=>$model->system_price_install_2_2/100,'class'=>'form-control'])->label('安装屏幕') ?>
            <?= $form->field($model, 'system_price_replace_2_2')->textInput(['value'=>$model->system_price_replace_2_2/100,'class'=>'form-control'])->label('更换屏幕') ?>
            <?= $form->field($model, 'system_price_remove_2_2')->textInput(['value'=>$model->system_price_remove_2_2/100,'class'=>'form-control'])->label('拆除屏幕') ?>
        </div>
            </div>
        <div style="overflow:hidden;">
        <div class="col-md-2 yw" style="float: left">
            三级区域：
        </div>
        <div class="col-md-3" style="float: left">
            <?= $form->field($model, 'system_price_install_2_3')->textInput(['value'=>$model->system_price_install_2_3/100,'class'=>'form-control'])->label('安装屏幕') ?>
            <?= $form->field($model, 'system_price_replace_2_3')->textInput(['value'=>$model->system_price_replace_2_3/100,'class'=>'form-control'])->label('更换屏幕') ?>
            <?= $form->field($model, 'system_price_remove_2_3')->textInput(['value'=>$model->system_price_remove_2_3/100,'class'=>'form-control'])->label('拆除屏幕') ?>
        </div>
            </div>
        </div>
    <div class="rowright" style="display: grid;float: right;width: 45%;">
        <div class="col-md-2 yw">
            内部电工费用
        </div>
        <div style="overflow: hidden">
        <div class="col-md-2 yw" style="float: left">
            一级区域：
        </div>
        <div class="col-md-3" style="float: left">
            <?= $form->field($model, 'system_price_install_1_1')->textInput(['value'=>$model->system_price_install_1_1/100,'class'=>'form-control'])->label('安装屏幕') ?>
            <?= $form->field($model, 'system_price_replace_1_1')->textInput(['value'=>$model->system_price_replace_1_1/100,'class'=>'form-control'])->label('更换屏幕') ?>
            <?= $form->field($model, 'system_price_remove_1_1')->textInput(['value'=>$model->system_price_remove_1_1/100,'class'=>'form-control'])->label('拆除屏幕') ?>
        </div>
        </div>
        <div style="overflow: hidden">
        <div class="col-md-2 yw" style="float: left">
            二级区域：
        </div>
        <div class="col-md-3" style="float: left">
            <?= $form->field($model, 'system_price_install_1_2')->textInput(['value'=>$model->system_price_install_1_2/100,'class'=>'form-control'])->label('安装屏幕') ?>
            <?= $form->field($model, 'system_price_replace_1_2')->textInput(['value'=>$model->system_price_replace_1_2/100,'class'=>'form-control'])->label('更换屏幕') ?>
            <?= $form->field($model, 'system_price_remove_1_2')->textInput(['value'=>$model->system_price_remove_1_2/100,'class'=>'form-control'])->label('拆除屏幕') ?>
        </div>
        </div>
        <div style="overflow: hidden">
        <div class="col-md-2 yw" style="float: left">
            三级区域：
        </div>
        <div class="col-md-3" style="float: left">
            <?= $form->field($model, 'system_price_install_1_3')->textInput(['value'=>$model->system_price_install_1_3/100,'class'=>'form-control'])->label('安装屏幕') ?>
            <?= $form->field($model, 'system_price_replace_1_3')->textInput(['value'=>$model->system_price_replace_1_3/100,'class'=>'form-control'])->label('更换屏幕') ?>
            <?= $form->field($model, 'system_price_remove_1_3')->textInput(['value'=>$model->system_price_remove_1_3/100,'class'=>'form-control'])->label('拆除屏幕') ?>
        </div>
            </div>
    </div>
    </div>
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
</div>
<?php \yii\widgets\ActiveForm::end(); ?>
<style type="text/css">
    /*.yw{*/
        /*line-height: 35px;*/
        /*font-size: 14px;*/
        /*font-weight: 700;*/
    /*}*/
</style>