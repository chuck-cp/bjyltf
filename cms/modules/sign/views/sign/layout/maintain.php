<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">

    <li <? if($action == 'maintain-time-list'):?>class="active"<?endif;?>>
        <?=Html::a('签到详情',['maintain-time-list','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'maintain-time-nosign'):?>class="active"<?endif;?>>
        <?=Html::a('未签到成员详情',['maintain-time-nosign','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'maintain-time-overtime'):?>class="active"<?endif;?>>
        <?=Html::a('超时签到详情',['maintain-time-overtime','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'maintain-time-unqualified'):?>class="active"<?endif;?>>
        <?=Html::a('未达标成员详情',['maintain-time-unqualified','date'=>$searchModel->create_at])?>
    </li>
    <li <? if($action == 'maintain-time-leave-early'):?>class="active"<?endif;?>>
        <?=Html::a('早退成员详情',['maintain-time-leave-early','date'=>$searchModel->create_at])?>
    </li>
</ul>
