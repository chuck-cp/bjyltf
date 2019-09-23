<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\modules\sign\models\SignImage;
use cms\modules\sign\models\SignTeam;
use cms\modules\sign\models\SignMaintain;
use cms\modules\sign\models\Sign;
/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\SystemAdvert */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '业务签到管理', 'url' => ['sign-business']];
$this->params['breadcrumbs'][] = $this->title;
$SignMaintain=SignMaintain::findOne(['sign_id'=>$model->id]);
?>
<div class="system-advert-view">
    <table class="table table-hover">
        <tr>
            <th colspan="4">维护签到基本信息</th>
        </tr>
        <tr>
            <td>维护时间</td>
            <td><?=$model->create_at?></td>
            <td>是否为首次签到</td>
            <td>
                <?if($model->first_sign==1):?>
                    是
                    （<?if($model->late_sign==0):?>
                        未超时
                    <?elseif($model->late_sign>=60):?>
                        超时签到：<?=Html::encode($model->late_time)?>分钟
                    <?else:?>
                        超时签到：<?php echo  Sign::Timechange($model->late_time)?>
                    <?endif;?>）
                <?else:?>
                    否
                <?endif;?>
            </td>
        </tr>
        <tr>
            <td>维护签到人</td>
            <td><?=$model->member_name?></td>
            <td>所属团队</td>
            <td><?echo SignTeam::find()->where(['id'=>$model->team_id])->select('team_name')->asArray()->one()['team_name']?></td>
        </tr>
        <tr>
            <td>拜访店铺</td>
            <td><?=$model->shop_name?></td>
            <td>店铺位置</td>
            <td><?=$model->shop_address?> <img style="cursor: pointer;" src="static/img/map.png" class="map" longitude="<?echo $SignMaintain->bd_longitude?>" latitude="<?echo $SignMaintain->bd_latitude?>"></td>
        </tr>
        <tr>
            <td>经度</td>
            <td><?=$SignMaintain->bd_longitude?></td>
            <td>纬度</td>
            <td><?=$SignMaintain->bd_latitude?></td>
        </tr>
        <tr>
            <td>联系人</td>
            <td><?=$SignMaintain->contacts_name?></td>
            <td>联系电话</td>
            <td><?=$SignMaintain->contacts_mobile?></td>
        </tr>
        <tr>
            <td>店铺类型</td>
            <td>
                <?if($SignMaintain->shop_type==1):?>
                    租赁
                <?elseif ($SignMaintain->shop_type==2):?>
                    自营
                <?else:?>
                    连锁
                <?endif;?>
            </td>
            <td>维护内容</td>
            <td><?=SignMaintain::MaintainContent($SignMaintain->maintain_content,2)?></td>
        </tr>
        <tr>
            <td>调整开关机时间</td>
            <td colspan="3"> <?=$SignMaintain->screen_start_at?> 至 <?=$SignMaintain->screen_end_at?></td>
        </tr>
        <tr>
            <td>描述</td>
            <td colspan="3"><?=$SignMaintain->description?></td>
        </tr>
        <!--<tr>
            <td>投放区域</td>
            <td colspan="3"><?/*=$model->advert_name*/?></td>
        </tr>-->
    </table>
    <table class="table table-hover img">
        <tr>
            <th colspan="4">拍照图片</th>
        </tr>
        <tr>
            <td>
                <?foreach (SignImage::signImg($model->id,2) as $v): ?>
                    <img width="150" height="auto" src="<?=Html::encode($v)?>" title="素材" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?endforeach;?>
            </td>
        </tr>
    </table>
    <?if ($SignMaintain->evaluate!==0):?>
        <table class="table table-hover img">
            <tr>
                <th colspan="4">维护评价详情</th>
            </tr>
            <tr>
                <td>评价时间</td>
                <td><?=$SignMaintain->evaluate_at?></td>
                <td>评价情况</td>
                <td>
                    <?if($SignMaintain->evaluate==1):?>
                        好评
                    <?elseif ($SignMaintain->evaluate==2):?>
                        中评
                    <?elseif($SignMaintain->evaluate==3):?>
                        差评
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td>备注</td>
                <td colspan="2">
                    <?=$SignMaintain->evaluate_description;?>
                </td>
            </tr>
        </table>
    <?endif;?>

</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.img').viewer({
        url: 'src',
    });
    $('.map').click(function(){
        var longitude=$(this).attr('longitude');
        var latitude=$(this).attr('latitude');
        var pageup = layer.open({
            type: 2,
            title: '地图',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/sign/sign/map'])?>&longitude='+longitude+'&latitude='+latitude
        });
    })
</script>