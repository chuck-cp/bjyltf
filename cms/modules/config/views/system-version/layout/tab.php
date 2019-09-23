<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'version'):?>class="active"<?endif;?>>
        <?=Html::a('安卓版本管理',['version'])?>
    </li>
    <li <? if($action == 'ios'):?>class="active"<?endif;?>>
        <?=Html::a('IOS版本管理',['ios'])?>
    </li>
    <li <? if($action == 'pid'):?>class="active"<?endif;?>>
        <?=Html::a('PID版本管理',['pid'])?>
    </li>
</ul>
