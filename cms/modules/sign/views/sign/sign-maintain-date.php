<?php

use yii\helpers\Html;
use cms\modules\shop\models\Shop;
use cms\modules\sign\models\SignImage;
use cms\modules\sign\models\SignMaintain;
use cms\modules\sign\models\SignTeam;
use cms\modules\sign\models\Sign;
/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\SystemAdvert */

//$this->title = $teamlist['shop_name']->id;
$this->params['breadcrumbs'][] = ['label' => '业务签到管理', 'url' => ['sign-business']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?/*$teamlist = SignTeam::find()->where(['id'=>$team_id])->select('team_name')->asArray()->one();*/?>
<div class="system-advert-view">
    <h4><b>维护签到基本信息</b></h4>
    <?foreach($signlist as $key=>$value):?>
    <table class="table table-hover">
        <tr>
            <td>维护时间</td>
            <td><?=Html::encode($value['create_at'])?></td>
            <td>是否为首次签到</td>
            <td>
                <?if($value['first_sign']==1):?>
                    是
                    （<?if($value['late_time']==0):?>
                        未超时
                    <?elseif($value['late_time']<=60):?>
                        超时签到：<?=Html::encode($value['late_time'])?>分钟
                    <?else:?>
                        超时签到：<?php echo  Sign::Timechange($value['late_time'])?>
                    <?endif;?>）
                <?else:?>
                    否
                <?endif;?>
            </td>
        </tr>
        <tr>
            <td>维护签到人</td>
            <td><?=Html::encode($value['member_name'])?></td>
            <td>所属团队</td>
            <td><?=Html::encode($value['team_name'])?></td>
        </tr>
        <tr>
            <td>拜访店铺</td>
            <td><?=Html::encode($value['shop_name'])?></td>
            <td>店铺位置</td>
            <td><?=Html::encode($value['shop_address'])?> <img style="cursor: pointer;" src="static/img/map.png" class="map" longitude="<?echo $value['signMaintain']['bd_longitude']?>" latitude="<?echo $value['signMaintain']['bd_latitude']?>"></td>
        </tr>
        <tr>
            <td>经度</td>
            <td><?=Html::encode($value['signMaintain']['bd_longitude'])?></td>
            <td>纬度</td>
            <td><?=Html::encode($value['signMaintain']['bd_latitude'])?></td>
        </tr>
        <tr>
            <td>联系人</td>
            <td><?=Html::encode($value['signMaintain']['contacts_name'])?></td>
            <td>联系电话</td>
            <td><?=Html::encode($value['signMaintain']['contacts_mobile'])?></td>
        </tr>
        <tr>
            <td>店铺类型</td>
            <td><?=Html::encode(Shop::getTypeByNum($value['signMaintain']['shop_type']))?></td>
            <td>维护内容</td>
            <td>
                <?=Html::encode(SignMaintain::MaintainContent($value['signMaintain']['maintain_content']))?><br />

                调整开关机时间： <?=Html::encode($value['signMaintain']['screen_start_at'])?> 至 <?=Html::encode($value['signMaintain']['screen_end_at'])?>
            </td>
        </tr>
        <tr>
            <td>描述</td>
            <td colspan="3"><?=Html::encode($value['signMaintain']['description'])?></td>
        </tr>
    </table>
    <table class="table table-hover img">
        <tr>
            <th colspan="4">拍照图片</th>
        </tr>
        <tr>
            <td>
                <?foreach (SignImage::signImg($value['id'],2) as $v): ?>
                    <img width="150" height="auto" src="<?=Html::encode($v)?>" title="素材" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?endforeach;?>
            </td>
        </tr>
    </table>
    <?if ($value['signMaintain']['evaluate']!==0):?>
        <table class="table table-hover img">
            <tr>
                <th colspan="4">维护评价详情</th>
            </tr>
            <tr>
                <td>评价时间</td>
                <td><?=Html::encode($value['signMaintain']['evaluate_at'])?></td>
                <td>评价情况</td>
                <td>
                    <?if($value['signMaintain']['evaluate']==1):?>
                        好评
                    <?elseif ($value['signMaintain']['evaluate']==2):?>
                        中评
                    <?elseif($value['signMaintain']['evaluate']==3):?>
                        差评
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td>备注</td>
                <td colspan="2">
                    <?=$value['signMaintain']['evaluate_description'];?>
                </td>
            </tr>
        </table>
    <?endif;?>
    <?endforeach;?>
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