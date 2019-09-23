<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">

    <li <? if($action == 'bank'):?>class="active"<?endif;?>>
        <?=Html::a('个人账户',['bank','id'=>$model->id])?>
    </li>
    <li <? if($action == 'combank'):?>class="active"<?endif;?>>
        <?=Html::a('对公账户',['combank','id'=>$model->id])?>
    </li>
</ul>
