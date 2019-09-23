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
        <div class="col-xs-3 form-group fm" style="margin-right: 80px;">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('姓名：');?>
        </div>
        <div class="col-xs-3 form-group fm" style="margin-right: 80px;">
            <?=$form->field($searchModel,'mobile')->textInput(['class'=>'form-control fm'])->label('电话：');?>
        </div>
        <div class="col-xs-3 form-group fm" style="margin-top: 20px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <input type="hidden" value="<?php echo $id?>" name="id">
    <input type="hidden" value="<?php echo $screen_type?>" name="screen_type">
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
    <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
    <script>
        $('.zhipai').click(function(){
            var id=$('input[name="id"]').val();
            var screen_type=$('input[name="screen_type"]').val();
            var name = $(this).attr('name');
            var member_id = $(this).attr('memberid');
            var mobile = $(this).attr('mobile');
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['audit-assign'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'name':name,'member_id':member_id,'mobile':mobile,'screen_type':screen_type},
                success:function (data) {
                    if(data.code==1){
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
