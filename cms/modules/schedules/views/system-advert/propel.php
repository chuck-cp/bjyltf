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
        <label>推送店铺：</label>
<!--        <input type="text" class="form-control fm" name="shop_id" aria-required="true" aria-invalid="false" value=""/>-->
        <select  class="form-control fm" name="shop_id" aria-required="true" aria-invalid="false" >
            <option value="">全部</option>
            <?foreach ($shopAd as $key=>$value): ?>
            <option value="<?=Html::encode($value['shop_id']);?>"><?=Html::encode($value['description'].','.$value['shop_id']);?></option>
            <?endforeach;?>
        </select>
    </div>
    <div class="col-xs-2 form-group" style="width: 300px;">
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