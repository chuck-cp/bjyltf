<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\search\ShopAbnormalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-abnormal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>店铺名称</p>
                    <?= $form->field($searchModel, 'shop_name')->textInput(['class'=>'form-control datepicker fm'])->label(false); ?>
                </td>
                <td>
                    <p>状态</p>
                    <?php  echo $form->field($searchModel, 'status')->dropDownList(['1'=>'已处理','0'=>'未处理'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>开始时间</p>
                    <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label(false); ?>

                </td>
                <td>
                    <p>结束时间</p>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
                </td>

                <td>
                    <p>.</p>
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                </td>
            </tr>
        </table>
    </div>

    <?php ActiveForm::end(); ?>

</div>
