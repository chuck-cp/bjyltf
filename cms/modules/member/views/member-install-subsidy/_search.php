<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\MemberInstallSubsidySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-install-subsidy-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td class="date">
                    <p>日期选择</p>
                    <?=$form->field($model,'create_at')->textInput(['class'=>'form-control datepicker collection-width'])->label(false);?>
                </td>
                <td>
                    <p>安装人电话</p>
                    <?=$form->field($model,'mobile')->textInput(['class' => 'form-control collection-width'])->label(false) ;?>
                </td>
                <td>
                    <p>安装人姓名</p>
                    <?=$form->field($model,'name')->textInput(['class' => 'form-control collection-width'])->label(false) ;?>
                </td>
                <td>
                    <p>今日收入区间</p>
                    <?=$form->field($model,'income_price_at')->textInput(['class' => 'form-control collection-width','placeholder'=>'开始'])->label(false) ;?>

                </td>
                <td>
                    <p>.</p>
                    <?=$form->field($model,'income_price_end')->textInput(['class' => 'form-control collection-width','placeholder'=>'结束'])->label(false) ;?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>所属省</p>
                    <?php  echo $form->field($model, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control area'])->label(false) ?>
                </td>
                <td>
                    <p>所属市</p>
                    <?php  echo $form->field($model, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->province),['prompt'=>'全部','key'=>'city','class'=>'form-control area'])->label(false) ?>
                </td>
                <td>
                    <p>所属区</p>
                    <?php  echo $form->field($model, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->city),['prompt'=>'全部','key'=>'area','class'=>'form-control area'])->label(false) ?>
                </td>
                <td>
                    <p>所属街道</p>
                    <?php  echo $form->field($model, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->area),['prompt'=>'全部','key'=>'town','class'=>'form-control'])->label(false) ?>
                </td>
                <td colspan="2">
                    <br />
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>

                    <?=  html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
                </td>
            </tr>
        </table>
    </div>

    <?php ActiveForm::end(); ?>

</div>
