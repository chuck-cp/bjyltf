<?php

use yii\helpers\Html;
use \yii\helpers\Url;
use cms\modules\examine\models\ShopContract;
use cms\modules\shop\models\Shop;
use yii\widgets\ActiveForm;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\examine\models\search\ShopContractSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '合同审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php echo $this->render('layout/contract',['searchModel'=>$searchModel]);?>

<div class="shop_search">
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
                    <p>商家名称</p>
                    <?=$form->field($searchModel,'shop_name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>法人姓名</p>
                    <?=$form->field($searchModel,'apply_name')->textInput(['class'=>'form-control fm'])->label(false);?>

                </td>
                <td>
                    <p>业务合作人姓名</p>
                    <?=$form->field($searchModel,'member_name')->textInput(['class'=>'form-control fm'])->label(false);?>

                </td>
                <!--<td>
                    <p>业务合作人电话</p>
                    <?/*=$form->field($searchModel,'member_name')->textInput(['placeholder'=>'联系人姓名','class'=>'form-control fm'])->label(false);*/?>

                </td>-->
                <td>
                    <p>柜号</p>
                    <?=$form->field($searchModel,'cabinet_number')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>店铺安装状态</p>
                    <?=$form->field($searchModel,'status')->dropDownList(['2'=>'待安装','3'=>'安装待审核','4'=>'安装未通过','5'=>'安装完成'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>合同审核状态</p>
                    <?=$form->field($searchModel,'examine_status')->dropDownList(['1'=>'未审核','2'=>'通过','3'=>'驳回','4'=>'解除'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>店铺类型</p>
                    <?=$form->field($searchModel,'shop_operate_type')->dropDownList(['1'=>'租赁','2'=>'自营','3'=>'连锁'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>签订时间</p>
                    <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                </td>
                <td>
                    <p>.</p>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
                <td style="padding-top: 30px;">
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                    <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end();?>
</div>
<div class="shop-contract-index">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'contract_number',
            [
                'label'=>'商家编号',
                'value'=>function($searchModel){
                    return $searchModel->shop_id;
                }
            ],
            [
                'label'=>'公司名称',
                'value'=>function($searchModel){
                    return $searchModel->shopApply['company_name'];
                }
            ],
            [
                'label'=>'店铺名称',
                'value'=>function($searchModel){
                    return $searchModel->shop['name'];
                }
            ],
            [
                'label'=>'统一社会信用代码',
                'value'=>function($searchModel){
                    return $searchModel->shopApply['registration_mark'];
                }
            ],
            [
                'label'=>'业务员',
                'value'=>function($searchModel){
                    return $searchModel->shop['member_name'];
                }
            ],
            [
                'label'=>'通讯地址',
                'value'=>function($searchModel){
                    return $searchModel->shop['area_name'].$searchModel->shop['address'];
                }
            ],
            [
                'label'=>'法人代表',
                'value'=>function($searchModel){
                    return $searchModel->shopApply['apply_name'];
                }
            ],
            [
                'label'=>'身份证号码',
                'value'=>function($searchModel){
                    return $searchModel->shopApply['identity_card_num'];
                }
            ],
            [
                'label'=>'店铺联系人',
                'value'=>function($searchModel){
                    return $searchModel->shopApply['contacts_name'];
                }
            ],
            'create_at',
            [
                'label'=>'店铺类型',
                'value'=>function($searchModel){
                    return Shop::getTypeByNum($searchModel->shop['shop_operate_type']);
                }
            ],
            [
                'label'=>'店铺状态',
                'value'=>function($searchModel){
                    return Shop::getStatusByNum($searchModel->shop['status']);
                }
            ],[
                'label'=>'合同状态',
                'value'=>function($searchModel){
                    if($searchModel->status == 1){
                        return ShopContract::getContractStatus($searchModel->examine_status);
                    }else{
                        return '已解除';
                    }
                }
            ],
            'receiver_name',
            'cabinet_number',
            'description',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{update} {pass} {reset} {relieve} {view} ',
                'buttons' => [
                    'update' => function($url,$searchModel){
                        return Html::a('信息', 'javascript:void(0);', ['class' => 'update', 'id' => $searchModel->id]);
                    },
                    'pass' => function($url,$searchModel){
                        if($searchModel->examine_status != 1) {
                            return Html::a('通过', 'javascript:void(0);', ['class' => 'conexamine', 'status' => 1, 'id' => $searchModel->id]);
                        }
                    },
                    'reset' => function($url,$searchModel){
                        if($searchModel->examine_status != 1) {
                            return Html::a('驳回', 'javascript:void(0);', ['class' => 'conexamine', 'status' => 2, 'id' => $searchModel->id]);
                        }
                    },
                    'relieve' => function($url,$searchModel){
                        if($searchModel->examine_status == 1 && $searchModel->status == 1){
                            return Html::a('解除','javascript:void(0);',['class'=>'relieve','status'=>2,'id'=>$searchModel->id]);
                        }
                    },
                    'view' => function($url,$searchModel){
                        return Html::a('详情',['/shop/shop/view','id'=>$searchModel->shop_id],['target'=>'_blank']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    //点击填写信息
    $('.update').click(function () {
        var id = $(this).attr('id');
        layer.open({
            type: 2,
            title: '查看',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '50%'],
            content: '<?=Url::to(['/examine/shop-contract/add-contract-id'])?>&id='+id
        });
    })
    //点击通过/驳回
    $('.conexamine').click(function () {
        var id = $(this).attr('id');
        var status = $(this).attr('status');
        if(status == 1){
            var mas = '你确定要通过吗？';
        }else{
            var mas = '你确定要驳回吗？';
        }
        layer.confirm(mas, {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['con-examine'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'status':status},
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
    //点击解除合同
    $('.relieve').click(function () {
        var id = $(this).attr('id');
        var status = $(this).attr('status');
        layer.confirm('你确定要解除合同吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['con-relieve'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'status':status},
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