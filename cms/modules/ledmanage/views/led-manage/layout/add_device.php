<?php
use yii\helpers\Html;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'create'):?>class="active"<?endif;?>>
        <?=Html::a('直接入库',['create','kuid'=>$kuid])?>
    </li>
    <li <? if($action == 'change-create'):?>class="active"<?endif;?>>
        <?=Html::a('调仓入库',['change-create','kuid'=>$kuid])?>
    </li>
</ul>
