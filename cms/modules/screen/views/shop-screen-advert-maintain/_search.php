<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\screen\models\search\ShopScreenAdvertMaintainSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-screen-advert-maintain-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>商家编号</p>
                    <?=$form->field($searchModel,'shop_id')->textInput(['placeholder'=>'商家编号','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>商家名称</p>
                    <?=$form->field($searchModel,'shop_name')->textInput(['placeholder'=>'商家名称','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>维护状态</p>
                    <?=$form->field($searchModel,'status')->dropDownList(['0'=>'待指派','1'=>'已指派','2'=>'维护完成'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>指派电工</p>
                    <?=$form->field($searchModel,'install_member_name')->textInput(['placeholder'=>'电工名称','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>下发日期</p>
                    <?= $form->field($searchModel, 'create_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                </td>
                <td>
                    <p>.</p>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>所属省</p>
                    <?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属市</p>
                    <?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属区</p>
                    <?php  echo $form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属街道</p>
                    <?php  echo $form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label(false) ?>
                </td>

                <td style="padding-top: 30px;">
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end(); ?>
</div>
