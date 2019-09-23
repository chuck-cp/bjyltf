<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'place'):?>class="active"<?endif;?>>
        <?=Html::a('广告位',['place'])?>
    </li>
    <li <? if($action == 'price'):?>class="active"<?endif;?>>
        <?=Html::a('广告价格',['price'])?>
    </li>
<!--    <li --><?// if($action == 'shape'):?><!--class="active"--><?//endif;?>
<!--        --><?//=Html::a('广告形式',['shape'])?>
<!--    </li>-->
    <li <? if($action == 'format'):?>class="active"<?endif;?>>
        <?=Html::a('广告格式',['format'])?>
    </li>
    <li <? if($action == 'duration'):?>class="active"<?endif;?>>
        <?=Html::a('广告时长',['duration'])?>
    </li>
    <li <? if($action == 'measure'):?>class="active"<?endif;?>>
        <?=Html::a('广告尺寸',['measure'])?>
    </li>
</ul>
