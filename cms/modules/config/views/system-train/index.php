<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\config\models\SystemTrain;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemTrainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '培训资料列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>

    table th, table td {
        text-align: center;
        vertical-align: middle!important;
    }
    .sorttext{
        border:solid 1px #ddd;
        width:30%;
        height:25px;
        text-align: center;
        border-radius:5px;
    }

</style>
<div class="system-train-index">

    <h1 style="font-size: 25px; font-weight: bold;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p style="text-align: right;">
        <?= Html::a('配置封面', 'javascript:void(0);', ['class' => 'btn btn-success covermap']) ?>
        <?= Html::a('上传视频资料', 'javascript:void(0);', ['class' => 'btn btn-success material']) ?>
        <?= Html::a('上传图文资料', 'javascript:void(0);', ['class' => 'btn btn-success imgtext']) ?>
    </p>
    <form id="myform">
    <table id="sort" class="grid table table-striped table-bordered" >
        <thead>
            <tr>
                <td width="5%">ID</td>
                <td width="5%">资料名称</td>
                <td width="5%">上传人</td>
                <td width="5%">资料类型</td>
                <td width="5%">状态</td>
                <td width="5%">缩略图</td>
                <td width="5%">排序</td>
                <td width="10%">操作</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dataRes as $k=>$v):?>
                <tr>
                    <td><?php echo $v['id'];?></td>
                    <td><?php echo $v['name'];?></td>
                    <td><?php echo $v['create_user_name'];?></td>
                    <td><?php echo SystemTrain::getTypeStatus('type',$v['type']) ;?></td>
                    <td><?php echo SystemTrain::getTypeStatus('status',$v['status']) ;?></td>
                    <td><?=Html::tag('img','',['src'=>$v['thumbnail'],'width'=>'20%'])?></td>
                    <td><input class="sorttext" type="text" name="sort[<?php echo $v['id'];?>]" value="<?php echo $v['sort'];?>"></td>
                    <td>
                        <a class="edit" id="<?php echo $v['id'];?> " type="<?php echo $v['type'];?> ">编辑</a>
                        <a class="see" id="<?php echo $v['id'];?>">查看</a>
                        <a class="del" id="<?php echo $v['id'];?>">删除</a>
                        <?php if( $v['status']==1):?>
                            <a class="status" id="<?php echo $v['id'];?>" status="<?php echo $v['status'];?>">停用</a>
                        <?php else:?>
                            <a class="status" id="<?php echo $v['id'];?>" status="<?php echo $v['status'];?>">启用</a>
                        <?php endif;?>

                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    </form>
    <input class="btn btn-primary" type="button" value="排序" id="submitsort">
</div>
<style type="text/css">
    .table th, .table td {
        text-align: center;
        vertical-align: middle!important;
    }
    a{cursor:pointer;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('#submitsort').click(function(){
        var data=$('#myform').serialize();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['sort'])?>',
            type : 'POST',
            dataType : 'json',
            data : data,
            success:function (data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon:1});
                    setTimeout(function(){
                        parent.location.reload();
                    },2000);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！',{icon:7});
            }
         });
    })

    $('.covermap').click(function(){
        var pageup = layer.open({
            type: 2,
            title: '封面图片',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/config/system-train/covermap'])?>'
        });
    })

    $('.material').click(function(){
        var pageup = layer.open({
            type: 2,
            title: '上传资料',
            shadeClose: true,
            shade: 0.8,
            area: ['70%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/config/system-train/createvideo'])?>'
        });
    })

    //上传图文资料
    $('.imgtext').click(function(){
        var pageup = layer.open({
            type: 2,
            title: '上传图文资料',
            shadeClose: true,
            shade: 0.8,
            area: ['70%', '90%'],
            content: '<?=\yii\helpers\Url::to(['/config/system-train/createimgtext'])?>'
        });
    })
    $('.edit').click(function(){
        var id = $(this).attr('id');
        var type = $(this).attr('type');
        var pageup = layer.open({
            type: 2,
            title: '编辑',
            shadeClose: true,
            shade: 0.8,
            area: ['70%', '90%'],
            content: '<?=\yii\helpers\Url::to(['/config/system-train/edit'])?>&id='+id+'&type='+type
        });


    })
    $('.see').click(function(){
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '查看',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/config/system-train/view'])?>&id='+id
        });
    })
    $('.del').click(function(){
        var id = $(this).attr('id');
        layer.confirm('确定删除所选数据吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['del'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id},
                success:function (data) {
                    if(data.error==1){
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
            });
        });
    })
    $('.status').click(function(){
        var id = $(this).attr('id');
        var status = $(this).attr('status');
        if(status==1){
            var title='禁用资料';
            var Prompt='确定停用资料';
        }else{
            var title='启用资料';
            var Prompt='确定启用资料？'
        }
        layer.confirm(Prompt, {
            title:title,
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['status'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'status':status},
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
            });
        });
    })
</script>