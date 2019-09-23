<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
//$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 1):?>class="active"<?endif;?>>
        <?=Html::a('首页banner',['index', 'type'=>1])?>
    </li>
    <li <? if($action == 2):?>class="active"<?endif;?>>
        <?=Html::a('广告页banner',['index', 'type'=>2])?>
    </li>
</ul>
