<?php
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:10px 15px;}
    .dv{margin-top:80px;}
    .sub{margin-left:78%;margin-top:45px;}
');
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <head>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>
    <? $form = ActiveForm::begin([
        'action' => ['rebut'],
        'method' => 'get',
    ])?>
    <div class="row">
            <div class="col-xs-12">
                <label for="">驳回原因：</label>
                <?= $form->field($model, 'examine_desc')->textarea(['rows' => '8','data_id'=>$model->id])->label(false); ?>
            </div>
            <div class="row">
                <?= Html::submitButton('提交', ['class'=>'btn btn-primary sub','name' =>'submit-button']) ?>
            </div>
    </div>

    <? ActiveForm::end();?>
    <?php $this->endBody() ?>
    </body>
    </html >
<?php $this->endPage() ?>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //阻止表单提交
        $("#w0").on("submit",function(event){
                event.preventDefault();
        })
        //点击驳回的时候
        $('.sub').bind('click',function () {
            var page = $('[name="page"]',parent.document).val();
            var content = $('#logexamine-examine_desc').val();
            var id = $('#logexamine-examine_desc').attr('data_id');
            var runner = '';
            if(page == 'index'){
                runner = '财务';
            }else if(page == 'audit'){
                runner = '审计';
            }else if(page == 'cashier'){
                runner = '出纳';
            }
            if(!content){
                layer.msg('请填写驳回原因！');
            }else {
                $.ajax({
                    url: '<?=\yii\helpers\Url::to(['/withdraw/member-withdraw/examine'])?>',
                    type: 'POST',
                    dataType: 'json',
                    data:{'id':id,'type':'rebut','content':content,'page':page},
                    success:function (phpdata) {
                        if(phpdata){
                            if(phpdata == 1){
                                parent.layer.msg(runner+'驳回成功！');
                                parent.layer.close(parent.layer.getFrameIndex(window.name))
                                parent.location.reload();
                                //成功后更改状态
                            }else if(phpdata == 5){
                                layer.msg('您已经驳回成功，请勿重复驳回！');
                            }
                        }else{
                            layer.msg('驳回失败！');
                        }
                    },error:function (phpdata) {
                        layer.msg('驳回失败！');
                    }
                })
            }
        })
    })
</script>
