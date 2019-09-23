<?php
use yii\helpers\Html;
$controller = \Yii::$app->controller->id;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'offline-shop'):?>class="active"<?endif;?>>
        <?=Html::a('理发店',['/examine/examine/offline-shop'])?>
    </li>
    <li <? if($action == 'led-index' || $action == 'poster-index'):?>class="active"<?endif;?>>
        <?=Html::a('楼宇',['/examine/examine-floor/led-index'])?>
    </li>
    <li <? if($action == 'index'):?>class="active"<?endif;?>>
        <?=Html::a('公园',['/examine/examine-park/index'])?>
    </li>
</ul>
