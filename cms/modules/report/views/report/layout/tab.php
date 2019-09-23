<?php
use yii\helpers\Html;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'order-view'):?>class="active"<?endif;?>>
        <?=Html::a('订单详情',['order-view','id'=>$id])?>
    </li>
    <li <? if($action == 'business-time-nosign'):?>class="active"<?endif;?>>
        <?=Html::a('投放地区',['business-time-nosign','id'=>''])?>
    </li>
    <li <? if($action == 'arrivalratereport'):?>class="active"<?endif;?>>
        <?=Html::a('到达率报告',['arrivalratereport','id'=>$id])?>
    </li>
    <li <? if($action == 'business-time-unqualified'):?>class="active"<?endif;?>>
        <?=Html::a('播放率报告',['business-time-unqualified','date'=>''])?>
    </li>
</ul>
