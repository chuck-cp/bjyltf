<?php
use yii\helpers\Html;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin: 10px;">
    <li <? if($action == 'batchs'):?>class="active"<?endif;?>>
        <?=Html::a('个人',['batchs','kuid'=>$kuid])?>
    </li>
    <li <? if($action == 'batchs-offices'):?>class="active"<?endif;?>>
        <?=Html::a('调货',['batchs-offices','kuid'=>$kuid])?>
    </li>
</ul>
