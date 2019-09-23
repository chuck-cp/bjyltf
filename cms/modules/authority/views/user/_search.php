<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\authority\models\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'username')->textInput(['class'=>'form-control fm'])->label('用户名');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'true_name')->textInput(['class'=>'form-control fm'])->label('姓名');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>&emsp;&emsp;&emsp;
            <?= Html::a('添加', 'javascript:void(0);', ['class' => 'btn btn-success Createuser']) ?>
            <?/*=Html::submitButton('导出',['class'=>'btn btn-primary'])*/?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
