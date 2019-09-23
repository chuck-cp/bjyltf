<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\models\SystemAddress;
use cms\modules\member\models\MemberAccount;
$this->title = '我的安装任务';
$this->params['breadcrumbs'][] = $this->title;

\cms\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<?php $this->beginBody(); ?>
<div class="container">
    <table class="table table-hover" style="padding: 10px;">
        <th><h3>安装任务：</h3></th>

        <tr>
            <th>未安装店铺</th>
            <th>店铺地址</th>
            <th>屏幕数量</th>
            <th>指派时间</th>
        </tr>
        <?if(!empty($noInstallShop)):?>
            <?foreach ($noInstallShop as $v):?>
                <tr>
                    <td><?=Html::encode($v['name'])?></td>
                    <td><?=Html::encode($v['area_name'].$v['address'])?></td>
                    <td><?=Html::encode($v['screen_number'])?></td>
                    <td><?=Html::encode($v['install_assign_at'])?></td>
                </tr>
            <?endforeach;?>

        <?else:?>
            <tr>
                <td>您还没有未安装的任务</td>
            </tr>
        <?endif;?>
        <tr>
            <th>已安装店铺</th>
            <th>店铺地址</th>
            <th>屏幕数量</th>
            <th>指派时间</th>
        </tr>
        <?if(!empty($alreadyShop)):?>
            <?foreach ($alreadyShop as $v):?>
                <tr>
                    <td><?=Html::encode($v['name'])?></td>
                    <td><?=Html::encode($v['area_name'].$v['address'])?></td>
                    <td><?=Html::encode($v['screen_number'])?></td>
                    <td><?=Html::encode($v['install_assign_at'])?></td>
                </tr>
            <?endforeach;?>
        <?else:?>
            <tr>
                <td>您还没有未安装的任务</td>
            </tr>
        <?endif;?>
    </table>
    <div class="row">
        <p align="right">已安装数：<?=count($alreadyShop)?>家&nbsp;&nbsp;&nbsp;</p>
        <p align="right">未安装数：<?=count($noInstallShop)?>家&nbsp;&nbsp;&nbsp;</p>
    </div>
</div>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>














