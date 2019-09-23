<?php

use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
\cms\assets\AppAsset::register($this);
use yii\bootstrap\Html;
use cms\models\SystemAddress;
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

<?php $this->beginBody(); ?>
<div class="member-search">
    <?php echo $this->render('layout/oneoutput',['kuid'=>$kuid,'deviceid'=>$deviceid]);?>
    <div class="row" style="display: flex;justify-content:center; width:100%;margin-left:0;margin-right:0 ;margin-top: 20px;">
        <?php  $form = ActiveForm::begin([
            //'action' => ['/screen/designate'],
            'method' => 'get',
        ]);     ?>
        <div class="col-xs-3 form-group" style="margin-bottom: -8px;">
            <?=$form->field($model,'name')->textInput(['class'=>'form-control fm'])->label('姓名：');?>
        </div>
        <div class="col-xs-3 form-group" style="margin-bottom: -8px;">
            <?=$form->field($model,'mobile')->textInput(['class'=>'form-control fm'])->label('电话：');?>
        </div>
        <div class="col-xs-3 form-group" style="margin-top: 10px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
        <input type="hidden" name="deviceid" value="<?=Html::encode($deviceid)?>" />
         <?= \cms\core\CmsGridView::widget([
             'dataProvider' => $dataProvider,
             'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],
                 'id',
                 [
                     'label'=>'管理员',
                     'value'=>function($model){
                         return $model->name;
                     }
                 ],
                 [
                     'label'=>'联系电话',
                     'value'=>function($model){
                         return $model->mobile;
                     }
                 ],
                 [
                     'class' => 'yii\grid\ActionColumn',
                     'header' => '操作',
                     'template' => '{designate}',
                     'buttons' => [
                         'designate' => function($url,$model){
                             return html::a('出库','javascript:void(0);',['class'=>'admin zhipai','memberid'=>$model->id]);
                         }
                     ],
                 ],
             ]

         ]);?>
</div>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
<!--<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>-->
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //出库
    $('.admin').click(function(){
        var memberid = $(this).attr('memberid');
        var deviceid = $('[name="deviceid"]').val();
        /*var layerMsg = layer.load('正在出库，请稍后...',{
            icon: 0,
            shade: [0.1,'black']
        });*/
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['out-put'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'memberid':memberid,'deviceid':deviceid},
            success:function (resdata) {
                if(resdata ==true){
                    layer.closeAll();
                    layer.msg('出库成功！',{icon:1});
                    setTimeout(function(){
                        window.parent.location.reload();
                    },1000);
                }else{
                    layer.msg('操作失败，请刷新页面后重新出库！',{icon:2});
                }
            },
            error:function (error) {
                layer.msg('操作失败！');
            }
        });
    })
</script>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{display: inline-block;}
    .detail:hover{cursor:pointer;}
    #w0{display: flex;justify-content:center;align-items:center; width:100%;}
    table th,table td{text-align: center; }
</style>