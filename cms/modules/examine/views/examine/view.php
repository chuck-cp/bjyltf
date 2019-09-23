<?php

use yii\helpers\Html;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use cms\modules\member\models\Member;
use common\libs\ToolsClass;
use cms\modules\examine\models\ActivityDetail;
use cms\modules\member\models\MemberInfo;

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
    <?$applyinfo = ShopApply::getCompanyById($model->id);?>
    <?$parent = Member::findOne(['id'=>$model->parent_member_id]);?>
    <?$num = ActivityDetail::find()->where(['shop_name'=>$model->name])->count();?>
    <?$memberList = MemberInfo::findOne(['member_id'=>$model->shop_member_id]);
        if(empty($memberList)){
            $checkName = '未实名';
        }elseif($memberList->examine_status == 1){
            if($memberList->name != $applyinfo->apply_name){
                $checkName = '实名信息不符';
            }else{
                $checkName = '已实名';
            }
        }else{
            $checkName = '未实名';
        }
    ?>
    <table class="table table-hover">
        <h4><b>商家基本信息</b></h4>
        <tr>
            <? if($headlistShopid>0):?>
                <h6><span style="color:red;">该店铺关联的连锁分店已经绑定其他店铺，请注意！</span></h6>
            <?endif;?>
        </tr><tr>
            <td>店铺编号：</td>
            <td><?=Html::encode($model->id)?></td>
        </tr>
        <tr>
            <td>公司名称：</td>
            <td><?=Html::encode($applyinfo->company_name)?><?if($model->repeat_company_name==1):?><span style="color: red;margin-left:10px; ">重复</span><?endif;?></td>
            <td>商家名称：</td>
            <td><?=Html::encode($model->name)?> <?if($num>0):?>(<span style="color: red;"><b>有推荐信息</b></span>)<?endif;?></td>
        </tr>
        <tr>
            <td>统一社会信用代码：</td>
            <td><?=Html::encode($applyinfo->registration_mark)?></td>
            <td>店铺经营类型：</td>
            <td><?=Html::encode(Shop::getTypeByNum($model->shop_operate_type))?></td>
        </tr>
        <tr>
            <td>所属地区：</td>
            <td><?=Html::encode($model->area_name)?></td>
            <td>详细地址：</td>
            <td><?=Html::encode($model->address)?></td>
        </tr>
        <tr>
            <td>镜面数量：</td>
            <td><?=Html::encode($model->mirror_account)?> 面</td>
            <td>店铺面积：</td>
            <td><?=Html::encode($model->acreage)?> (平方米)</td>
        </tr>
        <tr>
            <td>申请数量：</td>
            <td>
                <div id="num" class="inline-block col-md-7" style="margin-left: -15px;">
                    <?=Html::encode($model->apply_screen_number)?>面
                    <? if($model->status == 0 ||$model->status==2):?>
                        <a id="modify" style="margin-left: 20px;">修改</a>
                    <? endif;?>
                </div>
            </td>
            <td>实际数量：</td>
            <td>
                <div id="fact" class="inline-block col-md-12" style="margin-left: -15px;">
                    <?=Html::encode($model->screen_number)?>面
                </div>
            </td>
        </tr>
        <tr>
            <td>业务员姓名：</td>
            <td>
                <? if($model->member_id > 0):?>
                    <?=Html::encode($model->member_name)?>
                    <a href="<?=\yii\helpers\Url::to(['/member/member/view','id'=>$model->member_id])?>" style="padding-left: 15px;color: darkgreen;cursor: pointer;text-decoration: none;">查看</a>
                <? else:?>
                    <span>---</span>
                <? endif;?>
            </td>
            <td>业务员手机：</td>
            <td>
                <? if($model->member_id > 0):?>
                    <?=Html::encode($model->member_mobile)?>
                <? else:?>
                    <span>---</span>
                <? endif;?>
            </td>
        </tr>
        <tr>
            <td>上级业务员姓名：</td>
            <td>
                <? if($model->parent_member_id > 0):?>
                    <?=Html::encode($parent->name)?>
                    <a href="<?=\yii\helpers\Url::to(['/member/member/view','id'=>$model->parent_member_id])?>" style="padding-left: 15px;color: darkgreen;cursor: pointer;text-decoration: none;">查看</a>
                <? else:?>
                    <span>---</span>
                <? endif;?>
            </td>
            <td>上级业务员手机：</td>
            <td>
                <? if($model->parent_member_id > 0):?>
                    <?=Html::encode($parent->mobile)?>
                <? else:?>
                    <span>---</span>
                <? endif;?>
            </td>
        </tr>
        <tr>
            <td>推荐人姓名：</td>
            <td><?=Html::encode($model->introducer_member_name?$model->introducer_member_name:'---')?></td>
            <td>推荐人电话：</td>
            <td><?=Html::encode($model->introducer_member_mobile?$model->introducer_member_mobile:'---')?></td>
        </tr>
        <tr>
            <td>法人代表：</td>
            <td><?=Html::encode($applyinfo->apply_name)?> <span style="color: red;"><?=Html::encode($checkName)?></span></td>
            <td>法人电话：</td>
            <td><?=Html::encode($applyinfo->apply_mobile)?><?if($model->repeat_mobile==1):?><span style="color: red;margin-left:10px; ">重复</span><?endif;?></td>
        </tr>
        <tr>
            <td>店铺联系人姓名：</td>
            <td><?=Html::encode($applyinfo->contacts_name)?></td>
            <td>店铺联系人电话：</td>
            <td><?=Html::encode($applyinfo->contacts_mobile)?></td>
        </tr>
        <tr>
            <td>独家买断费用：</td>
            <td><?=Html::encode(ToolsClass::priceConvert($applyinfo->apply_brokerage))?> 元</td>
            <td>申请客户端：</td>
            <td>
                <?php if($model->apply_client == 0):?>
                    <span>手机端</span>
                <?php else:?>
                    <span>PC端</span>
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <td>申请时间：</td>
            <td><?=Html::encode($model->create_at)?></td>
            <td>状态：</td>
            <td><?=Html::encode(Shop::getStatusByNum($model->status).'->'.Shop::getExamineByNum($model->examine_number))?></td>
        </tr>
        <tr>
            <td>申请编号：</td>
            <td><?=Html::encode($applyinfo->apply_code)?></td>
            <td>动态码：</td>
            <td><?=Html::encode($applyinfo->dynamic_code)?></td>
        </tr>
        <tr>
            <td>开机时间：</td>
            <td><?=Html::encode($applyinfo->screen_start_at)?> - <?=Html::encode($applyinfo->screen_end_at)?></td>
            <td></td>
            <td></td>
        </tr>
        <tr><td colspan="4"><h4><b>商家图片信息</b></h4></td></tr>
        <th><b>法人证件信息</b></th>
        <th></th>
        <th><b>联系人证件信息</b></th>
        <th></th>
        <tr>
            <td style="text-align: center">
                <?php if($applyinfo->identity_card_front):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->identity_card_front)?>" title="身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($applyinfo->identity_card_back):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->identity_card_back)?>" title="身份证背面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($applyinfo->agent_identity_card_front):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->agent_identity_card_front)?>" title="联系人身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($applyinfo->agent_identity_card_back):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->agent_identity_card_back)?>" title="联系人身份证背面照" alt="">
                <?php endif;?>
            </td>
        </tr>
        <th>营业执照：</th>
        <th>店面门面：</th>
        <th>店铺全景：</th>
        <th></th>
        <tr>
            <td style="text-align: center">
                <?php if($applyinfo->business_licence):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->business_licence)?>" title="营业执照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->shop_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->shop_image)?>" title="店面门面" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($applyinfo->panorama_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->panorama_image)?>" title="店铺全景" alt="">
                <?php endif;?>
            </td>
            <td></td>
        </tr>
        <?if($model->shop_operate_type==1):?>
            <tr>
                <td colspan="4">授权证明</td>
            </tr>
            <tr>
                <td colspan="4">
                    <?if($applyinfo->authorize_image):?>
                        <?foreach (explode(',',$applyinfo->authorize_image) as $v):?>
                            <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($v)?>" title="授权证明" alt="">
                        <?endforeach;?>
                    <?endif;?>
                </td>
            </tr>
        <?endif;?>
        <tr>
            <td colspan="4">其他图片</td>
        </tr>
        <tr>
            <td colspan="4">
                <?if($applyinfo->other_image):?>
                    <?foreach (explode(',',$applyinfo->other_image) as $vv):?>
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vv)?>" title="授权证明" alt="">
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
        <?if(!empty($desc)):?>
            <tr><th colspan="4" style="font-size: 18px;"><b>审核信息</b></th></tr>
            <?php foreach ($desc as $v):?>
                <tr>
                    <td colspan="4">
                        日期：<?=Html::encode($v['create_at'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        操作人：<?=Html::encode($v['create_user_name'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <? if($v['examine_result']==2):?>
                            结果：<?=Html::encode($v['examine_desc'])?>
                        <? elseif ($v['examine_result']==1):?>
                            结果：已通过审核
                        <? endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
        <?endif;?>
    </table>
    <div class="row text-center" style="margin-top: 50px;" shop_id="<?=Html::encode($model->id)?>">
        <? if($model->status == 0):?>
            <? if($model->last_examine_user_id != Yii::$app->user->identity->getId()):?>
                <div class="row one">
                <? if($headlistShopid==0):?>
                    <button type="button" class="btn btn-primary ck" data-type="pass">审核通过</button>
                <?endif;?>
                    <button type="button" class="btn btn-danger ck" data-type="rebut">驳回</button>
                </div>
            <?else:?>
                <div class="row two">
                    <button type="button" class="btn btn-primary firm" data-type="close">确定</button>
                </div>
            <?endif;?>
        <?/* elseif($model->status == 1):*/?><!--
            <div class="row two">
                <button type="button" class="btn btn-success firm">审核未通过！</button>
            </div>-->
        <? else:?>
            <div class="row two">
                <button type="button" class="btn btn-primary firm" data-type="close">确定</button>
            </div>
        <? endif;?>
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
        $('.ck').bind('click',function () {
            var type = $(this).attr('data-type');
            var shop_id = $(this).parents('.text-center').attr('shop_id');
            if(type == 'pass'){
                layer.confirm('您确定要审核通过吗？', {
                    btn: ['通过','取消'] //按钮
                }, function(){
                    $.ajax({
                        url : '<?=\yii\helpers\Url::to(['examine'])?>',
                        type : 'POST',
                        dataType : 'json',
                        data : {'type':type, 'shop_id': shop_id},
                        success:function (phpdata) {
                            if(phpdata == 5){
                                layer.msg('请勿重复审核通过！');
                                return false;
                            }
                            if(phpdata ==1){
                                layer.msg('审核通过成功！');
                                history.back();
                            }else if(phpdata ==2){
                                layer.msg('审核通过成功');
                                history.back();
                            }else if(phpdata ==3){
                                layer.msg('该分店已绑定店铺！');
                            }else if(phpdata ==0){
                                layer.msg('审核失败！');
                            }
                        },
                        error:function () {
                            layer.msg('操作失败！');
                        }
                    });
                }, function(){

                });
            }else if(type == 'rebut'){
                var shop_id = $(this).parents('.text-center').attr('shop_id');
                //页面层
                pg = layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['470px', '290px'], //宽高
                    shadeClose: true,
                    content: '<div class="row scroll form-horizontal"  style="margin-top: 15px;"><label class="col-sm-3 control-label" for="formGroupInputLarge">驳回原因：</label><div class="col-sm-6"><select name="reason" id="" class="form-control"><option value="1">商家信息有误</option><option value="2">地址有误</option><option value="3">其他</option></select></div></div><div class="row scroll form-horizontal txa" style="margin-top: 15px;"><label for="" class="col-sm-3 control-label">其他原因：</label><div class="col-sm-6"><textarea class="form-control" rows="3"></textarea></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
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
                    //return;
                    $.ajax({
                        url : '<?=\yii\helpers\Url::to(['examine'])?>',
                        type : 'POST',
                        dataType : 'json',
                        data : {'type':type, 'shop_id': shop_id, 'desc':desc},
                        success:function (phpdata) {
                            if(phpdata == 5){
                                layer.msg('请勿重复审核！');
                                return false;
                            }
                            if(phpdata ==1){
                                layer.msg('驳回成功！');
                                $('.one').css({'display':'none'});
                                $('.two').css({'display':'block'});
                                layer.closeAll('page');
                                history.back();
                            }else if(phpdata == 2){
                                layer.msg('驳回成功！');
                                $('.one').css({'display':'none'});
                                $('.two').css({'display':'block'});
                                layer.closeAll('page');
                                history.back();
                            }else if(phpdata == 0){
                                layer.msg('驳回失败！');
                            }
                        },
                        error:function () {
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
        //点击修改申请镜面数量
        $('#modify').bind('click',function () {
            if($('.inp').size() == 0){
                $(this).prev().hide();
                $(this).before('<div class="col-sm-5 inline-block inp"><input type="text" class="form-control input-sm" name="mirror" placeholder="请输入镜面数量"></div>');
                $(this).html('完成');
            }else{
                var num = $('.input-sm').val();
                var shop_id = "<?=Html::encode($model->id)?>";
                if(!isInteger(num) || num == 0){
                    layer.msg('镜面数量必须是大于零的数字！');
                    return false;
                }
                $.ajax({
                    url : '<?=\yii\helpers\Url::to(['modify-screen'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'num':num, 'shop_id': shop_id},
                    success:function (phpdata) {
                        if(phpdata){
                            $("#fact").html(num+'面');
                            layer.msg('修改成功！');
                        }else{
                            layer.msg('修改失败！');
                        }
                    },error:function () {
                        layer.msg('修改失败！');
                    }
                });
                $(this).html('修改');
                $(this).prev().remove();
                $('#num').show();
            }
        })
        function isInteger(x) {
            return x % 1 === 0;
        }
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
