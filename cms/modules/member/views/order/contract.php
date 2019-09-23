<?php

use yii\helpers\Html;
use cms\modules\member\models\Member;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '合同申请';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <?php echo $this->render('_contractsearch', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'label' => '申请时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            'order_code',
            //'member_name',
            [
                 'label'=>'申请人',
                 'value' => function($searchModel){
                    return $searchModel->member_name;
                }
            ],
            [
                'label' => '联系电话',
                'value' => function($searchModel){
                    return Member::getNameById($searchModel->member_id,'mobile');
                }
            ],
            [
                'label' => '业务合作人',
                'value' => function($searchModel){
                    return $searchModel->salesman_name;
                }
            ],
            [
                'label' => '广告对接人',
                'value' => function($searchModel){
                    return $searchModel->custom_service_name;
                }
            ],
            //'custom_service_mobile',
            'advert_name',
            'advert_time',
            [
                'label' => '投放频次',
                'value' => function($searchModel){
                    return $searchModel->rate;
                }
            ],
            [
                'label' => '付款状态',
                'value' => function($searchModel){
                    return '已完成';
                }
            ],
            /*[
                'label' => '优惠方式',
                'value' => function($searchModel){
                    if($searchModel->preferential_way){
                        return $searchModel->preferential_way;
                    }else{
                        return '无优惠';
                    }
                }
            ],*/
            [
                'label' => '订单价格',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->order_price);
                }
            ],
            [
                'label' => '最终价格',
                'value' => function($searchModel){
                    // return $searchModel->logPayment['pay_status'];
                    return ToolsClass::priceConvert($searchModel->final_price);
                }
            ],

            [
                'label' => '状态',
                'value' => function($searchModel){
                    if($searchModel->contact_status==1){
                        return '申请中';
                    }else if($searchModel->contact_status==2){
                        return '已申请';
                    }else{
                        return '未申请';
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{operation}',
                'buttons' => [
                        'operation' => function($url,$searchModel){
                            if($searchModel->contact_status==1){
                                return html::a('签约','javascript:void(0);',['class'=>'contract','id'=>$searchModel->id,'contact_status'=>$searchModel->contact_status]);
                            }else if($searchModel->contact_status==2){
                                return html::a('已签约','javascript:void(0);',['class'=>'ycontract','id'=>$searchModel->id,'contact_number'=>$searchModel->contact_number]);
                            }else {
                                return '';
                            }
                        }
                ],
            ],
            'remarks',
        ],
    ]); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.contract').bind('click', function () {
            var id = $(this).attr('id');
            var contact_status = $(this).attr('contact_status');

            pg = layer.open({
                title:'签约',
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['450px', '320px'], //宽高
                shadeClose: true,
                content: '<div class="row scroll form-horizontal txa" style="margin-top: 15px;"><div class="shuru">请输入对应签约合同编号</div><div class="col-sm-6"><input class="form-control bh" type="text" name="contact_number"></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary qd" data-type="rebut">确定</button><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><button type="button" class="btn btn-primary qx" data-type="rebut">取消</button></div>'
            });
            $('.qd').bind('click',function () {
                var contact_number = $('[name="contact_number"]').val();
                if(!contact_number){
                    layer.msg('请输入对应签约合同编号！',{icon:2});
                    return false;
                }
                $.ajax({
                    url : '<?=\yii\helpers\Url::to(['contactstatus'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'id':id, 'contact_status': contact_status, 'contact_number':contact_number},
                    success:function (phpdata) {
                        if(phpdata.code==1){
                            layer.msg('成功！',{icon:1});
                            setTimeout(function(){
                                window.location.reload()
                            },2000);
                        }else{
                            layer.msg(phpdata.msg,{icon:1});
                        }
                    },
                    error:function () {
                        layer.msg('操作失败！');
                    }
                });
            })
            $('.qx').bind('click',function(){
                layer.close(pg);
            })
        })
        $('.ycontract').bind('click', function () {
            var id = $(this).attr('id');
            var contact_number = $(this).attr('contact_number');
            pg = layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['550px', '200px'], //宽高
                shadeClose: true,
                content: '<div class="row scroll form-horizontal"  style="margin-top: 15px;"></div><div class="row scroll form-horizontal txa" style="margin-top: 15px;"></div><div class="bh2">签约合同编号：<span  rows="4">'+contact_number+'</span></div></div><div class="row scroll text-center" style="margin-top: 60px;"></div>'
            });
        })
    })

</script>
<style>
    .shuru {text-align: center; font-weight:bold}
    .bh {margin-left:120px;margin-top:30px;}
    .bh2 {text-align: center;font-weight:bold ;font-size: 20px;}
</style>