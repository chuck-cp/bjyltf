<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '提现验证配置';
$this->params['breadcrumbs'][] = $this->title;
$action = \Yii::$app->controller->action->id;
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="system-config-index">
    <ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
        <li <? if($action == 'bonus'):?>class="active"<?endif;?>>
            <?=Html::a('广告业务提成',['bonus'])?>
        </li>
        <li <? if($action == 'contact-shop-bonus'):?>class="active"<?endif;?>>
            <?=Html::a('联系店铺提成',['contact-shop-bonus'])?>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-3 yw">
            联系小规模（2块镜面）<span>买断费：</span>
            <p>联系小规模（2块镜面）<span>每月补助费：</span></p>
        </div>
        <div class="col-md-2">
         <?= $form->field($model, 'small_shop_price_first_install_apply')->textInput(['value'=>$model->small_shop_price_first_install_apply/100,'class'=>'form-control'])->label(false) ?>
         <?= $form->field($model, 'small_shop_subsidy_price')->textInput(['value'=>$model->small_shop_subsidy_price/100,'class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 yw">
            联系小规模（2块镜面）<span>业务员提成金额：</span>
            <p>联系小规模（2块镜面）<span>业务员上级提成金额：</span></p>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'small_shop_price_first_install_salesman')->textInput(['value'=>$model->small_shop_price_first_install_salesman/100,'class'=>'form-control'])->label(false) ?>
            <?= $form->field($model, 'small_shop_price_first_install_salesman_parent')->textInput(['value'=>$model->small_shop_price_first_install_salesman_parent/100,'class'=>'form-control'])->label(false) ?>
        </div>
    </div>

    <hr/>
    <div class="row">
        <div class="col-md-3 yw">
            联系店铺业务合作费（内部）<span>业务合作费：</span>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'shop_contact_price_inside_self')->textInput(['value'=>$model->shop_contact_price_inside_self/100,'class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 yw">
            联系店铺上级提成金额（内部）<span>提成金额：</span>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'shop_contact_price_inside_parent')->textInput(['value'=>$model->shop_contact_price_inside_parent/100,'class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-3 yw">
            联系店铺业务合作费（外部）<span>业务合作费：</span>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'shop_contact_price_outside_self')->textInput(['value'=>$model->shop_contact_price_outside_self/100,'class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 yw">
            联系店铺上级提成金额（外部）<span>提成金额：</span>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'shop_contact_price_outside_parent')->textInput(['value'=>$model->shop_contact_price_outside_parent/100,'class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-3 yw">
            只能通过内部人员邀请码进行邀请
        </div>
        <div class="col-md-2">
            <? echo $form->field($model, 'just_allow_inside_member_invite')->radioList(['1'=>'是','0'=>'否'])->label(false) ?>
        </div>
    </div>
    <hr/>
    <div class="col-md-2">
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>

</div>
<?php \yii\widgets\ActiveForm::end(); ?>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
    }
</style>