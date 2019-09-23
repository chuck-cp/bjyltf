<?php
use yii\helpers\Html;
$controller = \Yii::$app->controller->id;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'offline-an'):?>class="active"<?endif;?>>
        <?=Html::a('理发店',['/examine/install/offline-an'])?>
    </li>
    <li <? if($action == 'led-index' || $action == 'poster-index'):?>class="active"<?endif;?>>
        <?=Html::a('楼宇',['/examine/install-floor/led-index'])?>
    </li>
    <li <? if($action == 'index'):?>class="active"<?endif;?>>
        <?=Html::a('公园',['/examine/install-park/index'])?>
    </li>

</ul>
