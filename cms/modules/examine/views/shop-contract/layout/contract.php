<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'index'):?>class="active"<?endif;?>>
        <?=Html::a('商家合同',['index','searchModel'=>$searchModel])?>
    </li>
    <li <? if($action == 'head-contract'):?>class="active"<?endif;?>>
        <?=Html::a('总部合同',['head-contract','searchModel'=>$searchModel])?>
    </li>

</ul>
