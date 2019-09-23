<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '每笔提现留存';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="system-config-index">
    <div class="row">
        <div class="col-md-2 yw">
            每笔提现留存：
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'advert_price_reserved')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
        <div class="col-md-3" style="line-height: 40px;">
            <span>%</span>
        </div>
    </div>
    <div class="xiugaidiv">
<!--        --><?//= Html::Button('修改', ['class' => 'btn btn-primary xiugai']) ?>
<!--    </div>-->
<!--    <div class="baocundiv" style="display: none;">-->
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary baocun']) ?>
    </div>

</div>
<?php \yii\widgets\ActiveForm::end(); ?>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
//    $(".xiugai").click(function(){
//        $('.form-control').removeAttr("readonly");
//        $(".xiugaidiv").hide();
//        $(".baocundiv").show();
//    });
</script>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
    }
</style>