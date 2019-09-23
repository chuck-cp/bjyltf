<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\modules\schedules\models\SystemAdvert;
/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\SystemAdvert */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'System Adverts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-advert-view">
    <table class="table table-hover">
        <tr>
            <th colspan="4">广告基本信息</th>
        </tr>
        <tr>
            <td>广告位</td>
            <td><?=SystemAdvert::getAdvertPositionKey($model->advert_position_key)?></td>
            <td>广告状态</td>
            <td>
                <?if($model->throw_status==0):?>
                    未推送
                <?elseif ($model->throw_status==1):?>
                    已推送
                <?elseif ($model->throw_status==2):?>
                    投放完成
                <?endif;?>
            </td>
        </tr>
        <tr>
            <td>广告名称</td>
            <td><?=$model->advert_name?></td>
            <td>店铺名称</td>
            <td><?=$model->shop_name?></td>
        </tr>
        <tr>
            <td>链接地址</td>
            <td><?=$model->link_url?></td>
            <td>投放时间</td>
            <td><?=$model->start_at?> 至 <?=$model->end_at?></td>
        </tr>
        <tr>
            <td>投放时长</td>
            <td><?=$model->advert_time?></td>
            <td>投放频次</td>
            <td><?=$model->throw_rate?></td>
        </tr>
        <tr>
            <td>投放区域</td>
            <td colspan="3">
                <?php echo implode(',',SystemAdvert::areaView($model->id))?>
            </td>
        </tr>
        <!--<tr>
            <td>投放区域</td>
            <td colspan="3"><?/*=$model->advert_name*/?></td>
        </tr>-->
    </table>
    <table class="table table-hover img">
        <tr>
            <th colspan="4">广告素材信息</th>
        </tr>
        <tr>
            <td>
                <?if(in_array($model->advert_position_key,['A1','A2'])):?>
                    <video controls  width="480" height="260" ><source src='<?echo $model->image_url?>' type="video/mp4" />  </video>
                <?else:?>
                    <img width="150" height="auto" src="<?=Html::encode($model->image_url)?>" title="素材" alt="">
                <?endif;?>
            </td>
        </tr>
    </table>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.img').viewer({
        url: 'src',
    });
</script>