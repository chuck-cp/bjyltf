<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '程序员电话';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $('.keep').click(function(){
           var obj = $(this).parents('.row');
           var index = obj.index();
           var html = '';
            html = '<div class=\"col-md-3\"><div class=\"form-group field-systemconfig-manufactory required\"><input type=\"text\" id=\"systemconfig-manufactory\" class=\"form-control\" name=\"SystemConfig[programmer_phone][]\" value=\"\"><div class=\"help-block\"></div></div></div>';
            obj.prev().append(html);
    })
");
?>
<?php $form = ActiveForm::begin([
//    'action' => [''],
    'method' => 'post',
]); ?>
<div class="system-config-index">
    <div class="row">
        <div class="col-md-2 yw">
            手机：
        </div>
    </div>
    <div class="row">
        <?if($model->programmer_phone):?>
            <?foreach (explode(',', $model->programmer_phone) as $v):?>
                <div class="col-md-3">
                    <?= $form->field($model, 'programmer_phone[]')->textInput(['class'=>'form-control', 'value'=>$v])->label(false) ?>
                </div>
            <?endforeach;?>
        <?else:?>
            <div class="col-md-3">
                <?= $form->field($model, 'programmer_phone[]')->textInput(['class'=>'form-control'])->label(false) ?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::button('继续添加',['class' => 'btn btn-success keep']);?>
            <?= Html::submitButton('保存', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
        width: 80px;
    }
</style>