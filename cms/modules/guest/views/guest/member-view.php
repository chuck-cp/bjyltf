<?php

use yii\helpers\Html;
use cms\models\SystemAddress;
/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\Member */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '人员查询', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-view">
<!--    --><?php //echo $this->render('layout/tab',['model'=>$model]);?>
    <table class="table table-hover">
        <tr >
            <th style="border-top: none;font-size: 18px;"><b>个人信息：</b></th>
            <th style="border-top: none"></th>
            <th style="border-top: none"></th>
            <th style="border-top: none"></th>
        </tr>
        <tr>
            <td>姓名：</td>
            <td><?= Html::encode($model->memIdcardInfo['name'])?></td>
            <td>身份证号：</td>
            <td><?=Html::encode($model->memIdcardInfo['id_number'])?></td>
        </tr>
        <tr>
            <td>手机号：</td>
            <td><?=Html::encode($model->mobile)?></td>
            <td>性别：</td>
            <td><?=Html::encode($model->memIdcardInfo['sex']==1?'男':'女')?></td>
        </tr>
        <tr>
            <td>毕业学校：</td>
            <td><?=Html::encode($model->school)?></td>
            <td>学历：</td>
            <td><?=Html::encode($model->education)?></td>
        </tr>
        <tr>
            <td>所属省：</td>
            <td><?=Html::encode(SystemAddress::getAreaByIdLen($model->area,5))?></td>
            <td>所属市：</td>
            <td><?=Html::encode(SystemAddress::getAreaByIdLen($model->area,7))?></td>
        </tr>
        <tr>
            <td>所属区：</td>
            <td><?=Html::encode(SystemAddress::getAreaByIdLen($model->area,9))?></td>
            <td>所属街道：</td>
            <td><?=Html::encode(SystemAddress::getAreaByIdLen($model->area,11))?></td>
        </tr>
        <tr>
            <td>上级：</td>
            <td>
                <?=Html::encode($model->getMemByNumber($model->parent_id)['name'])?>
                &nbsp;&nbsp;&nbsp;
                <?php if($model->getMemByNumber($model->parent_id)['id']): ?>
                    <?=Html::a('查看',['view','id'=>$model->getMemByNumber($model->parent_id)['id']])?>
                <?php endif?>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>详细地址：</td>
            <td><?=Html::encode($model->address)?></td>
            <td rowspan="2">头像：</td>
            <td rowspan="2">
                <?php if($model->avatar):?>
                    <?=Html::tag('img','',['src'=>\common\libs\ToolsClass::replaceCosUrl($model->avatar),'height'=>'80px','width'=>'auto'])?>
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <td>入驻时间：</td>
            <td><?=Html::encode($model->create_at)?></td>
        </tr>
    </table>
    <table class="table table-hover">
        <tr><th>紧急联系人:</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>紧急联系人：</td>
            <td><?=Html::encode($model->emergency_contact_name)?></td>
            <td>关系：</td>
            <td><?=Html::encode($model->emergency_contact_relation)?></td>
        </tr>
        <tr>
            <td>联系电话：</td>
            <td><?=Html::encode($model->emergency_contact_mobile)?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="font-size: 18px;"><b>证件信息：</b></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr class="last">
            <td>
                <?php if($model->memIdcardInfo['id_front_image']):?>
                    <img src="<?=Html::encode(\common\libs\ToolsClass::replaceCosUrl($model->memIdcardInfo['id_front_image']))?>" alt="" title="身份证正面照" width="200px" height="auto">
                <?php endif;?>
            </td>
            <td>
                <?php if($model->memIdcardInfo['id_back_image']):?>
                    <img src="<?=Html::encode(\common\libs\ToolsClass::replaceCosUrl($model->memIdcardInfo['id_back_image']))?>" alt="" width="200px" height="auto" title="身份证反面照">
                <?php endif;?>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr class="last">
            <td>
                <?php if($model->memIdcardInfo['id_hand_image']):?>
                    <img src="<?=Html::encode(\common\libs\ToolsClass::replaceCosUrl($model->memIdcardInfo['id_hand_image']))?>" alt="" width="200px" height="auto" title="手持身份证照">
                <?php endif;?>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
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
                                layer.alert('请勿重复审核通过！');
                                return false;
                            }
                            if(phpdata ==1){
                                layer.alert('审核通过成功！');
                            }else if(phpdata ==0){
                                layer.alert('审核失败！');
                            }
                        },error:function () {
                            layer.alert('操作失败！');
                        }
                    });
                }, function(){

                });
            }else if(type == 'rebut'){
                //页面层
                  pg = layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['440px', '260px'], //宽高
                    shadeClose: true,
                    content: '<div class="row scroll form-horizontal"  style="margin-top: 15px;"><label class="col-sm-3 control-label" for="formGroupInputLarge">驳回原因</label><div class="col-sm-5"><select name="reason" id="" class="form-control"><option value="1">身份证信息有误</option><option value="2">地址有误</option><option value="3">其他</option></select></div></div><div class="row scroll text-center" style="margin-top: 99px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
                });

                //驳回

                $('.confirm').bind('click',function () {
                    var desc = $('[name="reason"]').val();
                    $.ajax({
                        url : '<?=\yii\helpers\Url::to(['examine-card'])?>',
                        type : 'POST',
                        dataType : 'json',
                        data : {'type':type, 'member_id': member_id, 'desc':desc},
                        success:function (phpdata) {
                            if(phpdata == 5){
                                layer.alert('请勿重复驳回！');
                                history.back();
                                return false;
                            }
                            if(phpdata ==1){
                                layer.alert('驳回成功！');
                                layer.closeAll('page');
                                history.back();
                            }else if(phpdata == 0){
                                layer.alert('驳回失败！');
                            }
                        },error:function () {
                            layer.alert('操作失败！');
                        }
                    });
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
</style>