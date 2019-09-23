<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
\pms\assets\AppMinAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="members-role-index" style="padding: 10px">
    <?php $form = \yii\widgets\ActiveForm::begin(); ?>
    <?= $form->field($model, 'desc')->textarea(['maxlength' => 255,'rows'=>15]) ?>
    <?= Html::submitButton(Yii::t('yii','submit'), ['class' => 'btn btn-primary']) ?>
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
