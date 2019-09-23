<?php

use yii\helpers\Html;
use cms\modules\shop\models\Shop;
use cms\modules\sign\models\SignImage;
use cms\modules\sign\models\SignTeam;
use cms\modules\sign\models\SignBusiness;
use cms\modules\sign\models\Sign;
/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\SystemAdvert */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '业务签到管理', 'url' => ['sign-business']];
$this->params['breadcrumbs'][] = $this->title;
$SignBusiness=SignBusiness::findone(['sign_id'=>$model->id]);
?>
<div class="system-advert-view">
    <table class="table table-hover">
        <tr>
            <th colspan="4">业务员签到基本信息</th>
        </tr>
        <tr>
            <td>签到时间</td>
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
            <td>签到人名称</td>
            <td><?=$model->member_name?></td>
            <td>所属团队</td>
            <td><?echo SignTeam::find()->where(['id'=>$model->team_id])->select('team_name')->asArray()->one()['team_name']?></td>
        </tr>
        <tr>
            <td>拜访店铺</td>
            <td><?=$model->shop_name?></td>
            <td>店铺位置</td>
            <td><?=$model->shop_address?> <img style="cursor: pointer;" src="static/img/map.png" class="map" longitude="<?echo $SignBusiness->bd_longitude?>" latitude="<?echo $SignBusiness->bd_latitude?>"></td>
        </tr>
        <tr>
            <td>经度</td>
            <td><?=$SignBusiness->bd_longitude?></td>
            <td>纬度</td>
            <td><?=$SignBusiness->bd_latitude?></td>
        </tr>
        <tr>
            <td>店铺类型</td>
            <td>
                <?=Html::encode(Shop::getTypeByNum($SignBusiness->shop_type))?>
            </td>
            <td>有无屏幕</td>
            <td>
                <?=Html::encode($SignBusiness->screen_number==0?'无':'有('.$SignBusiness->screen_brand_name.':'.$SignBusiness->screen_number.'个)')?>
            </td>
        </tr>
        <tr>
            <td>最低消费</td>
            <td><?=$SignBusiness->minimum_charge?></td>
            <td>店铺面积</td>
            <td><?=$SignBusiness->shop_acreage?></td>
        </tr>
        <tr>
            <td>镜面数量</td>
            <td><?=$SignBusiness->shop_mirror_number?></td>
            <td>联系人电话</td>
            <td><?=$SignBusiness->contacts_mobile?></td>
        </tr>
        <tr>
            <td>描述</td>
            <td colspan="3"><?=$SignBusiness->description?></td>
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
                <?foreach (SignImage::signImg($model->id,1) as $v): ?>
                    <img width="150" height="auto" src="<?=Html::encode($v)?>" title="素材" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?endforeach;?>
            </td>
        </tr>
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