<?php

use yii\helpers\Html;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use cms\models\SystemAddress;
use common\libs\ToolsClass;
use cms\modules\member\models\Member;
/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\shop */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '商家信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    tr td:nth-child(odd){
        font-weight: 700;
    }
</style>
<div class="shop-view">
    <table class="table table-hover">
        <tr>
            <h4><b>个人信息</b></h4>
        </tr>
        <tr>
            <td>姓名：</td>
            <td><?=Html::encode($model->name)?></td>
            <td>手机号：</td>
            <td><?=Html::encode($model->member['mobile'])?></td>
        </tr>
        <tr>
            <td>身份证号:</td>
            <td><?=Html::encode($model->id_number)?></td>
            <td>电工证件编号：</td>
            <td><?=Html::encode($model->electrician_certificate_number)?></td>
        </tr>
        <tr>
            <td>电工证类别</td>
            <td><?=Html::encode($model->electrician_certificate_type)?></td>
            <td>准操项目</td>
            <td><?=Html::encode($model->professional_name)?></td>
        </tr>
        <tr>
            <td>电工证发证地区</td>
            <td><?=Html::encode($model->electrician_certificate_area_name)?></td>
            <td>常住地址</td>
            <td><?=Html::encode($model->live_area_name)?></td>
        </tr>
    </table>
    <?if((int)$model->join_team_id!==0):?>
        <table class="table table-hover">
            <tr>
                <h4><b>所属团队信息</b></h4>
            </tr>
            <tr>
                <td>
                    团队名称：
                </td>
                <td>
                    <?=Html::encode($MemberTeamModel->team_name)?>
                </td>
                <td>
                    组长姓名：
                </td>
                <td>
                    <?=Html::encode($MemberTeamModel->team_member_name)?>
                </td>
            </tr>
            <tr>
                <td>
                    组长电话：
                </td>
                <td colspan="4">
                    <?=Html::encode(Member::getMobileById($MemberTeamModel->team_member_id,'mobile'))?>
                </td>
            </tr>
        </table>
    <?endif;?>
    <table class="table table-hover">
        <tr>
            <h4><b>证件信息</b></h4>
        </tr>
        <tr>
            <td>
                <?=Html::tag('img','',['data-original'=>$model->electrician_certificate_front_image,'src'=>$model->electrician_certificate_front_image,'height'=>'80px','width'=>'auto'])?>
            </td>
            <td colspan="4">
                <?=Html::tag('img','',['data-original'=>$model->electrician_certificate_back_image,'src'=>$model->electrician_certificate_back_image,'height'=>'80px','width'=>'auto'])?>
            </td>
        </tr>
    </table>
    <table class="table table-hover">
        <?if(!empty($rejectAll)):?>
            <tr>
                <h4><b>审核信息</b></h4>
            </tr>
            <?php foreach ($rejectAll as $v):?>
            <tr>
                <td  colspan="4">
                    时间：<?php echo $v['create_at']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    操作人：<?php echo $v['create_user_name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <? if($v['examine_result']==2):?>
                        结果：<?php echo $v['examine_desc']?>
                    <?elseif($v['examine_result']==1):?>
                        结果：已通过审核
                    <?php endif;?>
                </td>
            </tr>
            <?php endforeach;?>
        <?endif;?>
    </table>
    <div class="row text-center" style="margin-top: 50px;" >
        <div class="row one">
            <?php if($model->electrician_examine_status==0):?>
                <button type="button" class="btn btn-primary adopt" data-type="pass" member_id="<?=Html::encode($model->member_id)?>">通过</button>
                <button type="button" class="btn btn-danger reject" data-type="rebut" member_id="<?=Html::encode($model->member_id)?>">驳回</button>
            <?php else:?>
                <button type="button" class="btn btn-primary firm" data-type="close">确定</button>
            <?php endif;?>
        </div>
    </div>
</div>

<!--<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>-->
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.shop-view').viewer({
        url: 'src',
    });
    $(function () {
        //审核不通过详情页点击确定
        $('.firm').bind('click',function () {
            history.back();
        })
        //审核通过

        $('.adopt').bind('click',function () {
            var member_id = $(this).attr('member_id');
            var type=1;
            layer.confirm('您确定要审核通过吗？', {
                btn: ['通过','取消'] //按钮
            }, function(){
                $.ajax({
                    url : '<?=\yii\helpers\Url::to(['installer-examine'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'type':type,'member_id': member_id},
                    success:function (phpdata) {
                        if(phpdata.code == 1){
                            layer.msg(phpdata.msg,{icon:1});
                            history.back();
                        }else{
                            layer.msg(phpdata.msg,{icon:2});
                            return false;
                        }
                    },
                    error:function () {
                        layer.msg('操作失败！');
                    }
                });
            }, function(){

            });
        })
        $('.reject').bind('click',function () {
            var member_id = $(this).attr('member_id');
            pg = layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['470px', '290px'], //宽高
                shadeClose: true,
                content: '<div class="row scroll form-horizontal txa" style="margin-top: 15px;"><label for="" class="col-sm-3 control-label">驳回原因：</label><div class="col-sm-6"><textarea class="form-control" rows="3"></textarea></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
            });
            $('.confirm').bind('click',function () {
                var desc = $('.txa textarea').val();
                var type=2;
                if(!desc){
                    layer.msg('请填写驳回原因！');
                    return false;
                }
                $.ajax({
                    url : '<?=\yii\helpers\Url::to(['installer-examine'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'type':type,'desc':desc,'member_id':member_id},
                    success:function (phpdata) {
                        if(phpdata.code ==1){
                            layer.msg('驳回成功！',{icon:1});
                            $('.one').css({'display':'none'});
                            $('.two').css({'display':'block'});
                            layer.closeAll('page');
                            history.back();
                        }else{
                            layer.msg('驳回失败！',{icon:2});
                        }
                    },
                    error:function () {
                        layer.msg('操作失败！');
                    }
                });
            })
        })
    })
</script>
<style type="text/css">
    label{width: 15%}
    .last td{
        text-align: center;
    }
    tr td:nth-child(odd){
        font-weight: 700;
    }
    th{
        font-size: 14px;
        border-top: none;
    }
    .scroll{
        width: 428px;
    }
</style>
