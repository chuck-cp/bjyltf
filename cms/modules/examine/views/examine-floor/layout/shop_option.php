<?php
use yii\helpers\Html;
$controller = \Yii::$app->controller->id;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'claim'):?>class="active"<?endif;?>>
        <?=Html::a('理发店',['/examine/examine/claim'])?>
    </li>
    <li <? if($action == 'floor-claim-led' || $action == 'floor-claim-poster'):?>class="active"<?endif;?>>
        <?=Html::a('楼宇',['/examine/examine-floor/floor-claim-led'])?>
    </li>
    <li <? if($action == 'park-claim'):?>class="active"<?endif;?>>
        <?=Html::a('公园',['/examine/examine-park/park-claim'])?>
    </li>

</ul>
