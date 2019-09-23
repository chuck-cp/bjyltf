<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">

    <li <? if($action == 'business-time-list'):?>class="active"<?endif;?>>
        <?=Html::a('签到详情',['business-time-list','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'business-time-nosign'):?>class="active"<?endif;?>>
        <?=Html::a('未签到成员详情',['business-time-nosign','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'business-time-overtime'):?>class="active"<?endif;?>>
        <?=Html::a('超时签到详情',['business-time-overtime','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'business-time-unqualified'):?>class="active"<?endif;?>>
        <?=Html::a('未达标成员详情',['business-time-unqualified','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'business-time-leave-early'):?>class="active"<?endif;?>>
        <?=Html::a('早退成员详情',['business-time-leave-early','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'business-time-repeat-sign'):?>class="active"<?endif;?>>
        <?=Html::a('重复签到详情',['business-time-repeat-sign','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'business-time-repeat-shop'):?>class="active"<?endif;?>>
        <?=Html::a('重复店铺详情',['business-time-repeat-shop','date'=>$searchModel->create_at])?>
    </li>
</ul>
