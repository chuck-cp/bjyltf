<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\account\models\LogPayment;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\account\models\search\LogPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '线下收款';
$this->params['breadcrumbs'][] = $this->title;
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<div class="log-payment-index">
    <?php echo $this->render('_offlinesearch', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'label' => '用户ID',
                'value' =>function($model){
                    return $model->orderInfo['member_id'];
                }
            ],
            [
                'label' => '用户姓名',
                'value' =>function($model){
                    return $model->orderInfo['member_name'];
                }
            ],
            [
                'label' => '业务合作人ID',
                'value' =>function($model){
                    return $model->orderInfo['salesman_id'];
                }
            ],
            [
                'label' => '业务合作人',
                'value' =>function($model){
                    return $model->orderInfo['salesman_name'];
                }
            ],
            [
                'label' => '业务合作人手机号',
                'value' =>function($model){
                    return $model->orderInfo['salesman_mobile'];
                }
            ],
            [
                'label' => '广告对接人ID',
                'value' =>function($model){
                    return $model->orderInfo['custom_member_id'];
                }
            ],
            [
                'label' => '广告对接人',
                'value' =>function($model){
                    return $model->orderInfo['custom_service_name'];
                }
            ],
            [
                'label' => '广告对接人手机号',
                'value' =>function($model){
                    return $model->orderInfo['custom_service_mobile'];
                }
            ],
            'order_code',
            [
                'label' => '订单提交时间',
                'value' =>function($model){
                    return $model->orderInfo['create_at'];
                }
            ],
            [
                'label' => '订单金额',
                'value' =>function($model){
                    return \common\libs\ToolsClass::priceConvert($model->orderInfo['order_price']);
                    //return $model->price;
                }
            ],
            /*[
                'label' => '优惠方式',
                'value' => function($model){
                    if($model->orderInfo['preferential_way']){
                        return $model->orderInfo['preferential_way'];
                    }else{
                        return '无优惠';
                    }
                }
            ],*/
            [
                'label' => '最终价格',
                'value' => function($model){
                    return \common\libs\ToolsClass::priceConvert($model->orderInfo['final_price']);
                }
            ],
            [
                'label'=>'支付类型',
                'value'=>function($model){
                    return LogPayment::getPayStyle(true,$model->pay_style);
                }
            ],
            [
                'label' => '付款金额',
                'value' =>function($model){
                    return \common\libs\ToolsClass::priceConvert($model->price);
                }
            ],
            'payment_code',
            [
                'label'=>' 付款状态',
                'value'=>function($model){
                    if($model->pay_style==1){
                        if($model->pay_status==0){
                            if($model->orderInfo['payment_status']==3){
                                return '已线上支付';
                            }elseif($model->orderInfo['payment_status']==2 || $model->orderInfo['payment_status']==0){
                                return '未支付';
                            }else{
                                return '放弃支付';
                            }
                        }else{
                            return LogPayment::getPayStatus(true, $model->pay_status);
                        }
                    }elseif($model->pay_style==2){
                        if($model->pay_status==0){
                            if($model->orderInfo['payment_status']==1 || $model->orderInfo['payment_status']==3){
                                return '已线上支付';
                            }elseif($model->orderInfo['payment_status']==2 || $model->orderInfo['payment_status']==0){
                                return '未支付';
                            }else{
                                return '放弃支付';
                            }
                        }else{
                            return LogPayment::getPayStatus(true, $model->pay_status);
                        }
                    }elseif($model->pay_style==3){
                        if($model->pay_status==0){
                            if($model->orderInfo['payment_status']==3){
                                return '已线上支付';
                            }elseif($model->orderInfo['payment_status']==1 || $model->orderInfo['payment_status']==2 || $model->orderInfo['payment_status']==0){
                                return '未支付';
                            }else{
                                return '放弃支付';
                            }
                        }else{
                            return LogPayment::getPayStatus(true, $model->pay_status);
                        }
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$model){
                        if($model->pay_style==1){
                            if($model->pay_status==0){
                                if($model->orderInfo['payment_status']==3){
                                    return html::tag('span','<img src="static/img/yiqueren.png" class="wimg" />',['class'=>'detail']);
                                }elseif($model->orderInfo['payment_status']==2 || $model->orderInfo['payment_status']==0){
                                    return html::tag('span','<img src="static/img/queren.png" class="wimg" />',['class'=>'detail queern','order_code'=>$model->order_code,'pay_style'=>$model->pay_style,'id'=>$model->id,'price'=>$model->price,'pay_status'=>$model->pay_status]);
                                }
                            }else{
                                return html::tag('span','<img src="static/img/yiqueren.png" class="wimg" />',['class'=>'detail']);
                            }
                        }elseif($model->pay_style==2){
                            if($model->pay_status==0){
                                if($model->orderInfo['payment_status']==1 || $model->orderInfo['payment_status']==3){
                                    return html::tag('span','<img src="static/img/yiqueren.png" class="wimg" />',['class'=>'detail']);
                                }elseif($model->orderInfo['payment_status']==2 || $model->orderInfo['payment_status']==0){
                                    return html::tag('span','<img src="static/img/queren.png" class="wimg" />',['class'=>'detail queern','order_code'=>$model->order_code,'pay_style'=>$model->pay_style,'id'=>$model->id,'price'=>$model->price,'pay_status'=>$model->pay_status]);
                                }
                            }else{
                                return html::tag('span','<img src="static/img/yiqueren.png" class="wimg" />',['class'=>'detail']);
                            }
                        }elseif($model->pay_style==3){
                            if($model->pay_status==0){
                                if($model->orderInfo['payment_status']==3){
                                    return html::tag('span','<img src="static/img/yiqueren.png" class="wimg" />',['class'=>'detail']);
                                }elseif($model->orderInfo['payment_status']==1 || $model->orderInfo['payment_status']==2 || $model->orderInfo['payment_status']==0){
                                    return html::tag('span','<img src="static/img/queren.png" class="wimg" />',['class'=>'detail queern','order_code'=>$model->order_code,'pay_style'=>$model->pay_style,'id'=>$model->id,'price'=>$model->price,'pay_status'=>$model->pay_status]);
                                }
                            }else{
                                return html::tag('span','<img src="static/img/yiqueren.png" class="wimg" />',['class'=>'detail']);
                            }
                        }
                    },
                ],
            ],
            [
                'label' => '订单备注',
                'value' => function($model){
                    return $model->orderInfo['remarks'];
                }
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<style type="text/css">
    .detail:hover{cursor:pointer;}
    .queern{color:#5e87b0;}
    .wimg{width:25px;height: 25px;}
</style>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function(){
        /**
         * 确认金额
         */
        $('.queern').bind('click',function(){
            var order_code = $(this).attr('order_code');
            var pay_style = $(this).attr('pay_style');
            var id = $(this).attr('id');
            var price = $(this).attr('price');
            var pay_status = $(this).attr('pay_status');
            layer.confirm('请确认金额无误？', {
                btn: ['确认无误','再看看'] //按钮
            }, function(){
                $.ajax({
                    url:'<?=\yii\helpers\Url::to(['confirm'])?>',
                    type: 'post',
                    dataType : 'json',
                    data : {order_code:order_code,pay_style:pay_style,'id':id,'price':price,'pay_status':pay_status},
                    success:function(phpdata){
                        if(phpdata.code==1){
                            layer.msg('确认完成！',{icon:1});
                            setTimeout(function(){
                                window.location.reload();
                            },2000);
                        } else{
                            layer.msg(phpdata.msg,{icon:2});
                            layer.closeAll('page');
                        }
                    },
                    error:function(){
                        layer.msg('操作失败！',{icon:2});
                    }
                });
            }, function(){

            });
        })

        /**
         * 收到金额
         */
        $('.shoudao').bind('click',function(){
            var order_code = $(this).attr('order_code');
            var price = $(this).attr('price');
            var pay_style = $(this).attr('pay_style');
            var payment_status = $(this).attr('payment_status');
            var id = $(this).attr('id');
            var pageup = layer.open({
                type: 2,
                title: '金额',
                shadeClose: true,
                shade: 0.8,
                area: ['80%', '80%'],
                content: '<?=\yii\helpers\Url::to(['/account/settle-center/money'])?>&order_code='+order_code+'&price='+price+'&pay_style='+pay_style+'&payment_status='+payment_status+'&id='+id
            });
        })
    })
</script>
