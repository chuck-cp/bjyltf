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
    <div class="row" style="display: flex;justify-content:center; width:100%;margin-left:0;margin-right:0 ;">
        <?php  $form = ActiveForm::begin([
        //'action' => ['/screen/designate'],
        'method' => 'get',
]);     ?>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'name')->textInput(['class'=>'form-control fm'])->label('姓名');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'mobile')->textInput(['class'=>'form-control fm'])->label('电话');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'city')->dropDownList(SystemAddress::getAreasByPid($model->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'area')->dropDownList(SystemAddress::getAreasByPid($model->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>
       <?php ActiveForm::end(); ?>
    </div>
     <table class="table table-hover" >
        <input type="hidden" name="shopid" value="<?=Html::encode($shopid)?>" />
         <?= \cms\core\CmsGridView::widget([
             'dataProvider' => $dataProvider,
             'columns' => [
//                 ['class' => 'yii\grid\CheckboxColumn'],
                 ['class' => 'yii\grid\SerialColumn'],
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
                     'label' => '业务区域',
                     'value' => function($model){
                         return  SystemAddress::getAreaNameById($model->admin_area);
                     }
                 ],
                 [
                     'label' => '商家数量',
                     'value' => function($model){
//                         print_r($model);exit;
                         return  $model->memberCount['admin_shop_number'] == ''?0:$model->memberCount['admin_shop_number'];
                     }
                 ],
                 [
                     'label' => 'LED数量',
                     'value' => function($model){
                         return  $model->memberCount['admin_screen_number'] == ''?0:$model->memberCount['admin_screen_number'];

                     }
                 ],
                 [
                     'class' => 'yii\grid\ActionColumn',
                     'header' => '操作',
                     'template' => '{designate}',
                     'buttons' => [
                         'designate' => function($url,$model){
                             return html::a('指派','javascript:void(0);',['class'=>'admin zhipai','dataid'=>$model->id]);
                         }
                     ],
                 ],
             ]

         ]);?>
    </table>
</div>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
<!--<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>-->
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //地区切换
    $('.area').change(function () {
        var type = $(this).attr('key');
        var selObj = $('[key='+type+']').parents('.col-xs-2');
        selObj.nextAll().find('select').find('option:not(:first)').remove();
        var parent_id = $(this).val();
        if(!parent_id){
            return false;
        }
        $.ajax({
            url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
            type: 'POST',
            dataType: 'json',
            data:{'parent_id':parent_id},
            success:function (phpdata) {
                $.each(phpdata,function (i,item) {
                    selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                })
            },error:function (phpdata) {
                layer.msg('获取失败！');
            }
        })
    })

    //指派
    $('.admin').click(function(){
        var memberid = $(this).attr('dataid');
        var shopid = $('[name="shopid"]').val();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['upadminshop'])?>',
            type : 'GET',
            dataType : 'json',
            data : {'memberid':memberid,'shopid':shopid},
            success:function (resdata) {
                if(resdata ==1){
                    layer.msg('指派成功！');
                    setTimeout(function(){
                        window.parent.location.reload();
                    },1000);
                }else if(resdata ==2){
                    layer.msg('指派失败！');
                }else{
                    layer.msg('操作失败，请刷新页面后重新指派！');
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