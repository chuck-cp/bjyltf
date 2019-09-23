<?php
use yii\helpers\Html;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin: 10px;">
    <li <? if($action == 'single'):?>class="active"<?endif;?>>
        <?=Html::a('个人',['single','kuid'=>$kuid,'deviceid'=>$deviceid])?>
    </li>
    <li <? if($action == 'single-offices'):?>class="active"<?endif;?>>
        <?=Html::a('办事处',['single-offices','kuid'=>$kuid,'deviceid'=>$deviceid])?>
    </li>
</ul>
