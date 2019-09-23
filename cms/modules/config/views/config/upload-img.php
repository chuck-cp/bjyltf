<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemBank */

$this->title = '图片上传';
$this->params['breadcrumbs'][] = ['label' => '图片上传'];
?>
<div class="system-bank-create">
    <div class="system-bank-form">
        <?php $form = ActiveForm::begin(); ?>
        <label class="control-label">上传路径</label><br />
        <input type="text" class="form-control" name="filename" value="">
        <?= $form->field($model,'upload_img_url')->widget('yidashi\uploader\SingleWidget'); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>