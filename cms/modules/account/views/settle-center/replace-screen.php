<?php

use yii\helpers\Html;
use cms\modules\examine\models\ShopScreenReplace;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
use common\libs\ToolsClass;

$this->title = '换屏费用支出';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

?>
<div class="shop-screen-replace-index">

    <?php $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($resModel,'install_member_id')->textInput(['class'=>'form-control fm'])->label('安装人ID');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($resModel,'install_member_name')->textInput(['class'=>'form-control fm'])->label('安装人姓名');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($resModel,'install_member_mobile')->textInput(['class'=>'form-control fm'])->label('安装人电话');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($resModel,'shop_name')->textInput(['class'=>'form-control fm'])->label('商家名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($resModel, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($resModel, 'city')->dropDownList(SystemAddress::getAreasByPid($resModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($resModel, 'area')->dropDownList(SystemAddress::getAreasByPid($resModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($resModel, 'town')->dropDownList(SystemAddress::getAreasByPid($resModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($resModel, 'install_finish_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('安装完成时间');?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($resModel, 'install_finish_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($resModel, 'create_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('申请换屏时间');?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($resModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
        </div>

        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary', 'name'=>'search', 'value'=>1])?>
            <?=Html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'label' => '安装类型',
                'value' =>function($model){
                    return ShopScreenReplace::getMaintainType($model->maintain_type);
                }
            ],
            'shop_id',
            'shop_name',
            [
                'label' => '店铺地址',
                'value' =>function($model){
                    return $model->shop_area_name.$model->shop_address;
                }
            ],
            'install_member_id',
            'install_member_name',
            [
                'label' => '安装人电话',
                'value' =>function($model){
                    return $model->member['mobile'];
                }
            ],
            'create_at',
            'install_finish_at',
            'replace_screen_number',
            [
                'label'=>'安装屏幕单价',
                'value' =>function($model){
                    return ToolsClass::priceConvert($model->install_price/$model->replace_screen_number);
                }
            ],
            [
                'label'=>'安装屏幕总费用',
                'value' =>function($model){
                    return ToolsClass::priceConvert($model->install_price);
                }
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //地区切换
    $(function () {
        $('.area').change(function () {
            var type = $(this).attr('key');
            var selObj = $('[key='+type+']').parents('.col-xs-2');
            selObj.nextAll().find('select').find('option:not(:first)').remove();
            var parent_id = $(this).val();
            if(!parent_id){
                return false;
            }
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
                type: 'POST',
                dataType: 'json',
                data:{'parent_id':parent_id},
                success:function (phpdata) {
                    $.each(phpdata,function (i,item) {
                        selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                    })
                },error:function (phpdata) {
                    layer.msg('获取失败！');
                }
            })
        })
    })
    //指派
    $('.Assign').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '换屏指派',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/examine/shop-screen-replace/assign-member'])?>&id='+id
        });
    })
    //取消指派
    $('.qxzp').click(function(){
        var id = $(this).attr('id');
        layer.confirm('你确定要取消指派吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['no-reassign'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id},
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！');
                }
            });
        });
    })
</script>