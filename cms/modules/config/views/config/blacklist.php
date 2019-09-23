<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '黑名单次数设置';
$this->params['breadcrumbs'][] = '黑名单次数设置';
?>
<?php $form = ActiveForm::begin([
    'action' => ['blacklist'],
    'method' => 'post',
]); ?>
    <div class="system-config-index">
        <div class="row">
            <div class="col-md-2 yw" style="width: 90px;">
                1.发送：
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'send_number_in_black')->textInput()->label(false) ?>
            </div>
            <span class="yw">次，设置为黑名单</span>
            <?= Html::submitButton('保存', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<?php $form = ActiveForm::begin([
    'action' => ['addblack'],
    'method' => 'post',
]); ?>
<div class="system-config-index">
    <div class="row">
        <div class="col-md-2 yw">
            2.添加到redis黑名单：
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control yw" name="blackredis" aria-required="true" aria-invalid="false" value=""/>
        </div>
        <div class="col-md-1 but">
            <?=Html::submitButton('添加', ['class' => 'btn btn-primary but','name'=>'submits','value'=>'redis']) ?>
        </div>
    </div>
    <div class="row grid-view">
        <div class="col-md-2 yw">
            3.redis黑名单明细
        </div>
        <div style="margin-left: 15px;">
            <table class="table table-striped table-bordered" style="width: 99%;">
                <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th>应用类型</th>
                    <th class="action-column" style="width: 13%;">操作</th>
                </tr>
                </thead>
                <tbody>
                <? foreach($blackinfo as $key=>$value):?>
                <tr>
                    <td><?=Html::encode($key+1)?></td>
                    <td><?=Html::encode($value)?></td>
                    <td>
                        <?if(strstr($value,'member_id')):?>
                            <?=Html::a('查看人员详情',['/member/member/view','id'=>substr($value,10)],['target'=>'_blank']);?>
                        <?endif;?>
                        <?=Html::a('删除','javascript:void(0);',['class'=>'shanchu','value'=>$value]);?>
                    </td>
                </tr>
                <?endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
    }
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.shanchu').on('click',function(){
        var black=$(this).attr('value');
        $.ajax({
            url: '<?=Url::to(['delblack'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'black':black},
            success:function (sre) {
                if(sre==1){
                    layer.msg('删除成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('删除失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    })
</script>