<?php
use yii\helpers\Html;
use cms\modules\shop\models\BuildingShopFloor;
use cms\modules\shop\models\BuildingCompany;
use cms\modules\shop\models\BuildingPositionConfig;
/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\BuildingShopFloor */
$this->params['breadcrumbs'][] = ['label' => '楼宇LED详情', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-shop-floor-view">
    <ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
        <?foreach ($label as $k=>$v):?>
            <li <?if($position_id==$v['position_id']):?> class="active"<?endif;?>>
                <a href="<?=\yii\helpers\Url::to(['/shop/building-shop-floor/install-poster-view','id'=>$id,'position_id'=>$v['position_id']])?>" ><?echo BuildingPositionConfig::getPositionName($v['position_id'])?></a>
            </li>
        <?endforeach;?>
    </ul>
    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->
    <h4 style="font-weight: bold;">LED</h4>
    <table class="table table-striped table-bordered">
        <?foreach ($data as $sk=>$sv):?>
            <tr><td colspan="4"><?echo $sv['position_name']?>&nbsp;&nbsp;&nbsp;&nbsp; <?if($sv['monopoly']==1):?>独占<?else:?>非独占<?endif;?></td></tr>
            <?php unset($sv['position_name'])?>
            <?php unset($sv['monopoly'])?>
            <?php unset($sv['number'])?>
            <?if (is_array($sv)):?>
                <tr>
                <?foreach ($sv as $kk=>$vv):?>
                    <td>
                        <img width="150" height="auto" src="<?=Html::encode($vv['image_url'])?>" title="平面结构图" alt="">
                        <p class="park-view"><span>设备号：</span>  <?echo $vv['device_number']?></p>
                        <p class="park-view"><span>安装位置：</span> <?echo $vv['position_name']?></p>
                    </td>
                    <?if(($kk+1)%2==0):?></tr><tr><?endif;?>
                <?endforeach;?>
                </tr>
            <?endif;?>
        <?endforeach;?>
    </table>
</div>
<style>
    .park-view{
        position:relative;
        top:-133px;
        left:184px;
    }
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/jquery.media.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.panel-body').viewer({
        url: 'src',
    });
</script>
