<?php
use yii\helpers\Html;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'detail'):?>class="active"<?endif;?>>
        <?=Html::a('订单详情',['detail','id'=>$id])?>
    </li>
    <!--<li <?/* if($action == 'business-time-nosign'):*/?>class="active"<?/*endif;*/?>>
        <?/*=Html::a('投放地区',['business-time-nosign','id'=>''])*/?>
    </li>-->
    <li <? if($action == 'arrival-rate-report'):?>class="active"<?endif;?>>
        <?=Html::a('到达率报告',['arrival-rate-report','id'=>$id])?>
    </li>
    <li <? if($action == 'broadcast-rate-report'):?>class="active"<?endif;?>>
        <?=Html::a('播放率报告',['broadcast-rate-report','id'=>$id])?>
    </li>
    <li <? if($action == 'rate-report'):?>class="active"<?endif;?>>
        <?=Html::a('监播报告',['rate-report','id'=>$id])?>
    </li>
</ul>
