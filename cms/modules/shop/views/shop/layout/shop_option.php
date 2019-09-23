<?php
use yii\helpers\Html;
$controller = \Yii::$app->controller->id;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($controller == 'shop'):?>class="active"<?endif;?>>
        <?=Html::a('理发店',['shop/index'])?>
    </li>
    <li <? if($controller == 'building-shop-floor'):?>class="active"<?endif;?>>
        <?=Html::a('楼宇',['building-shop-floor/led-index'])?>
    </li>
    <li <? if($controller == 'building-shop-park'):?>class="active"<?endif;?>>
        <?=Html::a('公园',['building-shop-park/index'])?>
    </li>

</ul>
