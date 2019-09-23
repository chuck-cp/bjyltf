<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\modules\shop\models\ShopApply;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
    .wid{width: 210px;}
    .fl{float: left;}
    .fir{margin-left: 22px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="container">
    <h4>所属库存信息</h4>
    <?if($logs==''):?>
        <table>
            <tr>暂无消息</tr>
        </table>
    <?else:?>
        <table class="table table-bordered">
            <?foreach ($logs as $v):?>
                <tr>
                    <td><?echo $v['create_at']?></td>
                    <td><?echo $v['operation_type']==1?'入库':'出库'?> 至 <? echo $v['receiver_name']?></td>
                </tr>
            <?endforeach;?>
        </table>
    <?endif;?>
    <h4>商家信息：</h4>
    <?if($model==''):?>
        <table>
            <tr>暂无消息</tr>
        </table>
    <?else:?>
        <?$applyinfo = ShopApply::getCompanyById($model->id);?>
        <table class="table table-bordered">
            <tr>
                <td>地理位置：</td>
                <td>
                    <?=Html::encode($model->area_name)?>
                </td>
                <td>商家编号：</td>
                <td>
                    <?=Html::encode($model->id)?>
                </td>
            </tr>
            <tr>
                <td>商家名称：</td>
                <td>
                    <?=Html::encode($model->name)?>
                </td>
                <td>申请时间：</td>
                <td>
                    <?=Html::encode($model->create_at)?>
                </td>
            </tr>
            <tr>
                <td>店铺联系人：</td>
                <td>
                    <?=Html::encode($applyinfo->apply_name)?>
                </td>
                <td>联系方式：</td>
                <td>
                    <?=Html::encode($applyinfo->apply_mobile)?>
                </td>
            </tr>
        </table>
    <?endif;?>

    <h4>物流信息：</h4>
    <table class="table table-bordered">
        <?if($logisticsList):?>
            <?foreach ($logisticsList['data'] as $v):?>
                <tr>
                    <td>
                        <?=Html::encode($v['time'])?>
                    </td>
                    <td>
                        <?=Html::encode($v['context'])?>
                    </td>
                </tr>
            <?endforeach;?>
        <?else:?>
            <tr>
                暂无物流消息
            </tr>
        <?endif;?>
    </table>

</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
