<?php
use yii\grid\GridView;
use yii\helpers\Html;
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
<div class="system-version-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'app_type' => $model->app_type,
    ]) ?>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage()?>