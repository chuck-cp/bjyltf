<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

cms\assets\AppAsset::register($this);

$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

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

<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_password', [
        'model' => $model,
    ]) ?>

</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
