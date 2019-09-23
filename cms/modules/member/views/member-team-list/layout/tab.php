<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
$action = \Yii::$app->controller->action->id;

?>
<div class="member-team-view">
    <h2>团队信息：</h2>
    <table class="table table-hover">
        <tr>
            <td>团队名称：</td>
            <td>
                <?=Html::encode($teamObj->team_name)?>
            </td>
            <td>组长：</td>
            <td>
                <?=Html::encode($teamObj->team_member_name)?>
            </td>
        </tr>
        <tr>
            <td>联系方式：</td>
            <td>
                <?=Html::encode($mobile)?>
            </td>
            <td>组员人数：</td>
            <td>
                <?=Html::encode($teamObj->team_member_number)?>
            </td>
        </tr>
        <tr>
            <td>已安装店铺数：</td>
            <td>
                <?=Html::encode($teamObj->install_shop_number)?>
            </td>
            <td>未安装店铺数：</td>
            <td>
                <?=Html::encode($teamObj->not_install_shop_number)?>
            </td>
        </tr>
        <tr>
            <td>未指派安装店铺数：</td>
            <td>
                <?=Html::encode($teamObj->not_assign_shop_number)?>
            </td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
<ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
    <li <? if($action == 'index'):?>class="active"<?endif;?>>
        <?=Html::a('安装成员列表',['index','team_id'=>$member_id,'teamObj'=>$teamObj,'mobile'=>$mobile])?>
    </li>
    <li <? if($action == 'record'):?>class="active"<?endif;?>>
        <?=Html::a('团队指派记录',['record','team_id'=>$member_id,'teamObj'=>$teamObj,'mobile'=>$mobile])?>
    </li>
</ul>
