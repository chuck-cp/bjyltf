<?php

use yii\helpers\Html;
use yii\helpers\Url;
use cms\modules\config\models\SystemAddressLevel;

$this->title = '区域等级设置';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
    .form-control-select {
        width: 100px;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        color: #555555;
        vertical-align: middle;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
        width: 160px;
    }
    .left{
        float: right;
        margin: 0px 20px;
    }
')
?>
<div class="system-zone-list-index">
    <div class="row">
        <?//= Html::a('创建区域等级', ['create'], ['class' => 'btn btn-primary left']) ?>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>序号</th>
                <th>区域名称</th>
                <th>配置地址</th>
                <th class="action-column">操作</th>
            </tr>
        </thead>
        <tbody>
        <?foreach($newarea as $kl=>$vl):?>
        <tr data-key="<?=Html::encode($kl)?>">
            <td width="5%"><?=Html::encode($kl)?></td>
            <td width="5%"><?=Html::encode(SystemAddressLevel::getNameByLevel($kl))?></td>
            <td>
                <?if(empty($vl)):?>
                    暂无地区
                <?else:?>
                    <?foreach($vl as $ka=>$va):?>
                    <?=Html::encode($va).','?>
                    <? endforeach; ?>...
                <?endif?>
            </td>
            <td width="5%"><a href="<?=Url::to(['/config/config/addlevel', 'level'=>$kl])?>">查看详情</a> </td>
        </tr>
        <?endforeach;?>
        </tbody>
    </table>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
//    //取消操作返回列表
//    $('.delprice').on('click', function () {
//        var priceid = $(this).attr('data_id');
//        layer.confirm('您确定需要删除该项设置？', {
//            btn: ['确定','取消'] //按钮
//        }, function(){
//            $.ajax({
//                url:'<?//=\yii\helpers\Url::to(['delprice'])?>//',
//                type : 'GET',
//                dataType : 'json',
//                data : {'priceid':priceid},
//                success:function (resdata) {
//                    if(resdata ==1){
//                        layer.msg('删除成功');
//                    }else{
//                        layer.msg('删除失败');
//                    }
//                    setTimeout(function(){
//                        window.parent.location.reload();
//                    },2000);
//                },
//                error:function (error) {
//                    layer.msg('操作失败！');
//                }
//            })
//        }, function(){
//            layer.msg('您已取消');
//        });
//    })
</script>