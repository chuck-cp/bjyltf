<?php

use cms\modules\examine\models\Activity;
use cms\models\LogOperation;
/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\SystemAdvert */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '店铺推荐信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$activity=Activity::findOne(['id'=>$model->activity_id]);
$LogOperation=LogOperation::find()->where(['foreign_id'=>$model->id,'operation_type'=>1])->asArray()->all();
?>
<div class="system-advert-view">
    <table class="table table-hover">
        <th colspan="2" style="background-color: #5e87b0;color: #fff;">基本信息</th>
        <tr>
            <td>店铺名称</td>
            <td><?=$model->shop_name?></td>
        </tr>
        <tr>
            <td>店铺地址</td>
            <td><?=$model->area_name.$model->address?></td>
        </tr>
        <tr>
            <td>店铺联系人</td>
            <td><?=$model->apply_name?></td>
        </tr>
        <tr>
            <td>店铺联系电话</td>
            <td><?=$model->apply_mobile?></td>
        </tr>
        <tr>
            <td>状态</td>
            <td>
                <?if($model->status==0):?>
                    未签约
                <?elseif($model->status==1):?>
                    已签约
                <?elseif($model->status==3):?>
                    已签约
                <?else:?>
                    签约失败
                <?endif;?>
            </td>
        </tr>
        <tr>
            <td>店铺照片</td>
            <td>
                <img style="cursor: pointer;" src="<?=$model->shop_image?>" width="10%" class="map">
            </td>
        </tr>
        <th colspan="2" style="background-color: #5e87b0;color: #fff;">推荐信息</th>
        <tr>
            <td>推荐人</td>
            <td><?=$activity->member_name?></td>
        </tr>
        <tr>
            <td>推荐账号</td>
            <td><?=$activity->member_name?></td>
        </tr>
        <tr>
            <td>推荐时间</td>
            <td><?=$model->create_at?></td>
        </tr>
        <th colspan="2" style="background-color: #5e87b0;color: #fff;">对接信息</th>
        <tr>
            <td>对接业务合作人</td>
            <td><?=$model->custom_member_name?></td>
        </tr>
        <tr>
            <td>对接业务合作人账号</td>
            <td><?=$model->custom_member_name?></td>
        </tr>
        <tr>
            <td>对接业务合作人办事处</td>
            <td></td>
        </tr>
        <th colspan="2" style="background-color: #5e87b0;color:#fff">业务合作人备注</th>
        <?if(!empty($LogOperation)):?>
            <?foreach ($LogOperation as $v):?>
                <tr>
                    <td><?echo $v['create_at']?></td>
                    <td><?echo $v['content']?></td>
                </tr>
            <?endforeach;?>
        <?endif;?>
    </table>
    <hr>
</div>
<div id="allmap"></div>
<style type="text/css">
    body, html{width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
    #allmap{height:500px;width:100%;}
    #r-result{width:100%; font-size:14px;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.table').viewer({
        url: 'src',
    });
</script>