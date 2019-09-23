<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\modules\shop\models\Shop;
use cms\modules\member\models\MemberAccount;
$this->title = '商家审核';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>
    <input type="hidden" name="id" value="<?=Html::encode($searchModel->id)?>">
    <?=$form->field($searchModel,'member_id')->hiddenInput(['value'=>$searchModel->id])->label(false);?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'apply_code')->textInput(['class'=>'form-control fm'])->label('订单号');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'id')->textInput(['class'=>'form-control fm'])->label('商家编号');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'member_name')->textInput(['class'=>'form-control fm'])->label('业务合作人');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('商家名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'acreage')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('店铺面积');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'apply_screen_number')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请数量');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'shop_operate_type')->dropDownList(['1'=>'租赁店','2'=>'自营店','3'=>'连锁店','4'=>'总店'],['class'=>'form-control fm','prompt'=>'全部'])->label('经营类型');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'status')->dropDownList(['0'=>'待审核','1'=>'审核未通过'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请状态');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'apply_client')->dropDownList(['0'=>'手机端','1'=>'PC端'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请客户端');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'member_inside')->dropDownList(['1'=>'内部合作人','0'=>'外部合作人'],['class'=>'form-control fm','prompt'=>'全部'])->label('身份类别');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'way')->dropDownList(['1'=>'有推荐人','2'=>'无推荐人'],['class'=>'form-control fm','prompt'=>'全部'])->label('入驻方式');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
        </div>
    </div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            'member_name',
            [
                'label' => '身份类别',
                'value' => function($searchModel){
                    return $searchModel->member_inside == 1?'内部合作人':'外部合作人';
                }
            ],
            [
                'label' => '法人代表',
                'value' => function($searchModel){
                    return $searchModel->apply['apply_name'];
                }
            ],
            'name',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name;
                }
            ],
            'acreage',
            'mirror_account',
            'screen_number',
            'examine_user_name',
            'create_at',
            [
                'label' => '店铺类型',
                'value' => function($searchModel){
                    return Shop::getTypeByNum($searchModel->shop_operate_type);
                }
            ],
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return Shop::getStatusByNum($searchModel->status);
                }
            ],
            [
                'label' => '审核状态',
                'value' => function($searchModel){
                    return Shop::getExamineByNum($searchModel->examine_number);
                }
            ],
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/examine/examine/view','id'=>$searchModel->id, 'apply_name'=>$searchModel->apply['apply_name']]);
                    }
                ],
            ],
        ]
    ]);?>
    <?php ActiveForm::end();?>
</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
</style>
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
</script>