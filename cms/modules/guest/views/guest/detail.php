<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use cms\modules\member\models\Order;
use common\libs\ToolsClass;
use cms\modules\member\models\Member;
use cms\modules\member\models\OrderDate;
use cms\modules\member\models\OrderArea;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');

$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="container">
    <h4>付款信息：</h4>
<!--    <div class="line"></div>-->
    <?php if($model->payment_type==1):?>
        <table class="table table-bordered">
            <tr>
                <td class="fir">订单号：</td>
                <td>
                    <?= Html::encode($model->order_code)?>
                </td>
                <td class="fir">付款类型：</td>
                <td>
                    <?= Html::encode(Order::getOrderStatus('payment_type',$model->payment_type))?>
                </td>
                <td class="fir">首付款时间：</td>
                <td>
                    <?= Html::encode(date('Y-m-d',strtotime($model->last_payment_at)))?>
                </td>
            </tr>
            <tr>
                <td class="fir">总费用：</td>
                <td>
                    <?= Html::encode(ToolsClass::priceConvert($model->order_price))?>
                </td>
                <td class="fir">实付金额：</td>
                <td>
                    <? if($model->payment_status < 1):?>
                        0.00
                    <? elseif ($model->payment_status == 1 || $model->payment_status == 2):?>
                        <?= Html::encode(ToolsClass::priceConvert($model->payment_price))?>
                    <? else:?>
                        <?= Html::encode(ToolsClass::priceConvert($model->order_price))?>
                    <? endif;?>
                </td>
                <td class="fir"></td>
                <td>
                </td>
            </tr>
        </table>
    <?php else:?>
        <table class="table table-bordered">
            <tr >
                <td width="30%">订单号： <?= Html::encode($model->order_code)?></td>
                <td width="30%">付款类型：<?= Html::encode(Order::getOrderStatus('payment_type',$model->payment_type))?></td>
                <td width="30%">尾款状态：
                    <?php if($model->payment_status==2):?>
                        已逾期
                    <?php elseif($model->payment_status==3):?>
                        已付款
                    <?php else:?>
                        未付款
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <td width="30%">首付款日期： <?= Html::encode($model->payment_at)?></td>
                <td width="30%">实付金额：
                    <?php if($model->payment_status==1 || $model->payment_status==2):?>
                        <?= Html::encode(ToolsClass::priceConvert($model->payment_price))?>
                    <?php elseif($model->payment_status==3):?>
                        <?= Html::encode(ToolsClass::priceConvert($model->order_price))?>
                    <?php else:?>
                        0.00
                    <?php endif;?>
                </td>
                <td width="30%">未付金额：
                    <?php if($model->payment_status==1 || $model->payment_status==2):?>
                        <?= Html::encode(ToolsClass::priceConvert($model->order_price-$model->payment_price))?>
                    <?php elseif($model->payment_status==3):?>
                        0.00
                    <?php else:?>
                        <?= Html::encode(ToolsClass::priceConvert($model->order_price))?>
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <td width="30%">尾款日期： <?= Html::encode($model->last_payment_at)?></td>
                <td width="30%">定金支付截止日期：<?= Html::encode($model->overdue_at)?></td>
                <td width="30%">总费用：<?= Html::encode(ToolsClass::priceConvert($model->order_price))?></td>
            </tr>
        </table>
    <?php endif;?>

    <h4>购买详情：</h4>
<!--    <div class="line"></div>-->
    <table class="table table-bordered">
        <tr>
            <td class="fir">手机号：</td>
            <td>
                <?= Html::encode(Member::getNameById($model->member_id,'mobile'))?>
            </td>
            <td class="fir">业务合作人：</td>
            <td>
                <?= Html::encode($model->salesman_name)?>
            </td>
            <td class="fir">合作人电话：</td>
            <td>
                <?= Html::encode($model->salesman_mobile)?>
            </td>
        </tr>
        <tr>
            <td class="fir">广告位：</td>
            <td>
                <?= Html::encode($model->advert_name)?>
            </td>
            <td class="fir">广告时长：</td>
            <td>
                <?= Html::encode($model->advert_time)?>
            </td>
            <td class="fir">投放频次：</td>
            <td>
                <?= Html::encode($model->rate)?>
            </td>
        </tr>
        <tr class="dateup">
            <td class="fir">投放日期：</td>
            <td colspan="5">
                <?= Html::encode(OrderDate::getOrderDate($model->id))?>
                <? if($orderDate['is_update'] != 3 && $model->payment_status>=1): ?>
<!--                <span><a href="javascript:void(0);" class="update" >修改时间</a></span>-->
                <? endif; ?>
            </td>
        </tr>
        <tr>
            <td>投放地区：</td>
            <td>
                <div class="dropdown">
                    <?if($model->deal_price == 0):?>
                        <a href="javascript:void(0);">无购买地区</a>
                    <? else:?>
                        <a target="_blank" href="<?=Url::to(['/report/report/schedule', 'id'=>$model->id])?>">点击查看投放地区列表</a>
                    <?endif;?>
                </div>
            </td>
        </tr>
    </table>
    <ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
        <?= Html::submitButton('订单状态', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('投放状态', ['class' => 'btn btn-default']) ?>
    </ul>
    <table class="table table-hover">
        <? if(!empty($payMsg)):?>
            <? foreach ($payMsg as $k => $v):?>
                <tr>
                    <?php if($v['desc']):?>
                        <td <?if($k == 0):?>style="border-top: none;"<? endif;?>>
                            <?=Html::encode($v['create_at']).' '.Html::encode($v['desc'])?>
                        </td>
                    <?php endif;?>
                </tr>
            <? endforeach;?>
        <? else:?>
            <tr>
                <td style="border-top: none;">暂无消息</td>
            </tr>
        <? endif;?>
    </table>
    <table class="table table-hover" style="display: none;">
        <? if(!empty($throwMsg)):?>
            <? foreach ($throwMsg as $k => $v):?>
                <tr>
                    <?php if($v['desc']):?>
                        <td <?if($k == 0):?>style="border-top: none;"<? endif;?>>
                            <?=Html::encode($v['create_at']).' '.Html::encode($v['desc'])?>
                            <? if($v['reject_reason']):?>
                                <?=Html::button('查看',['class'=>'view'])?>
                                <p style="border: 1px solid #cccccc; padding: 5px;margin-top: 8px;display: none;">
                                    <?=Html::encode($v['reject_reason'])?>
                                </p>
                            <? endif;?>
                        </td>
                    <?php endif?>

                </tr>
            <? endforeach;?>
        <? else:?>
            <tr>
                <td style="border-top: none;">暂无消息</td>
            </tr>
        <? endif;?>
    </table>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<style type="text/css">
    .fm{width: 140px;}
    .wid{width: 210px;}
    .fl{float: left;}
    /*.fir{margin-left: 2px;}*/
    .mt{margin-top: 8px;}
    h4{text-indent: 2%}
    .line{height: 1px;border: 0.5px solid #C1C1C1;width: 98%;margin-left: 2%}
    /*.fir{width: 14%;}*/
</style>
<script src="/static/js/common.js"></script>
<script type="text/javascript">
    $(function () {
        $(".btn").click(function () {
            if($(this).hasClass('btn-default')){
                $(this).removeClass('btn-default').addClass('btn-primary').siblings().removeClass('btn-primary').addClass('btn-default');
            }
            var nu = $(this).index();
            if(nu == 0){
                $('.table-hover').eq(0).css('display','block');
                $('.table-hover').eq(1).css('display','none');
            }else {
                $('.table-hover').eq(1).css('display','block');
                $('.table-hover').eq(0).css('display','none');
            }
        })
        //
        $('.view').click(function () {
            $(this).next('p').toggle('normal');
        })
        //点击修改时间
        $('.update').on('click',function(){
            $('.dateup').css('display','none');
            $('.dateinput').css('display','table-row');
        })
        //点击取消修改
        $('.reupdate').on('click',function(){
            $('.dateup').css('display','table-row');
            $('.dateinput').css('display','none');
        })
        //开始时间选择
        $('.starts_at').on('change',function(){
            var startdate = $('.starts_at').val();
            var myDate = new Date().toLocaleDateString(); //获取今天日期
            var time1 = Date.parse(new Date(startdate));
            var time2 = Date.parse(new Date(myDate));
            var nDays = parseInt((time1 - time2)/1000/3600/24);
            if(nDays<15){
                layer.msg('只可修改15天后的广告排期！');
            }
            var num = $("input[name='datenum']").val();
            var newDate = new Date(time1 + (num-1) * 86400 * 1000);
            var nian = newDate.getFullYear();
            var yue = newDate.getMonth()>=10?newDate.getMonth()+1:'0'+(newDate.getMonth()+1);
            var ri = newDate.getDate()>=10?newDate.getDate():'0'+newDate.getDate();
            var ndates = [nian, yue, ri].join('-');
            $('.end_at').attr('value',ndates);
        })
        //提交修改时间
        $('.submitupdate').on('click',function(){
            var startdate = $('.starts_at').val();
            var myDate = new Date().toLocaleDateString(); //获取今天日期
            var time1 = Date.parse(new Date(startdate));
            var time2 = Date.parse(new Date(myDate));
            var nDays = parseInt((time1 - time2)/1000/3600/24);
            var enddate = $('.end_at').val();
            var orderid = $('input[name="orderid"]').val();
            if(nDays>=15){
                $.ajax({
                    url: '<?=Url::to(['/member/order/uporderdate'])?>',
                    type: 'POST',
                    dataType: 'json',
                    data:{'orderid':orderid,'start_at':startdate,'end_at':enddate},
                    success:function (updata) {
                        if(updata[0]=='ORDER_LOCKED'){
                            layer.msg('订单已锁定，暂时无法修改时间！');
                        }else if(updata[0]=='ORDER_NO_MODIFY'){
                            layer.msg('时间没有变化，请确认时间！');
                        }else if(updata[0]=='ORDER_DATE_NOT_ALLOWED'){
                            layer.msg('起始时间必须是15天后！');
                        }else if(updata[0]=='ORDER_MODIFY_FAILED'){
                            layer.msg('修改时间失败！');
                        }else if(updata[0]=='SUCCESS'){
                            layer.msg('修改时间成功！');
                            window.location.reload();
//                            $('.dateup').css('display','table-row');
//                            $('.dateinput').css('display','none');
                        }else{
                            layer.msg('修改时间失败！');
                        }
                    },error:function () {
                        layer.msg('修改时间失败！');
                    }
                })
            }else{
                layer.msg('只可修改15天后的广告排期！');
                return false;
            }
        })
        $('.selectdate').on('click',function(){
            var startdate = $('.starts_at').val();
            var myDate = new Date().toLocaleDateString(); //获取今天日期
            var time1 = Date.parse(new Date(startdate));
            var time2 = Date.parse(new Date(myDate));
            var nDays = parseInt((time1 - time2)/1000/3600/24);
            if(nDays>=15){
                var id = $('input[name="orderid"]').val();
                var startdate = $('.starts_at').val();
                var enddate = $('.end_at').val();
                var pageup = layer.open({
                    type: 2,
                    title: '排期信息',
                    shadeClose: true,
                    shade: 0.8,
                    area: ['90%', '80%'],
                    content: '<?=\yii\helpers\Url::to(['/member/order/paiqi'])?>&id='+id+'&start='+startdate+'&end='+enddate
                });
            }else{
                layer.msg('只可查看15天后的广告排期');
                return false;
            }

        })
    })
</script>
