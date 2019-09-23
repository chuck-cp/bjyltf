<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '推送节目';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([]); ?>
<div class="row">
    <div class="col-xs-2 form-group">
        <label>店铺id：</label>
        <input type="text" class="form-control fm" name="shop_id" aria-required="true" aria-invalid="false" oninput = "value=value.replace(/[^\d]/g,'')"/>
    </div>
    <div class="col-xs-2 form-group">
        <label>总部id：</label>
        <input type="text" class="form-control fm" name="head_id" aria-required="true" aria-invalid="false" oninput = "value=value.replace(/[^\d]/g,'')"/>
    </div>
    <div class="col-xs-2 form-group">
        <label>分类：</label>
        <select  class="form-control fm" name="type" aria-required="true" aria-invalid="false">
            <option value="test">测试</option>
            <option value="">正式</option>
        </select>
    </div>
    <div class="col-xs-2 form-group">
        <?=Html::submitButton('推送单个',['class'=>'btn btn-primary','name'=>'advice','value'=>1])?>
        <?=Html::submitButton('推送全部',['class'=>'btn btn-primary','name'=>'advice','value'=>2])?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div class="system-config-querys">
    <h5>推送结果：</h5>
    <p>推送单个</p><?=Html::encode($resone)?>
    </br>
    <p>全部店铺</p><?=Html::encode($resalls)?>
</div>
<style type="text/css">

</style>