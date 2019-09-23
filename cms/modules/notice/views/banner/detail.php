<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\notice\models\search\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banner管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="system-banner-index">
    <div class="row">
        <div class="row col-md-3" style="margin-left: 15px">
            <?= $form->field($model,'image_url')->widget('yidashi\uploader\SingleWidget')->label('图片：'); ?>
        </div>
        <div class="row col-md-7" style="margin-top: 28px;">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">网址：</label>
                    <div class="col-sm-5">
                        <?=$form->field($model,'link_url')->textInput(['class'=>'form-control'])->label(false)?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">排序：</label>
                    <div class="col-sm-1" style="margin-left: 15px">
                        <?=$form->field($model,'sort')->dropDownList(['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7],['class'=>'form-control'])->label(false)?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-sm-5" style="margin-top: 30px;margin-left: 26px;">
            <button type="submit" class="btn btn-primary ck" data-type="pass">保存</button>
            <button type="button" class="btn btn-danger ck" data-type="rebut">取消</button>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
