<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">

    <li <? if($action == 'install-information'):?>class="active"<?endif;?>>
        <?=Html::a('安装屏幕信息',['install-information','id'=>$model->id])?>
    </li>
    <li <? if($action == 'changescreenlist'):?>class="active"<?endif;?>>
        <?=Html::a('更换屏幕信息',['changescreenlist','id'=>$model->id])?>
    </li>
</ul>
