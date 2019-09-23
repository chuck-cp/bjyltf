<?php

use yii\helpers\Html;
use cms\modules\shop\models\BuildingShopFloor;
use cms\modules\shop\models\BuildingCompany;
/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\BuildingShopFloor */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '楼宇画报详情', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?$buildingCompanyModel = BuildingCompany::findOne(['id'=>$model->company_id])?>
<div class="building-shop-floor-view">
    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->
    <h4 style="font-weight: bold;">公园基本信息(画报)</h4>
    <table class="table table-striped table-bordered">
        <tr>
            <td>公园名称</td>
            <td><?=Html::encode($model->shop_name)?></td>
            <td>公园ID</td>
            <td><?=Html::encode($model->id)?></td>
            <td>公园等级</td>
            <td><?=Html::encode($model->shop_level)?></td>
        </tr>
        <tr>
            <td>地址</td>
            <td><?=Html::encode($model->address)?></td>
            <td>联系人</td>
            <td><?=Html::encode($model->contact_name)?></td>
            <td>联系电话</td>
            <td><?=Html::encode($model->contact_mobile)?></td>
        </tr>
        <tr>
            <td>画报数量</td>
            <td><?=Html::encode($model->poster_screen_number)?></td>
            <td>申请时间</td>
            <td><?=Html::encode($model->poster_create_at)?></td>
            <td>审核通过时间</td>
            <td><?=Html::encode($model->poster_examine_at)?></td>
        </tr>
        <tr>
            <td>申请状态</td>
            <td><?=Html::encode(BuildingShopFloor::getStatusfloor($model->poster_examine_status))?></td>
            <td>安装完成时间</td>
            <td><?=Html::encode($model->poster_install_finish_at)?></td>
            <td>买断费用</td>
            <td colspan="5"><?=Html::encode($model->poster_install_price)?></td>
        </tr>
        <tr>
            <td>公司名称</td>
            <td><?=html::encode($buildingCompanyModel->company_name)?></td>
            <td>公司地址</td>
            <td><?=html::encode($buildingCompanyModel->address)?></td>
            <td>统一社会信用码</td>
            <td><?=html::encode($buildingCompanyModel->registration_mark)?></td>
        </tr>
        <tr>
            <td>申请人</td>
            <td><?=html::encode($buildingCompanyModel->apply_name)?></td>
            <td>申请电话</td>
            <td><?=html::encode($buildingCompanyModel->apply_mobile)?></td>
            <td>业务员姓名</td>
            <td><?=html::encode($buildingCompanyModel->member_name)?></td>
        </tr>
        <tr>
            <td>联系方式</td>
            <td><?=html::encode($buildingCompanyModel->member_mobile)?></td>
            <td>合同附件</td>
            <td>预览 下载</td>
            <td>安装信息</td>
            <td>安装详情</td>
        </tr>
    </table>
    <h4 style="font-weight: bold;">物业照片信息</h4>
    <table class="table table-hover">
        <tr>
            <td>营业执照信息</td>
        </tr>
        <tr>
            <td>
                <?php if($buildingCompanyModel->business_licence):?>
                    <img width="150" height="auto" src="<?=Html::encode($buildingCompanyModel->business_licence)?>" title="营业执照图片" alt="">
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <td>其他</td>
        </tr>
        <tr>
            <td>
                <?if($buildingCompanyModel->other_image):?>
                    <?foreach (explode(',',$buildingCompanyModel->other_image) as $v):?>
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($v)?>" alt="">
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
    </table>
    <h4 style="font-weight: bold;">公园照片信息</h4>
    <table class="table table-hover">
        <tr>
            <td>公园外观照</td>
            <td>平面结构图</td>
        </tr>
        <tr>
            <td>
                <?php if($model->shop_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->shop_image)?>" title="楼宇外观照" alt="">
                <?php endif;?>
            </td>
            <td>
                <?php if($model->plan_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->plan_image)?>" title="平面结构图" alt="">
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <td colspan="2">其他</td>
        </tr>
        <tr>
            <td >
                <?if($model->other_image):?>
                    <?foreach (explode(',',$model->other_image) as $vv):?>
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vv)?>" alt="">
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
    </table>
    <h4 style="font-weight: bold;">安装信息</h4><Br />
    <?foreach ($InstallDatas as $k=>$v):?>
        <table class="table table-hover">
            <p style="margin-left: 0.5% ;font-size: 16px;font-weight: bold;"><?/* echo $v['floor_number']*/?><? echo $v['position_name']?></p>
            <tr>
                <?foreach ($v as $kk=>$vv):?>
                    <?if (is_array($vv)):?>
                        <td>
                            <img width="150" height="auto" src="<?=Html::encode($vv['image_url'])?>" title="平面结构图" alt="">
                            <p class="park-view"><span>设备号：</span>  <?echo $vv['device_number']?></p>
                            <p class="park-view"><span>安装位置：</span> <?echo $vv['position_name']?></p>
                        </td>
                        <?if(($kk+1)%3==0):?></tr><tr><?endif;?>
                    <?endif;?>
                <?endforeach;?>
            </tr>
        </table>
    <?endforeach;?>
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
