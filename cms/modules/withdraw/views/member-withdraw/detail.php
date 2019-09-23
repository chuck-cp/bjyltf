<?php
use yii\grid\GridView;
use common\libs\ToolsClass;
use yii\helpers\Html;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody(); ?>
        <div>
            <table class="grid table table-striped table-bordered search">
                <tr>
<!--                    <td>编号：</td>-->
<!--                    <td>--><?//=Html::encode($mwlist->id)?><!--</td>-->
                    <td>收款人：</td>
                    <td><?=Html::encode($mwlist->payee_name)?></td>
                    <td>申请时间：</td>
                    <td><?=Html::encode($mwlist->create_at)?></td>
                    <td>提现金额：</td>
                    <td colspan="3"><?=Html::encode(number_format($mwlist->price/100,2))?></td>
                </tr>
                <tr>
                    <td>银行卡号：</td>
                    <td><?=Html::encode($mwlist->bank_account)?></td>
                    <td>收款银行：</td>
                    <td><?=Html::encode($mwlist->bank_name)?></td>
                    <td>银行预留手机号：</td>
                    <td><?=Html::encode($mwlist->bank_mobile)?></td>
                </tr>
                <?if(!empty($desc)):?>
                    <tr><th colspan="6"><b>审核信息</b></th></tr>
                    <?php foreach ($desc as $v):?>
                        <tr>
                            <td colspan="6">
                                日期：<?=Html::encode($v['create_at'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                操作人：<?=Html::encode($v['create_user_name'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <? if($v['examine_result']==2):?>
                                    结果：<?=Html::encode($v['examine_desc'])?>
                                <? elseif ($v['examine_result']==1):?>
                                    结果：已通过审核
                                <? endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?endif;?>
            </table>
        </div>
<?php $this->endBody() ?>
</body>

<?php $this->endPage() ?>