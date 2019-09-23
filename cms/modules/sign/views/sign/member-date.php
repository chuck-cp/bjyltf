<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

$this->title = '个人签到';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sign-member-dates">
    <span><b>个人签到详情页</b></span><span style="float: right;margin-right: 20px;"><a href="javascript:history.go(-1)">返回</a></span>
    <table class="table table-hover">
        <th style="text-align: center;">序号</th>
        <th style="text-align: center;">签到日期</th>
        <th style="text-align: center;">当日签到总次数</th>
        <th style="text-align: center;">当日签到是否达标</th>
        <th style="text-align: center;">首次签到是否超时</th>
        <th style="text-align: center;">操作</th>
        <?foreach($arraylist as $key=>$value):?>
            <tr style="text-align: center;">
                <td><?=Html::encode($key+1)?></td>
                <td><?=Html::encode($value['create_at'])?></td>
                <td><?=Html::encode($value['sign_number'])?></td>
                <td><?=Html::encode($value['qualified']==1?'是':'否')?></td>
                <td><?=Html::encode($value['late_sign']==1?'是':'否')?></td>
                <td>
                    <?if($value['sign_number'] > 0):?>
                        <?if($teamType == 1):?>
                            <?=Html::a('查看',['/sign/sign/sign-business-date','team_id'=>$value['team_id'],'member_id'=>$value['member_id'],'date'=>$value['create_at']],['target'=>'_blank'])?>
                        <?else:?>
                            <?=Html::a('查看',['/sign/sign/sign-maintain-date','team_id'=>$value['team_id'],'member_id'=>$value['member_id'],'date'=>$value['create_at']],['target'=>'_blank'])?>
                        <?endif;?>
                    <?else:?>
                        <?=Html::encode('---')?>
                    <?endif;?>
                </td>
            </tr>
        <?endforeach?>
        <tr style="text-align: center;">
            <td colspan="6">
                <?= LinkPager::widget([
                    'pagination' => $pages,
                    'nextPageLabel' => '下一页',
                    'prevPageLabel' => '上一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                ]); ?>
            </td>
        </tr>
    </table>

</div>
