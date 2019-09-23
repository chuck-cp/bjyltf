<?php
use yii\helpers\Html;
use cms\modules\member\models\Order;
use common\libs\ToolsClass;
use cms\models\SystemAddress;
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
    <h5>申请人信息：</h5>
<!--    <div class="line"></div>-->

        <table class="table table-bordered">
            <tr>
                <td class="fir" width="20%">
                    用户： <?= Html::encode($model->member_name)?>
                </td>
                <td>手机号： <?= Html::encode($model->member_phone)?></td>
            </tr>
        </table>
    <h5>发票信息：</h5>
    <table class="table table-bordered">
        <tr>
            <td width="20%" class="fir">抬头类型：
                <?php if($model->invoice_title_type==1):?>
                    个人非企业单位
                <?php else:?>
                    企业单位
                <?php endif;?>
            </td>
            <td width="20%" class="fir">发票抬头：<?= Html::encode($model->invoice_title)?></td>
            <td class="fir">税号：<?= Html::encode($model->taxplayer_id)?></td>
        </tr>
        <tr>
            <td width="20%" class="fir">发票内容：广告购买费用</td>
            <td width="20%" class="fir" >发票金额：<?= Html::encode(ToolsClass::priceConvert($model->invoice_value))?></td>
            <td width="20%" class="fir" >总金额：<?= Html::encode(ToolsClass::priceConvert($model->order_price))?></td>
        </tr>
    </table>
    <h5>更多信息：</h5>
    <table class="table table-bordered">
        <tr>
            <td width="20%" class="fir">开户账号：<?= Html::encode($model->bank_account)?></td>
            <td class="fir" colspan="2">注册地址：<?= Html::encode($model->invoice_address)?></td>
        </tr>
        <tr>
            <td class="fir">注册电话：<?= Html::encode($model->invoice_phone)?></td>
            <td class="fir" width="20%">开户银行：<?= Html::encode($model->bank_name)?></td>
            <td class="fir">备注说明：<?= Html::encode($model->remark)?></td>
        </tr>
    </table>
    <h5>发票接受方式：</h5>
    <table class="table table-bordered">
        <tr>
            <td width="20%" class="fir">收件人：<?= Html::encode($model->receiver)?></td>
            <td class="fir">联系电话：<?= Html::encode($model->contact_phone)?></td>
        </tr>
        <tr>
            <td width="20%" class="fir">邮寄地区：<?= Html::encode(SystemAddress::getAreaNameById($model->address_id) )?></td>
            <td class="fir">详细地址：<?= Html::encode($model->address_detail)?></td>
        </tr>
    </table>
    <H5>订单信息</H5>
    <table class="table table-bordered">
        <tr>
            <td class="fir">订单号</td>
            <td class="fir">业务合作人</td>
            <td class="fir">业务合作人电话</td>
            <td class="fir">广告对接人</td>
            <td class="fir">广告位</td>
            <td class="fir">广告时长</td>
            <td class="fir">投放频次</td>
            <td class="fir">订单状态</td>
            <!--<td class="fir">优惠方式</td>-->
            <td class="fir">实付金额</td>
            <td class="fir">总费用</td>
            <td class="fir">订单备注</td>
        </tr>
        <?php foreach($OrderRes as $v):?>
            <tr>
                <td class="fir"><?php echo $v['order_code']?></td>
                <td class="fir"><?php echo $v['salesman_name']?></td>
                <td class="fir"><?php echo $v['salesman_mobile']?></td>
                <td class="fir"><?php echo $v['custom_service_name']?></td>
                <td class="fir"><?php echo $v['advert_name']?></td>
                <td class="fir"><?php echo $v['advert_time']?></td>
                <td class="fir"><?php echo $v['rate']?></td>
                <td class="fir"><?php echo Order::getOrderStatus('payment_status',$v['payment_status']);?></td>
                <!--<td class="fir"><?php /*echo $v['preferential_way']*/?></td>-->
                <td class="fir">
                    <?php if($v['payment_type']==1):?>
                        <?php if($v['payment_status']==3):?>
                            <?php echo ToolsClass::priceConvert($v['final_price'])?>
                        <?php else:?>
                            0.00
                        <?php endif;?>
                    <?php else:?>
                        <?php if($v['payment_status']==1 || $v['payment_status']==2):?>
                            <?php echo ToolsClass::priceConvert($v['final_price']-$v['payment_price'])?>
                        <?php elseif($v['payment_status']==3):?>
                            <?php echo ToolsClass::priceConvert($v['final_price'])?>
                        <?php else:?>
                            0.00
                        <?php endif?>
                    <?php endif;?>
                </td>
                <td class="fir"><?php echo  ToolsClass::priceConvert($v['order_price'])?></td>
                <td class="fir"><?php echo  $v['remarks']?></td>
            </tr>
        <?php endforeach;?>
    </table>
    <?php if($model->status==1):?>
        <button type="button" id="<?= Html::encode($model->id)?>" class="btn btn-primary confirm" data-type="pass">确认开票</button>
    <?php endif;?>

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
    $(function(){

        $(".confirm").click(function(){
            var id=$(this).attr('id');
            layer.confirm('是否确认开发票', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.ajax({
                    url : '<?=\yii\helpers\Url::to(['openinvoice'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'id':id},
                    success:function (phpdata) {
                        if(phpdata.code == 1){
                            layer.msg(phpdata.msg,{icon:1});
                            setTimeout(function(){
                                window.parent.location.reload();
                            },2000);
                        }else{
                            layer.msg(phpdata.msg,{icon:2});
                        }
                    },
                    error:function () {
                        layer.msg('操作失败！');
                    }
                });
            });

        })


    })
</script>
