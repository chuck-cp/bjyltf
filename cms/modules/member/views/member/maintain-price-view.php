<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $model cms\models\ScreenRunTimeShopSubsidy */
cms\assets\AppAsset::register($this);

$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>

<div class="screen-run-time-shop-subsidy-view">
    <table style="border: 1px solid #dddddd; width: 19%; margin:20px 0 0 10px;text-align: center;" >
        <tr>
            <th style="text-align: center;" >
                <p style="margin-top: 10px;font-size: 16px;">
                    费用总计：<?echo $TotalPrice?>元
                </p>
            </th>
        </tr>
    </table>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'ID',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '屏幕编号',
                'value' => function($searchModel){
                    return $searchModel->software_number;
                }
            ],
            [
                'label' => '屏幕周期内开启天数',
                'value' => function($searchModel){
                    return $searchModel->number;
                }
            ],
            [
                'label' => '屏幕维护费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->price);
                }
            ],
            [
                'label' => '应发维护费',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->reduce_price);
                }
            ],
        ],
    ]); ?>

</div>
<?php $this->endBody() ?>
</body>

<?php $this->endPage() ?>