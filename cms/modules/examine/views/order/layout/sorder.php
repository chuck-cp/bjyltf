<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'shop-order'):?>class="active"<?endif;?>>
        <?=Html::a('商家广告',['shop-order','searchModel'=>$searchModel])?>
    </li>
    <li <? if($action == 'head-order'):?>class="active"<?endif;?>>
        <?=Html::a('总部广告',['head-order','searchModel'=>$searchModel])?>
    </li>

</ul>
