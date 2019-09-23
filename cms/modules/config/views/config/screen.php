<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '屏幕配置';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $('.keep').click(function(){
           var obj = $(this).parents('.row');
           var index = obj.index();
           alert(index);
           var html = '';
               if(index == 3){
                    html = '<div class=\"col-md-3\"><div class=\"form-group field-systemconfig-manufactory required\"><input type=\"text\" id=\"systemconfig-manufactory\" class=\"form-control\" name=\"SystemConfig[manufactory][]\" value=\"\"><div class=\"help-block\"></div></div></div>';
               }else if(index == 7){
                    html = '<div class=\"col-md-3\"><div class=\"form-group field-systemconfig-led_spec required\"><input type=\"text\" id=\"systemconfig-led_spec\" class=\"form-control\" name=\"SystemConfig[led_spec][]\" value=\"\"><div class=\"help-block\"></div></div></div>';
               }else if(index == 12){
                    html = '<div class=\"col-md-3\"><div class=\"form-group field-systemconfig-frame_device_manufactor required\"><input type=\"text\" id=\"systemconfig-frame_device_manufactor\" class=\"form-control\" name=\"SystemConfig[frame_device_manufactor][]\" value=\"\"><div class=\"help-block\"></div></div></div>';
               }else if(index == 16){
                    html = '<div class=\"col-md-3\"><div class=\"form-group field-systemconfig-frame_device_size\"><input type=\"text\" id=\"systemconfig-frame_device_size\" class=\"form-control\" name=\"SystemConfig[frame_device_size][]\" value=\"\"><div class=\"help-block\"></div></div></div>';
               }else if(index == 20){
                    html = '<div class=\"col-md-3\"><div class=\"form-group field-systemconfig-frame_device_material\"><input type=\"text\" id=\"systemconfig-frame_device_material\" class=\"form-control\" name=\"SystemConfig[frame_device_material][]\" value=\"\"><div class=\"help-block\"></div></div></div>';
               }else if(index == 24){
                    html = '<div class=\"col-md-3\"><div class=\"form-group field-systemconfig-frame_device_level\"><input type=\"text\" id=\"systemconfig-frame_device_level\" class=\"form-control\" name=\"SystemConfig[frame_device_level][]\" value=\"\"><div class=\"help-block\"></div></div></div>';
               }
               obj.prev().append(html);
    })
");
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="system-config-index">
    <H3><b>LED配置</b></H3>
    <div class="row">
        <div class="col-md-2 yw">
            LED厂家名称：
        </div>
    </div>
    <div class="row">
        <?if($model->manufactory):?>
            <?foreach (explode(',', $model->manufactory) as $v):?>
                <div class="col-md-3">
                    <?= $form->field($model, 'manufactory[]')->textInput(['class'=>'form-control', 'value'=>$v])->label(false) ?>
                </div>
            <?endforeach;?>
        <?else:?>
            <div class="col-md-3">
                <?= $form->field($model, 'manufactory[]')->textInput(['class'=>'form-control'])->label(false) ?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::button('继续添加',['class' => 'btn btn-success keep']);?>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-2 yw">
            LED设备规格：
        </div>
    </div>
    <div class="row">
        <?if($model->led_spec):?>
            <?foreach (explode(',', $model->led_spec) as $v):?>
                <div class="col-md-3">
                    <?= $form->field($model, 'led_spec[]')->textInput(['class'=>'form-control', 'value'=>$v])->label(false) ?>
                </div>
            <?endforeach;?>
        <?else:?>
            <div class="col-md-3">
                <?= $form->field($model, 'led_spec[]')->textInput(['class'=>'form-control'])->label(false) ?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::button('继续添加',['class' => 'btn btn-success keep']);?>
        </div>
    </div>
    <hr/>
    <H3><b>画框配置</b></H3>
    <div class="row">
        <div class="col-md-2 yw">
            画框厂家名称：
        </div>
    </div>
    <div class="row">
        <?if($model->frame_device_manufactor):?>
            <?foreach (explode(',', $model->frame_device_manufactor) as $v):?>
                <div class="col-md-3">
                    <?= $form->field($model, 'frame_device_manufactor[]')->textInput(['class'=>'form-control', 'value'=>$v])->label(false) ?>
                </div>
            <?endforeach;?>
        <?else:?>
            <div class="col-md-3">
                <?= $form->field($model, 'frame_device_manufactor[]')->textInput(['class'=>'form-control'])->label(false) ?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::button('继续添加',['class' => 'btn btn-success keep']);?>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-2 yw">
            画框设备尺寸：
        </div>
    </div>
    <div class="row">
        <?if($model->frame_device_size):?>
            <?foreach (explode(',', $model->frame_device_size) as $v):?>
                <div class="col-md-3">
                    <?= $form->field($model, 'frame_device_size[]')->textInput(['class'=>'form-control', 'value'=>$v])->label(false) ?>
                </div>
            <?endforeach;?>
        <?else:?>
            <div class="col-md-3">
                <?= $form->field($model, 'led_spframe_device_sizeec[]')->textInput(['class'=>'form-control'])->label(false) ?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::button('继续添加',['class' => 'btn btn-success keep']);?>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-2 yw">
            画框材质：
        </div>
    </div>
    <div class="row">
        <?if($model->frame_device_material):?>
            <?foreach (explode(',', $model->frame_device_material) as $v):?>
                <div class="col-md-3">
                    <?= $form->field($model, 'frame_device_material[]')->textInput(['class'=>'form-control', 'value'=>$v])->label(false) ?>
                </div>
            <?endforeach;?>
        <?else:?>
            <div class="col-md-3">
                <?= $form->field($model, 'frame_device_material[]')->textInput(['class'=>'form-control'])->label(false) ?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::button('继续添加',['class' => 'btn btn-success keep']);?>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-2 yw">
            画框品质：
        </div>
    </div>
    <div class="row">
        <?if($model->frame_device_level):?>
            <?foreach (explode(',', $model->frame_device_level) as $v):?>
                <div class="col-md-3">
                    <?= $form->field($model, 'frame_device_level[]')->textInput(['class'=>'form-control', 'value'=>$v])->label(false) ?>
                </div>
            <?endforeach;?>
        <?else:?>
            <div class="col-md-3">
                <?= $form->field($model, 'frame_device_level[]')->textInput(['class'=>'form-control'])->label(false) ?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::button('继续添加',['class' => 'btn btn-success keep']);?>
        </div>
    </div>
    <hr/>

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