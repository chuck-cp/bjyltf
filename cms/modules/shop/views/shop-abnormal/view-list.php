<?php
use yii\grid\GridView;
use common\libs\ToolsClass;
\cms\assets\AppAsset::register($this);
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
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
<?= \cms\core\CmsGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        // ['class' => 'yii\grid\CheckboxColumn'],
        [
            'label'=>'ID',
            'value'=>function($searchModel){
                return $searchModel->id;
            }
        ],
        [
            'label'=>'日期',
            'value'=>function($searchModel){
                return $searchModel->date;
            }
        ],
        [
            'label'=>'屏幕软件编码',
            'value'=>function($searchModel){
                return $searchModel->software_number;
            }
        ],
        [
            'label'=>'店铺名称',
            'value'=>function($searchModel) use ($shop_name){
                return $shop_name;
            }
        ],
        [
            'label'=>'屏幕运行时长(小时)',
            'value'=>function($searchModel){
                return round($searchModel->time/60/60,2);
            }
        ],
    ]
])?>
<?php $this->endBody() ?>
<style>
    .col-xs-2{
        margin-right: 80px;
    }
</style>
</body>
</html >
<?php $this->endPage() ?>
