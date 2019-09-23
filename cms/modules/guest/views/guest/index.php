<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\modules\shop\models\Shop;
$this->title = '商家信息';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="shop_search" style="padding: 0 15px;">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>

                <td>
                    <p>店铺ID</p>
                    <?=$form->field($searchModel,'id')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>商家名称</p>
                    <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>法人姓名</p>
                    <?=$form->field($searchModel,'apply_name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>法人手机</p>
                    <?=$form->field($searchModel,'apply_mobile')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>公司名称</p>
                    <?=$form->field($searchModel,'company_name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>联系人姓名</p>
                    <?=$form->field($searchModel,'contacts_name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>联系人手机</p>
                    <?=$form->field($searchModel,'contacts_mobile')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>审核通过时间</p>
                    <?= $form->field($searchModel, 'shop_examine_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                </td>
                <td>
                    <p>.</p>
                    <?= $form->field($searchModel, 'shop_examine_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false);?>
                </td>
                <td style="padding-top: 15px;" colspan="3">
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary searchaa'])?>
                </td>
            </tr>
        </table>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => '店铺ID',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '订单编号',
                'value' => function($searchModel){
                    return $searchModel->apply['apply_code'];
                }
            ],
            [
                'label' => '店铺名称',
                'value' => function($searchModel){
                    return $searchModel->name;
                }
            ],
            [
                'label' => '公司名称',
                'value' => function($searchModel){
                    return $searchModel->apply['company_name'];
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name;
                    //return SystemAddress::getAreaNameById($searchModel->area);
                }
            ],
            [
                'label' => '店铺面积（平方米）',
                'value' => function($searchModel){
                    return  $searchModel->acreage;
                }
            ],
            [
                'label' => '店铺镜面数量',
                'value' => function($searchModel){
                    return  $searchModel->mirror_account;
                }
            ],
            [
                'label' => '实际屏幕数量',
                'value' => function($searchModel){
                    return  $searchModel->screen_number;
                }
            ],
            [
                'label' => '创建时间',
                'value' => function($searchModel){
                    return  $searchModel->create_at;
                }
            ],
            [
                'label' => '店铺类型',
                'value' => function($searchModel){
                    return Shop::getTypeByNum($searchModel->shop_operate_type);
                }
            ],
            [
                'label' => '是否更换屏幕',
                'value' => function($searchModel){
                    return $searchModel->replace_screen_status == 1?'是':'否';
                }
            ],
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return \cms\modules\shop\models\Shop::getStatusByNum($searchModel->status);
                }
            ],
            [
                'label' => '审核通过时间',
                'value' => function($searchModel){
                    return  $searchModel->shop_examine_at;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/guest/guest/view','id'=>$searchModel->id,'token'=>\common\libs\ToolsClass::makeCustomToken($searchModel->apply['apply_mobile'].$searchModel->id)]);
                    }
                ],
            ],
        ]
    ]);?>
    <?php ActiveForm::end();?>
    </div>
</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block; margin-top:10px;}
    .start {width: 50%}
    .grid p{float: left;margin:18px 15px 0 0 }
    .postion{position:relative}
    .blue{color: #06F;}
    .postion .fm{width: 160px;}

</style>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<!--<script type="text/javascript">
    // 验证手机号
    function isPhoneNo(phone) {
        var pattern = /^1[34578]\d{9}$/;
        return pattern.test(phone);
    }
    $(function(){
        $('.searchaa').click(function(){
            var a = new Array();
            var apply_mobile = $('input[name="ShopkfSearch[apply_mobile]"]').val();//法人手机
            if(apply_mobile){
                if (isPhoneNo($.trim(apply_mobile)) == false) {
                    layer.msg('手机号码不正确',{icon:2});
                    return false;
                }
            }
            if(!apply_mobile){
                layer.msg('法人手机必填',{icon:2})
                return false;
            }
            a[0] = $('input[name="ShopkfSearch[name]"]').val();//商家名称
            a[1] = $('input[name="ShopkfSearch[apply_name]"]').val();//法人姓名
            a[2] = $('input[name="ShopkfSearch[member_name]"]').val();//业务员姓名
            a[3] = $('input[name="ShopkfSearch[member_mobile]"]').val();//业务员手机
            a[4] = $('input[name="ShopkfSearch[contacts_name]"]').val();//业务员手机
            a[5] = $('input[name="ShopkfSearch[contacts_mobile]"]').val();//业务员手机
            var arr=$.grep(a,function(n,i){
                return n;
            },false);

            var length=arr.length;
            if(length==0){
                layer.msg('缺少搜索条件',{icon:2});
                return false;
            }
        })
    })
</script>-->