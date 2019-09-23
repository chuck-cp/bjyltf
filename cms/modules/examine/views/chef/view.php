<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\SystemAddress;
/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\Member */
$this->title = $model->name;
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = ['label' => '人员审核', 'url' => ['index']];
$this->params['breadcrumbs'][] = '人员信息';

?>
<div class="member-view">
    <?php echo $this->render('layout/tab',['model'=>$membermodel]);?>
    <table class="table table-hover">
        <tr >
            <h4 style="border-top: none"><b>个人信息</b></h4>
        </tr>
        <tr>
            <td>姓名：</td>
            <td><?= Html::encode($model->name)?></td>
            <td>身份证：</td>
            <td><?=Html::encode($model->id_number)?></td>
        </tr>
        <tr>
            <td>手机号：</td>
            <td><?=Html::encode($membermodel->mobile)?></td>
            <td>性别：</td>
            <td><?=Html::encode($model->sex==1?'男':'女')?></td>
        </tr>
        <tr>
            <td>毕业学校：</td>
            <td><?=Html::encode($membermodel->school)?></td>
            <td>学历：</td>
            <td><?=Html::encode($membermodel->education)?></td>
        </tr>
        <tr>
            <td rowspan="2">头像：</td>
            <td rowspan="2">
                <?php if($membermodel->avatar):?>
                    <?=Html::tag('img','',['data-original'=>$membermodel->avatar,'src'=>$membermodel->avatar,'height'=>'80px','width'=>'auto'])?>
                <?php endif;?>
            </td>
            <td>所属地区：</td>
            <td><?=Html::encode($model->live_area_name)?></td>
        </tr>
        <tr>
            <td>详细地址：</td>
            <td><?=Html::encode($model->live_address)?></td>
        </tr>
        <tr>
            <td>入驻时间：</td>
            <td><?=Html::encode($membermodel->create_at)?></td>
            <td>上级：</td>
            <td>
                <?=Html::encode($membermodel->getMemByNumber($membermodel->parent_id)['name'])?>
                &nbsp;&nbsp;&nbsp;
                <?php if($membermodel->getMemByNumber($membermodel->parent_id)['id']): ?>
                    <?=Html::a('查看',['view','id'=>$membermodel->getMemByNumber($membermodel->parent_id)['id']])?>
                <?php endif?>
            </td>
        </tr>
    </table>
    <table class="table table-hover">
        <tr >
            <h4><b>紧急联系人</b></h4>
        </tr>
        <tr>
            <td>紧急联系人：<?=Html::encode($membermodel->emergency_contact_name)?></td>
            <td>关系：<?=Html::encode($membermodel->emergency_contact_relation)?></td>
            <td>联系电话：<?=Html::encode($membermodel->emergency_contact_mobile)?></td>
            <td></td>
        </tr>
    </table>
    <table class="table table-hover">
        <tr>
            <h4><b>证件信息</b></h4>
        </tr>
        <tr class="last">
            <td>
                <?php if($model->id_front_image):?>
                    <img data-original="<?=Html::encode($model->id_front_image)?>" src="<?=Html::encode($model->id_front_image)?>" alt="" title="身份证正面照" width="200px" height="auto">
                <?php endif;?>
            </td>
            <td>
                <?php if($model->id_back_image):?>
                    <img data-original="<?=Html::encode($model->id_back_image)?>" src="<?=Html::encode($model->id_back_image)?>" alt="" width="200px" height="auto" title="身份证反面照">
                <?php endif;?>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr class="last">
            <td>
                <?php if($model->id_hand_image):?>
                    <img data-original="<?=Html::encode($model->id_front_image)?>" src="<?=Html::encode($model->id_hand_image)?>" alt="" width="200px" height="auto" title="手持身份证照">
                <?php endif;?>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <?if(!empty($examine)):?>
        <table class="table table-hover">
            <tr>
                <h4><b>审核信息</b></h4>
            </tr>
            <?php foreach ($examine as $v):?>
                <tr class="last">
                    <td colspan="4" style="text-align: left;width: 12%">
                        时间<?=Html::encode($v['create_at'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        操作人：<?=Html::encode($v['create_user_name'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <? if($v['examine_result']==2):?>
                            结果：<?=Html::encode($v['examine_desc'])?>
                        <?elseif($v['examine_result']==1):?>
                            结果：已审核通过
                        <?endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
    <?endif;?>
    <? if($model->examine_status==0):?>
    <div class="row text-center" style="margin-top: 50px;" member_id="<?=Html::encode($model->member_id)?>">
        <button type="button" class="btn btn-primary ck" data-type="pass">审核通过</button>
        <button type="button" class="btn btn-danger ck" data-type="rebut">驳回</button>
    </div>
    <?endif;?>
</div>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.member-view').viewer({
        url: 'src',
    });
    $(function () {

        $('.ck').bind('click',function () {
            var type = $(this).attr('data-type');
            var member_id = $(this).parent('.row').attr('member_id');
            if(type == 'pass'){
                layer.confirm('您确定要审核通过吗？', {
                    btn: ['通过','取消'] //按钮
                }, function(){
                    $.ajax({
                        url : '<?=\yii\helpers\Url::to(['examine-card'])?>',
                        type : 'POST',
                        dataType : 'json',
                        data : {'type':type, 'member_id': member_id},
                        success:function (phpdata) {
                            if(phpdata == 5){
                                layer.msg('请勿重复审核通过！');
//                                history.back();
                                return false;
                            }
                            if(phpdata == 4){
                                layer.msg('未提交数据不能审核！');
//                                history.back();
                                return false;
                            }
                            if(phpdata ==1){
                                layer.msg('审核通过成功！');
//                                layer.closeAll('page');
                                history.back();
                            }else if(phpdata ==0){
                                layer.msg('审核失败！');
                            }
                        },error:function () {
                            layer.msg('操作失败！');
                        }
                    });
                }, function(){

                });
            }else if(type == 'rebut'){
                //页面层
                  pg = layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['470px', '290px'], //宽高
                    shadeClose: true,
                    content: '<div class="row scroll form-horizontal"  style="margin-top: 15px;"><label class="col-sm-3 control-label" for="formGroupInputLarge">驳回原因</label><div class="col-sm-5"><select name="reason" id="" class="form-control"><option value="1">身份证信息有误</option><option value="2">地址有误</option><option value="3">其他</option></select></div></div><div class="row scroll form-horizontal txa" style="margin-top: 15px;"><label for="" class="col-sm-3 control-label">其他原因：</label><div class="col-sm-6"><textarea class="form-control" rows="3"></textarea></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
                });

                //驳回

                $('.confirm').bind('click',function () {
                    var desc = $('[name="reason"]').val();
                    if(desc == 3){
                        desc = $('.txa textarea').val();
                        if(!desc){
                            layer.msg('请填写驳回原因！');
                            return false;
                        }
                    }

                    $.ajax({
                        url : '<?=\yii\helpers\Url::to(['examine-card'])?>',
                        type : 'POST',
                        dataType : 'json',
                        data : {'type':type, 'member_id': member_id, 'desc':desc},
                        success:function (phpdata) {
                            if(phpdata == 5){
                                layer.msg('请勿重复审核！');
                                history.back();
                                return false;
                            }
                            if(phpdata == 4){
                                layer.msg('待提交数据不能审核！');
                                history.back();
                                return false;
                            }
                            if(phpdata ==1){
                                layer.msg('驳回成功！');
                                layer.closeAll('page');
                                history.back();
                            }else if(phpdata == 0){
                                layer.msg('驳回失败！');
                            }
                        },error:function () {
                            layer.msg('操作失败！');
                        }
                    });
                })
                //驳回其他
                $('select[name="reason"]').change(function () {
                    if($(this).val() == 3){
                        $('.txa').css({'visibility':'visible'});
                    }else{
                        $('.txa').css({'visibility':'hidden'});
                    }
                })
            }

        })
    })
</script>
<style type="text/css">
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
    .txa{
        visibility: hidden;
    }
</style>