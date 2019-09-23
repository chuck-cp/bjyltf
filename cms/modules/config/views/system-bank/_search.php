<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\search\SystemBankSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-bank-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'bank_name')->textInput(['class'=>'form-control fm'])->label('银行名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search'])?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
