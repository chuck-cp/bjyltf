<?php
use yii\grid\GridView;
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
        'id',
        [
            'label' => '状态',
            'value' => function($screenModel){
                return \cms\modules\screen\models\Screen::getScreenStatus($screenModel->status);
            }
        ],
        'offline_time',
    ]
])?>
<?php $this->endBody() ?>
</body>
    </html >
<?php $this->endPage() ?>