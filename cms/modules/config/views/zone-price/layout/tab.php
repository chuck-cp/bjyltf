<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'zone' || $action == 'view' || $action == 'create'):?>class="active"<?endif;?>>
        <?=Html::a('区域买断费用',['zone'])?>
    </li>
    <li <? if($action == 'subsidy'|| $action == 'subview' || $action == 'subcreate'):?>class="active"<?endif;?>>
        <?=Html::a('每日补贴费用',['subsidy'])?>
    </li>
</ul>