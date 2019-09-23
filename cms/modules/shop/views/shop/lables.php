<?php
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
//            'action' => ['addlable'],
            'method' => 'post',
        ]);     ?>
        <div class="col-xs-3 form-group">
            <label>标签名称：</label><input type="text" class="form-control fm" name="title" value="" style="width: 240px;" placeholder="请输入四个字以内的标签名称">
        </div>
        <div class="col-xs-3 form-group">
            <label>标签注释：</label><input type="text" class="form-control fm" name="desc" value="" style="width: 240px;" placeholder="请输入十二个字以内的标签名注释">
        </div>
        <div class="col-xs-3 form-group">
            <?= Html::Button('生成标签', ['class' => 'btn btn-primary creat']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <table class="table table-striped table-bordered" style="width: 98%;margin: 20px 10px;">
        <thead>
        <tr>
            <th colspan="15">标签列表（请选择至多两条标签）：</th>
        </tr>
        </thead>
        <tbody class="lables">
        <input type="hidden" name="shopid" value="<?=Html::encode($shopid)?>">
        <?foreach($lables as $key=>$value):?>
            <? if($key == 0):?>
                <tr class="restatus">
            <?endif;?>
                <td style="position: relative;">
                    <input  name="lableArr[]" type="checkbox" id="<?=Html::encode($value['id'])?>" <?if(in_array($value['id'],$labid)):?>checked<?endif;?>><a title="<?echo $value['desc']?>"><?=Html::encode($value['title'])?></a><img style="cursor:pointer;position: absolute;top:0;right: 0;" src="static/img/labeldel.png" class="del" id="<?echo $value['id']?>">
                </td>
            <?if(($key+1)%15==0):?>
                </tr>
                <tr>
            <?elseif($key ==(count($lables)-1)):?>
                </tr>
            <?endif;?>
        <?endforeach;?>

        </tbody>
    </table>
    <div style="text-align: center;"><?= Html::Button('保存', ['class' => 'btn btn-primary save']) ?></div>
    <?php $this->endBody() ?>
    <style>
        a{color: #0c0c0c;cursor:pointer}
        a:hover{text-decoration: none;}
    </style>
    <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $('.creat').on('click',function(){
                var shopid = $.trim($("input[name='shopid']").val());
                var title = $.trim($("input[name='title']").val());
                var desc = $.trim($("input[name='desc']").val());
                if(!title || title==''){
                    layer.msg('请填写标签名称',{icon:2});
                    return false;
                }else if(title.length>4){
                    layer.msg('标签名称不得超过4个字符',{icon:2});
                    return false;
                }
                if(!desc || desc==''){
                    layer.msg('请填写标签注释',{icon:2});
                    return false;
                }else if(desc.length>12){
                    layer.msg('标签注释不得超过12个字符',{icon:2});
                    return false;
                }
                $.ajax({
                    url:'<?=\yii\helpers\Url::to(['addlable'])?>',
                    type : 'get',
                    dataType : 'json',
                    data : {'title':title,'desc':desc},
                    success:function (res) {;
                        if(res.code==1){
                            layer.msg(res.msg,{icon:1});
                            setTimeout(function(){
                                window.location.reload();//刷新当前页面.
                            },2000);
                        }else{
                            layer.msg(res.msg,{icon:2});
                        }
                    },error:function (error) {
                        layer.msg('');
                    }
                });
            })
            $('.save').click(function(){
                var shopid = $.trim($("input[name='shopid']").val());
                var ids=$(':checkbox');
                var str='';
                var count=0;
                for(var i=0;i<ids.length;i++){
                    if(ids.eq(i).is(':checked')){
                        str+=","+ids.eq(i).attr("id");
                        count++;
                    }
                }
                /*if(!str){
                    layer.msg('至少选择一项',{icon:2});
                    return false;
                }*/
                var strAll=str.substr(1).split(',');
                for(var i in strAll){
                    strAll[i];
                }
                if(strAll.length>2){
                    layer.msg('所选标签不能超过两个',{icon:2});
                    return false;
                }
                $.ajax({
                    url:'<?=\yii\helpers\Url::to(['keeplable'])?>&shopid='+shopid,
                    type : 'post',
                    dataType : 'json',
                    data : {'strAll':strAll},
                    success:function (res) {;
                        if(res.code==1){
                            layer.msg(res.msg,{icon:1});
                            setTimeout(function(){
                                parent.location.reload();
                              //  window.location.reload();//刷新当前页面.
                            },2000);
                        }else{
                            layer.msg(res.msg,{icon:2});
                        }
                    },error:function (error) {
                        layer.msg('');
                    }
                });

            })

            $('.del').click(function(){
                var id=$(this).attr('id');
                layer.confirm('确定删除此标签吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    $.ajax({
                        url:'<?=\yii\helpers\Url::to(['label-del'])?>',
                        type : 'POST',
                        dataType : 'json',
                        data : {'id':id},
                        success:function (data) {
                            if(data.code==1){
                                layer.msg(data.msg,{icon:1});
                                setTimeout(function(){
                                    window.location.reload();
                                },1000);
                            }else{
                                layer.msg(data.msg,{icon:2});
                            }
                        },error:function (error) {
                            layer.msg('操作失败！');
                        }
                    });
                });
            })
        })

    </script>
    </body>
    </html >
<?php $this->endPage() ?>
