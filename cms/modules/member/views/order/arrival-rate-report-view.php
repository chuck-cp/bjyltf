<?php
//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use cms\modules\member\models\Order;
use common\libs\ToolsClass;
use cms\modules\member\models\Member;
use cms\modules\member\models\OrderDate;
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
    <table class="table table-bordered">
        <tr>
            <td width="30%">店铺ID</td>
            <td><?echo $data['shop_id']?></td>
        </tr>
        <tr>
            <td>店铺名称</td>
            <td><?echo $data['shop_name']?></td>
        </tr>
        <tr>
            <td>初始屏幕数量</td>
            <td><?echo $data['buyed_number']?>快</td>
        </tr>
        <tr>
            <td>初始屏幕编号</td>
            <td>
                <?if(!empty($data['buyed_software_number'])):?>
                    <?foreach ($data['buyed_software_number'] as $v):?>
                        <?echo $v?>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
        <tr>
            <td>当前屏幕数量</td>
            <td><?echo $data['now_number']?>块</td>
        </tr>
    </table>
    <table class="table table-bordered">
        <tr>
            <td colspan="3">当前屏幕及到达情况</td>
        </tr>
        <tr>
            <td width="30%">屏幕编号</td>
            <td>到达情况</td>
            <td>到达时间</td>
        </tr>
        <?foreach ($data['software_number'] as $v):?>
            <tr>
                <td><?echo $v['number']?></td>
                <td>
                    <?if(!empty($v['date'])):?>
                        已到达
                    <?else:?>
                        未到达
                    <?endif;?>
                </td>
                <td><?echo $v['date']?></td>
            </tr>
        <?endforeach;?>
    </table>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
