<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\modules\examine\models\ShopScreenReplace;
use cms\models\SystemAddress;
use cms\modules\member\models\MemberAccount;
$this->title = '商家信息';
$this->params['breadcrumbs'][] = ['label' => '人员查询', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

?>
<div class="shop_search">
    <?php echo $this->render('layout/tab',['model'=>$model]);?>

    <?php
    $form = ActiveForm::begin([
       /* 'action' => ['shop'],*/
        'method' => 'get',
    ]);
    ?>
<!--    <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
    <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript">
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
                        url: '<?=\yii\helpers\Url::to(['member/address'])?>',
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
    </script>
    
<!--    --><?//=$form->field($searchModel,'install_member_id')->hiddenInput(['value'=>$model->id])->label(false);?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'shop_id')->textInput(['class'=>'form-control fm'])->label('商家编号');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'shop_name')->textInput(['class'=>'form-control fm'])->label('商家名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'city')->dropDownList(SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'area')->dropDownList(SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'town')->dropDownList(SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'maintain_type')->dropDownList(['1'=>'商家入驻','2'=>'更换屏幕','3'=>'拆除屏幕','4'=>'新增屏幕'],['class'=>'form-control fm','prompt'=>'全部'])->label('维护类型');?>
        </div>
<!--        <div class="col-xs-2 form-group">-->
<!--            --><?//=$form->field($searchModel,'apply_screen_number')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请数量');?>
<!--        </div>-->
<!--        <div class="col-xs-2 form-group">-->
<!--            --><?//=$form->field($searchModel,'apply_client')->dropDownList(['0'=>'手机端','1'=>'PC端'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请客户端');?>
<!--        </div>-->
<!--        <div class="col-xs-2 form-group">-->
<!--            --><?//=$form->field($searchModel,'way')->dropDownList(['0'=>'有推荐人','1'=>'无推荐人'],['class'=>'form-control fm','prompt'=>'全部'])->label('入驻方式');?>
<!--        </div>-->
        <div class="col-xs-2 form-group">
            <?= $form->field($searchModel, 'install_finish_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('安装完成时间');?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($searchModel, 'install_finish_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
            <?if(in_array(Yii::$app->user->identity->username,['ylcmbeijing','ylcmshanghai','ylcmgaungzhou','ylcmgaungzhou'])):?>

            <?else:?>
                <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
            <?endif;?>
        </div>
    </div>
    <?php ActiveForm::end();?>
<!--    --><?php //echo $this->render('layout/installclass',['model'=>$model]);?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            [
                'label' => '维护类型',
                'value' =>function($model){
                    return ShopScreenReplace::getMaintainType($model->maintain_type);
                }
            ],
            'shop_id',
            'shop_name',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->shop_area_name.$searchModel->shop_address;
                }
            ],
            'replace_screen_number',
            'install_member_name',
            'create_at',
            'install_finish_at',
            [
                'label' => '状态',
                'value' =>function($model){
                    return ShopScreenReplace::getStatus($model->status);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/shop/shop/view','id'=>$searchModel->shop_id]);
                    }
                ],
            ],
        ]

    ]);?>

</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
</style>