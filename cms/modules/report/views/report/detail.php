<?php
use yii\helpers\Html;
use yii\helpers\Url;
use cms\modules\member\models\Order;
use common\libs\ToolsClass;
use cms\modules\member\models\Member;
use cms\modules\member\models\OrderDate;
use cms\modules\member\models\OrderArea;
use yii\widgets\LinkPager;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
$this->beginBlock('AppPage');
//$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="container">
    <h4>基本信息：</h4>
    <table class="table table-bordered">
        <input type="hidden" class="orderid" name="order_id" value="<?=Html::encode($model->id)?>"/>
        <tr>
            <td>订单号：</td>
            <td>
                <?=Html::encode($model->order_code)?>
            </td>
            <td>广告购买人：</td>
            <td>
                <?=Html::encode($model->member_name)?>
            </td>
            <td>购买人所在区域：</td>
            <td>
                <?=Html::encode($model->area_name)?>
            </td>
        </tr>
        <tr>
            <td>业务合作人：</td>
            <td>
                <?=Html::encode($model->salesman_name)?> <a target="_blank" href="<?=Url::to(['/member/member/view', 'id'=>$model->salesman_id])?>">查看</a>
            </td>
            <td>广告位：</td>
            <td>
                <?=Html::encode($model->advert_name)?>
            </td>
            <td>广告时长：</td>
            <td>
                <?=Html::encode($model->advert_time)?>
            </td>
        </tr>
        <tr>
            <td>投放频次：</td>
            <td>
                <?=Html::encode($model->rate)?>
            </td>
            <td>购买天数：</td>
            <td>
                <?=Html::encode($model->total_day)?>
            </td>
            <td>投放开始日期：</td>
            <td>
                <?=Html::encode(OrderDate::getOrderDate($model->id))?>
            </td>
        </tr>
        <tr>
            <td>投放地区：</td>
            <td colspan="5">
                <?if($model->deal_price == 0):?>
                    <a href="javascript:void(0);">无购买地区</a>
                <? else:?>
                    <a target="_blank" href="<?=Url::to(['/report/report/schedule', 'id'=>$model->id])?>">点击查看投放地区列表</a>
                <?endif;?>
<!--                <div class="dropdown">-->
<!--                <a target="_blank" href="--><?//=Url::to(['/report/report/schedule', 'id'=>$model->id])?><!--">点击查看投放地区列表</a>-->
<!--                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">-->
<!--                        --><?// foreach (OrderArea::getAdvertAreaes($model->id) as $v):?>
<!--                            <li role="presentation">-->
<!--                                <a role="menuitem" tabindex="-1" href="#">-->
<!--                                    --><?//= Html::encode($v)?>
<!--                                </a>-->
<!--                            </li>-->
<!--                        --><?// endforeach;?>
<!--                    </ul>-->
<!--                </div>-->
            </td>
        </tr>
    </table>
<!--    <h4>播放信息：</h4>-->
<!--    <table class="table table-bordered">-->
<!--        <tr>-->
<!--            <td class="fir">播放状态：</td>-->
<!--            <td>-->
<!--                --><?//=Html::encode($model->examine_status == 4 ? '播放中' : '已完成') ?>
<!--            </td>-->
<!--            <td class="fir">播放频次：</td>-->
<!--            <td>-->
<!--                --><?//=Html::encode(($model->number).'次/小时')?>
<!--            </td>-->
<!--            <td class="fir">屏幕数量：</td>-->
<!--            <td>-->
<!--                --><?//=Html::encode($model->screen_number)?>
<!--            </td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td class="fir">播放总次数：</td>-->
<!--            <td>-->
<!--                --><?//=Html::encode(($model->rate)*($model->total_day)*($model->screen_number))?>
<!--            </td>-->
<!--            <td class="fir">已播次数：</td>-->
<!--            <td>-->
<!--                --><?//if($total):?>
<!--                    --><?//=Html::encode($total)?>
<!--                --><?//else:?>
<!--                    0-->
<!--                --><?//endif;?>
<!--            </td>-->
<!--            <td class="fir"></td>-->
<!--            <td>-->
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>