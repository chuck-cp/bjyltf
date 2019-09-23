<?php
use yii\grid\GridView;
use common\libs\ToolsClass;
\cms\assets\AppAsset::register($this);
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
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
    <body>
    <?php $this->beginBody(); ?>
    <div class="row" style="margin-top: 30px;">
        <?php  $form = ActiveForm::begin([
            //'action' => ['/screen/designate'],
            'method' => 'get',
        ]);     ?>
        <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
        <script type="text/javascript">
            $(function () {
                $('.area').change(function () {
                    var type = $(this).attr('key');
                    var selObj = $('[key='+type+']').parents('.col-xs-2');
                    selObj.nextAll().find('select').find('option:not(:first)').remove();
                    var parent_id = $(this).val();
                    //alert(parent_id);
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
            })
        </script>
        <div class="col-xs-3 form-group fm" style="margin-right: 80px;">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('姓名：');?>
        </div>
        <div class="col-xs-3 form-group fm" style="margin-right: 80px;">
            <?=$form->field($searchModel,'mobile')->textInput(['class'=>'form-control fm'])->label('电话：');?>
        </div>
        <div class="col-xs-2 form-group fm">
            <?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group fm">
            <?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group fm">
            <?php  echo $form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group fm">
            <?php  echo $form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-3 form-group fm" style="margin-top: 20px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <ul id="myTab" class="nav nav-tabs" style="margin-bottom: 10px;">
        <li class="active">
            <?=Html::a('指派个人')?>
        </li>
        <li >
            <?=Html::a('指派小组',['assign-group','id'=>$id])?>
        </li>
    </ul>
    <input type="hidden" value="<?php echo $id?>" name="id">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
           // ['class' => 'yii\grid\CheckboxColumn'],
            'member_id',
            [
                'label'=>'安装人',
                'value'=>function($model){
                    return $model->name;
                }
            ],
            [
                'label'=>'联系电话',
                'value'=>function($model){
                    return $model->member['mobile'];
                }
            ],
            [
                'label'=>'常驻地区',
                'value'=>function($model){
                    return $model->live_area_name;
                }
            ],
            [
                'label'=>'待安装店铺',
                'value'=>function($model){
                    return $model->wait_shop_number;
                }
            ],
            [
                'label'=>'待安装屏幕',
                'value'=>function($model){
                    return $model->wait_screen_number;
                }
            ],

            //'offline_time',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{designate}',
                'buttons' => [
                    'designate' => function($url,$model){
                        return html::a('指派','javascript:void(0);',['class'=>'zhipai','memberid'=>$model->member_id,'name'=>$model->name,'mobile'=>$model->member['mobile']]);
                    }
                ],
            ],
        ]
    ])?>
    <?php $this->endBody() ?>
    <style>
        .col-xs-2{
            margin-right: 80px;
        }
    </style>
    <script>
        $('.zhipai').click(function(){
            var id=$('input[name="id"]').val();
            var name = $(this).attr('name');
            var member_id = $(this).attr('memberid');
            var mobile = $(this).attr('mobile');
            var type=1;
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['assign'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'name':name,'member_id':member_id,'mobile':mobile,'type':type},
                success:function (data) {
                    if(data.code==1){
                      //  layer.closeAll();
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            parent.location.reload();
                        },2000);
                    }else if(data.code==3){
                        layer.msg(data.msg,{icon:2});
                        setTimeout(function(){
                            parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！');
                }
            });
        })
    </script>
    </body>
    </html >
<?php $this->endPage() ?>
