<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \cms\modules\shop\models\Shop;
$this->title = '配发货';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
//        'action' => ['index'],
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

    <input type="hidden" name="id" value="<?=Html::encode($searchModel->id)?>">
    <?=$form->field($searchModel,'member_id')->hiddenInput(['value'=>$searchModel->id])->label(false);?>
    <div class="row">
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
            <?=$form->field($searchModel,'apply_client')->dropDownList(['0'=>'手机端','1'=>'PC端'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请客户端');?>
        </div>

        <input type="hidden" id="shopsearch-status" class="form-control fm" name="ShopSearch[status]" value="2">

        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'delivery_status')->dropDownList(['1'=>'待配货','2'=>'待发货 ','3'=>'已发货'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请状态');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'way')->dropDownList(['0'=>'有推荐人','1'=>'无推荐人'],['class'=>'form-control fm','prompt'=>'全部'])->label('入驻方式');?>
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
            'name',
            [
                'label' => '法人代表',
                'value' => function($searchModel){
                    return $searchModel->apply['apply_name'];
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name;
                }
            ],
            'acreage',
            'apply_screen_number',
            'screen_number',
            'examine_user_name',
            'create_at',
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return Shop::getStatusByNum($searchModel->status);
                }
            ],
            [
                'label' => '发货状态',
                'value' => function($searchModel){
                    return Shop::getDeliveryByNum($searchModel->delivery_status);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('安装详情',['/examine/install/view','id'=>$searchModel->id]);
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