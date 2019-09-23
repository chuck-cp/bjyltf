<?php

use yii\helpers\Html;
use cms\modules\shop\models\Shop;
/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\Order */
$this->params['breadcrumbs'][] = '店铺自定义广告';
$this->registerCssFile('/static/css/tcplayer/tcplayer.css');
$this->registerJsFile('/static/js/tcplayer/videojs-ie8.js');
?>
<div class="member-view">
    <table class="table table-hover">
        <tr><h4><strong>商家信息</strong></h4></tr>
        <?if($type==1):?>
            <tr>
                <td colspan="2">商家名称：<?=$model->name?></td>
            </tr>
            <tr>
                <td>所属地区：<?=$model->area_name?></td>
                <td>详细地址：<?=$model->address?></td>
            </tr>
            <tr>
                <td>店铺屏幕数量：<?=$model->screen_number?></td>
                <td>广告图片数量：<?=$ImageCount?></td>
            </tr>
            <tr>
                <td colspan="2">店铺类型：<?=Shop::getTypeByNum($model->shop_operate_type)?></td>
            </tr>
        <?else:?>
            <tr>
                <td colspan="2">商家名称：<?=$model->company_name?></td>
            </tr>
            <tr>
                <td>所属地区：<?=$model->company_area_name?></td>
                <td>详细地址：<?=$model->company_address?></td>
            </tr>
            <tr>
                <td>店铺屏幕数量：-</td>
                <td>广告图片数量：<?= $ImageCount?></td>
            </tr>
            <tr>
                <td colspan="2">店铺类型：总部</td>
            </tr>
        <?endif;?>

    </table>
    <table class="table table-hover imgs">
        <tr>
            <td><h4><strong>广告内容</strong></h4></td>
            <td><?=Html::submitButton('推送节目',['class'=>'btn btn-primary push'])?></td>
        </tr>
        <tr>
            <td colspan="2" style="overflow: hidden">
                <?if(!empty($ImageArr)):?>
                    <?foreach ($ImageArr as $v):?>
                        <p style="float: left;width:12%;padding:0 15px;text-align: center">
                            <img style="width: 100%;height: 240px;" src="<?echo $v['image_url']?>">
                            <span> <?echo $v['create_at']?></span>
                            <a  style="display:block" href="javascript:void(0);" id="<?echo $v['id'] ?>" class="del btn btn-danger ck" shop_type="<?echo $v['shop_type']?>" shop_id="<?echo $v['shop_id']?>">删除</a>
                        </p>
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
    </table>


</div>
<style>
    img{margin-bottom: 10px}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script>
    $('.imgs').viewer({
        url: 'src',
    });
    $('.del').click(function(){
        var id=$(this).attr('id');
        var shop_type=$(this).attr('shop_type');
        var shop_id=$(this).attr('shop_id');
        layer.confirm('你确定要删除吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['image-del'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'shop_type':shop_type,'id':id,'shop_id':shop_id},
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！',{icon:7});
                }
            });
        });
    })

    $('.push').on('click',function(){
        var shop_id = '<?=$model->id?>';
        var shop_type = '<?=$type?>';
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['push-program'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'shop_id':shop_id,'shop_type':shop_type},
            success:function (data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon:1});
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！',{icon:7});
            }
        });
    })
</script>




