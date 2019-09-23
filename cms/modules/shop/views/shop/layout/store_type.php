<?php
use yii\helpers\Html;
$action = \Yii::$app->controller->action->id;
?>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">

    <li <? if($action == 'signing-shop'):?>class="active"<?endif;?>>
        <?=Html::a('签约店铺',['signing-shop','create_at_start'=>$searchModel->create_at_start,'create_at_end'=>$searchModel->create_at_end,'areas'=>$searchModel->areas])?>
    </li>
    <li <? if($action == 'install-shop'):?>class="active"<?endif;?>>
        <?=Html::a('安装店铺',['install-shop','create_at_start'=>$searchModel->create_at_start,'create_at_end'=>$searchModel->create_at_end,'areas'=>$searchModel->areas])?>
    </li>
</ul>
