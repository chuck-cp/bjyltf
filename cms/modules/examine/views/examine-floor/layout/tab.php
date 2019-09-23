<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'led-index'):?>class="active"<?endif;?>>
        <?=Html::a('LED',['led-index'])?>
    </li>
    <li <? if($action == 'poster-index'):?>class="active"<?endif;?>>
        <?=Html::a('画报',['poster-index'])?>
    </li>
</ul>
