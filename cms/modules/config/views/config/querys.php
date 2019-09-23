<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sql/Redis查询';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([

]); ?>
    <div class="system-config-querys">
        <div class="row">
            <div class="col-md-2 wd">
               sql语句：
            </div>
            <div class="col-md-2">
                <select id="advertconfigsearch-shape" class="form-control" name="sqlku" value="<?=Html::encode($arr['sqlku']) ?>" />
                    <option value="db" <? if($arr['sqlku']=='db'):?>selected="selected"<?endif;?>>db</option>
                    <option value="throw_db" <? if($arr['sqlku']=='throw_db'):?>selected="selected"<?endif;?>>throw_db</option>
                </select>
            </div>
            <div class="col-md-1">
                <input type="text" class="form-control yw" name="sql" aria-required="true" aria-invalid="false" value="<?=Html::encode($arr['sql']) ?>"/>
            </div>
            <div class="col-md-1 but" >
                <?= Html::submitButton('查询', ['class' => 'btn btn-primary but','name'=>'submits','value'=>'sql']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php $form = ActiveForm::begin([

]); ?>
    <div class="system-config-querys">
        <div class="row">
            <div class="col-md-2 wd">
                redis:
            </div>
            <div class="col-md-2">
                <select id="advertconfigsearch-shape" class="form-control" name="redisku" value="<?=Html::encode($arr['redisku']) ?>" />
                    <option value="0" <? if($arr['redisku']==0):?>selected="selected"<?endif;?>>0库</option>
                    <option value="1" <? if($arr['redisku']==1):?>selected="selected"<?endif;?>>1库</option>
                    <option value="2" <? if($arr['redisku']==2):?>selected="selected"<?endif;?>>2库</option>
                    <option value="3" <? if($arr['redisku']==3):?>selected="selected"<?endif;?>>3库</option>
                    <option value="4" <? if($arr['redisku']==4):?>selected="selected"<?endif;?>>4库</option>
                    <option value="5" <? if($arr['redisku']==5):?>selected="selected"<?endif;?>>5库</option>
                    <option value="6" <? if($arr['redisku']==6):?>selected="selected"<?endif;?>>6库</option>
                    <option value="7" <? if($arr['redisku']==7):?>selected="selected"<?endif;?>>7库</option>
                    <option value="8" <? if($arr['redisku']==8):?>selected="selected"<?endif;?>>8库</option>
                    <option value="9" <? if($arr['redisku']==9):?>selected="selected"<?endif;?>>9库</option>
                </select>
            </div>
            <div class="col-md-1">
                <input type="text" class="form-control yw" name="redis" aria-required="true" aria-invalid="false" value="<?=Html::encode($arr['redis']) ?>"/>
            </div>
            <div class="col-md-1 but">
                <?=Html::submitButton('查询', ['class' => 'btn btn-primary but','name'=>'submits','value'=>'redis']) ?>
            </div>
        </div>
        <div class="row table">
        </div>
    </div>
<?php ActiveForm::end(); ?>
<hr/>
<div class="system-config-querys">
    <h5>查询结果：</h5>
    <? \common\libs\ToolsClass::p($result); ?>
</div>
<style type="text/css">
    .wd{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
        width: 9%;
    }
    .yw{
        width: 700px;
    }
    .but{
        right: -610px;
    }
</style>