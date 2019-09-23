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


        <?php  $form = ActiveForm::begin([
            //'action' => ['/screen/designate'],
            'method' => 'get',
        ]);     ?>
        <div class="row">
            <table class="grid table table-striped table-bordered search">
                <tr>
                    <td>
                        <p>业务合作人</p>
                        <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label(false);?>
                    </td>
                    <td>
                        <p>业务合作人手机号</p>
                        <?=$form->field($searchModel,'mobile')->textInput(['class'=>'form-control fm'])->label(false);?>
                    </td>
                    <td colspan="4">
                        <p></p>
                        <br/>
                        <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search'])?>
                    </td>
                </tr>
            </table>
        </div>
        <?php ActiveForm::end(); ?>

    <input type="hidden" value="<?php echo $id?>" name="id">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
           // ['class' => 'yii\grid\CheckboxColumn'],
            [
                'label'=>'业务合作人',
                'value'=>function($model){
                    return $model->name;
                }
            ],
            [
                'label'=>'业务合作人账号',
                'value'=>function($model){
                    return $model->mobile;
                }
            ],

            //'offline_time',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{designate}',
                'buttons' => [
                    'designate' => function($url,$model){
                        return html::a('指派','javascript:void(0);',['class'=>'zhipai','member_id'=>$model->id,'name'=>$model->name,'mobile'=>$model->mobile]);
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
    <script type="text/javascript">
        $('.zhipai').click(function(){
            var id=$('input[name="id"]').val();
            var name = $(this).attr('name');
            var member_id = $(this).attr('member_id');
            var mobile = $(this).attr('mobile');
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['activity-assign'])?>',
                type : 'get',
                dataType : 'json',
                data : {'id':id,'name':name,'member_id':member_id,'mobile':mobile},
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！');
                }
            })
        })
    </script>
    </body>
    </html >
<?php $this->endPage() ?>
