<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'view'):?>class="active"<?endif;?>>
        <?=Html::a('人员信息',['view','id'=>$model->id])?>
    </li>
    <li <? if($action == 'partner'):?>class="active"<?endif;?>>
        <?=Html::a('伙伴信息',['partner','id'=>$model->id])?>
    </li>
    <li <? if($action == 'shop'):?>class="active"<?endif;?>>
        <?=Html::a('商家信息',['shop','id'=>$model->id])?>
    </li>
    <li <? if($action == 'led'):?>class="active"<?endif;?>>
        <?=Html::a('LED信息',['led','id'=>$model->id])?>
    </li>
<!--    <li><a href="#five" data-toggle="tab">收益总额</a></li>-->
</ul>
