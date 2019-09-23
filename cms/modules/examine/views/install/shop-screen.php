<?php
use yii\grid\GridView;
use common\libs\ToolsClass;
\cms\assets\AppAsset::register($this);
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
        ['class' => 'yii\grid\CheckboxColumn'],
        //'id',
        'number',
        [
            'label' => '状态',
            'value' => function($screenModel){
                return \cms\modules\screen\models\Screen::getScreenStatus($screenModel->status);
            }
        ],
        //'offline_time',
        [
            'label' => '离线时间',
            'value' => function($screenModel){
                if($screenModel->status == 0){
                    return '---';
                }elseif($screenModel->status == 1){
                    return '0000-00-00 00:00:00';
                }else{
                    return ToolsClass::timediff(time(),strtotime($screenModel->offline_time));
                }
            }
        ]
    ]
])?>
<?php $this->endBody() ?>
</body>
    </html >
<?php $this->endPage() ?>