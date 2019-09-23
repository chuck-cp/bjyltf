<?php

use yii\helpers\Html;
use \cms\models\SystemAddress;
use cms\modules\config\models\SystemAddressLevel;

cms\assets\AppAsset::register($this);

$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

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
<style type="text/css">
    .left{width: 98%; padding-top: 10px;}
    .sybox li{
        list-style: none;
        border-bottom: 1px solid #ccc;
        padding-top: 10px;
    }
    .sub{
        text-align: center;
        width: 585px;
    }
    .rel{
        line-height: 35px;
        color: #5e87b0;
    }
    .area,.relate,.del{
        cursor: pointer;
    }
</style>

<!--<div class="checkbox">-->
<!--    <label><input type="checkbox" id="all" value="1">全选</label>-->
<!--</div>-->
<div class="row">
    <ul class="left zone sybox">
        <?if(empty($Areas)):?>
            暂无地区
        <?else:?>

        <? foreach ($Areas as $k => $v):?>
            <li>
                <span style="font-weight: bold"><?=Html::encode(SystemAddress::getAreaNameById($k))?></span>
                <? foreach ($v as $kth => $vth) :?>
                    <p style="display: inline-block; padding-left: 10px">
                        <span class="area">
                            <?=Html::encode($vth)?>
                        </span>
                    </p>
                <? endforeach;?><br/>
            </li>
        <? endforeach;?>
        <?endif;?>
    </ul>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>


