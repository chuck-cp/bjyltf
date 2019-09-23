<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\ShopUpdateRecord */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Update Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-update-record-view">
    <table class="table table-hover">
        <th colspan="4" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;"><b>商家原基本信息：</b></th>
        <tr>
            <td>店铺编号：</td>
            <td class="shopid"><?=Html::encode($model->shop_id)?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>法人代表：</td>
            <td><?=Html::encode($model->apply_name)?></td>
            <td>法人电话：</td>
            <td><?=Html::encode($model->apply_mobile)?></td>
        </tr>
        <tr>
            <td>身份证号码：</td>
            <td><?=Html::encode($model->identity_card_num)?></td>
            <td>申请时间：</td>
            <td><?=Html::encode($smodel->create_at)?></td>
        </tr>
        <tr>
            <td>店铺联系人姓名：</td>
            <td><?=Html::encode($amodel->contacts_name)?></td>
            <td>店铺联系人电话：</td>
            <td><?=Html::encode($amodel->contacts_mobile)?></td>
        </tr>
        <tr>
            <td>推荐人姓名：</td>
            <td><?=Html::encode($smodel->introducer_member_name?$smodel->introducer_member_name:'---')?></td>
            <td>推荐人电话：</td>
            <td><?=Html::encode($smodel->introducer_member_mobile?$smodel->introducer_member_mobile:'---')?></td>
        </tr>
        <tr>
            <td>公司名称：</td>
            <td><?=Html::encode($model->company_name)?></td>
            <td>店铺名称：</td>
            <td><?=Html::encode($model->shop_name)?></td>
        </tr>

        <tr>
            <td>统一社会信用代码：</td>
            <td><?=Html::encode($model->registration_mark)?></td>
            <td>店铺面积：</td>
            <td><?=Html::encode($smodel->acreage.'（平米）')?></td>

        </tr>
        <tr>
            <td>所属地区：</td>
            <td><?=Html::encode($smodel->area_name)?></td>
            <td>详细地址：</td>
            <td><?=Html::encode($smodel->address)?></td>
        </tr>

        <th colspan="4" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;"><b>商家修改后信息：</b></th>
        <tr>
            <td>法人代表：</td>
            <td><?=Html::encode($model->update_apply_name)?></td>
            <td>法人电话：</td>
            <td><?=Html::encode($model->update_apply_mobile)?></td>
        </tr>
        <tr>
            <td>身份证号码：</td>
            <td><?=Html::encode($model->update_identity_card_num)?></td>
            <td>理发店名称：</td>
            <td><?=Html::encode($model->update_shop_name)?></td>
        </tr>
        <tr>
            <td>公司名称：</td>
            <td><?=Html::encode($model->update_company_name)?></td>
            <td>统一社会信用码：</td>
            <td><?=Html::encode($model->update_registration_mark)?></td>
        </tr>
        <tr>
            <td>联系人：</td>
            <td><?=Html::encode($model->update_contacts_name)?></td>
            <td>联系人电话：</td>
            <td><?=Html::encode($model->update_contacts_mobile)?></td>
        </tr>
        <tr>
            <td>修改后地区：</td>
            <td><?=Html::encode($model->update_area_name)?></td>
            <td>修改后详细地址：</td>
            <td><?=Html::encode($model->update_address)?></td>
        </tr>
    </table>
    <table class="table table-hover">
        <th colspan="5" style="border-top: none;font-size: 18px;background-color:#486d93; color: #fff;"><b>商家原图片信息</b></th>
        <tr>
            <th style="text-align: center" colspan="2">法人证件信息</th>
            <th style="text-align: center" colspan="2">联系人证件信息</th>
            <th style="text-align: center">营业执照信息</th>
        </tr>
        <tr>
            <td style="text-align: center">
                <?php if($model->identity_card_front):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->identity_card_front)?>" title="身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->identity_card_back):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->identity_card_back)?>" title="身份证背面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->agent_identity_card_front):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->agent_identity_card_front)?>" title="代理人身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->agent_identity_card_back ):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->agent_identity_card_back)?>" title="代理人身份证背面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->business_licence ):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->business_licence)?>" title="营业执照信息" alt="">
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <th style="text-align: center">店铺门面</th>
            <th style="text-align: center">店铺全景</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="text-align: center;">
                <?php if($model->shop_image):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->shop_image)?>" title="店铺门面图" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center;">
                <?php if($model->panorama_image):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->panorama_image)?>" title="店铺全景图" alt="">
                <?php endif;?>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center" colspan="5">授权证明</th>
        </tr>
        <tr>
            <?if($model->authorize_image):?>
                <?foreach (explode(',',$model->authorize_image) as $ka=>$va):?>
                    <td style="text-align: center">
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($va)?>" title="授权证明" alt="">
                    </td>
                <?endforeach;?>
            <?endif;?>
        </tr>
        <tr>
            <th style="text-align: center" colspan="5">其他资质</th>
        </tr>
        <tr>
            <?if($model->other_image):?>
                <?foreach (explode(',',$model->other_image) as $ko=>$vo):?>
                    <td style="text-align: center">
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vo)?>" title="其他资质" alt="">
                    </td>
                <?endforeach;?>
            <?endif;?>
        </tr>

        <th colspan="5" style="border-top: none;font-size: 18px;background-color:#486d93; color: #fff;"><b>商家修改后图片信息</b></th>
        <tr>
            <th style="text-align: center" colspan="2">法人证件信息</th>
            <th style="text-align: center" colspan="2">联系人证件信息</th>
            <th style="text-align: center">营业执照信息</th>
        </tr>
        <tr>
            <td style="text-align: center">
                <?php if($model->update_identity_card_front):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_identity_card_front)?>" title="身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->update_identity_card_back):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_identity_card_back)?>" title="身份证背面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->update_agent_identity_card_front):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_agent_identity_card_front)?>" title="代理人身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->update_agent_identity_card_back ):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_agent_identity_card_back)?>" title="代理人身份证背面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->update_business_licence ):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_business_licence)?>" title="营业执照信息" alt="">
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <th style="text-align: center">店铺门面</th>
            <th style="text-align: center">店铺全景</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="text-align: center;">
                <?php if($model->update_shop_image ):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_shop_image)?>" title="店铺门面图" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center;">、
                <?php if($model->update_panorama_image ):?>
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_panorama_image)?>" title="店铺全景图" alt="">
                <?php endif;?>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center" colspan="5">授权证明</th>
        </tr>
        <tr>
            <?if($model->update_authorize_image):?>
                <?foreach (explode(',',$model->update_authorize_image) as $kua=>$vua):?>
                    <td style="text-align: center">
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vua)?>" title="授权证明" alt="">
                    </td>
                <?endforeach;?>
            <?endif;?>
        </tr>
        <tr>
            <th style="text-align: center" colspan="5">其他资质</th>
        </tr>
        <tr>
            <?if($model->update_other_image):?>
                <?foreach (explode(',',$model->update_other_image) as $kuo=>$vuo):?>
                    <td style="text-align: center">
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vuo)?>" title="其他资质" alt="">
                    </td>
                <?endforeach;?>
            <?endif;?>
        </tr>
        <?if(!empty($desc)):?>
            <tr><th colspan="5" style="border-top: none;font-size: 18px;background-color:#486d93; color: #fff;"><b>审核信息</b></th></tr>
            <?php foreach ($desc as $v):?>
                <tr>
                    <td colspan="5">
                        日期：<?=Html::encode($v['create_at'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        操作人：<?=Html::encode($v['create_user_name'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <? if($v['examine_result']==2):?>
                            结果：<?=Html::encode('审核驳回，原因：'.$v['examine_desc'])?>
                        <? elseif ($v['examine_result']==1):?>
                            结果：已通过审核
                        <? elseif ($v['examine_result']==0):?>
                            结果：系统申请
                        <? endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
        <?endif;?>
    </table>
    <div class="row text-center" style="margin-top: 50px;" id="<?=Html::encode($model->id)?>">
        <? if($model->examine_status == 0):?>
            <div class="row one">
                <button type="button" class="btn btn-primary ck" data-type="pass">审核通过</button>
                <button type="button" class="btn btn-danger ck" data-type="rebut">驳回</button>
            </div>
        <? else:?>
            <div class="row two">
                <button type="button" class="btn btn-primary firm" data-type="close">确定</button>
            </div>
        <? endif;?>
    </div>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.shop-update-record-view').viewer({
        url: 'src',
    });
    $(function () {
        //审核不通过详情页点击确定
        $('.firm').bind('click', function () {
            history.back();
        })
        //审核通过
        $('.ck').bind('click', function () {
            var type = $(this).attr('data-type');
            var id = $(this).parents('.text-center').attr('id');
            if (type == 'pass') {
                layer.confirm('您确定要审核通过吗？', {
                    btn: ['通过', '取消'] //按钮
                }, function () {
                    $.ajax({
                        url: '<?=\yii\helpers\Url::to(['choose-examine'])?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {'type': type, 'id': id},
                        success: function (phpdata) {
                            if (phpdata == 5) {
                                layer.msg('请勿重复审核通过！');
                                return false;
                            }
                            if (phpdata == 4) {
                                layer.msg('手机号未注册！');
                                return false;
                            }
                            if (phpdata == 1) {
                                layer.msg('审核通过成功！');
                                history.back();
                            } else if (phpdata == 0) {
                                layer.msg('审核失败！');
                            }
                        },
                        error: function () {
                            layer.msg('操作失败！');
                        }
                    });
                }, function () {

                });
            } else if (type == 'rebut') {
                var id = $(this).parents('.text-center').attr('id');
                //页面层
                pg = layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['470px', '290px'], //宽高
                    shadeClose: true,
                    content: '<div class="row scroll form-horizontal txa" style="margin-top: 15px;"><label for="" class="col-sm-3 control-label">驳回原因：</label><div class="col-sm-6"><textarea class="form-control" rows="3"></textarea></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
                });

                //驳回
                $('.confirm').bind('click', function () {
                    var desc = $('.txa textarea').val();
                    if (!desc) {
                        layer.msg('请填写驳回原因！');
                        return false;
                    }
                    $.ajax({
                        url: '<?=\yii\helpers\Url::to(['choose-examine'])?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {'type': type, 'id': id, 'desc': desc},
                        success: function (phpdata) {
                            if (phpdata == 5) {
                                layer.msg('请勿重复审核！');
                                return false;
                            }
                            if (phpdata == 1) {
                                layer.msg('驳回成功！');
                                $('.one').css({'display': 'none'});
                                $('.two').css({'display': 'block'});
                                layer.closeAll('page');
                                history.back();
                            } else if (phpdata == 2) {
                                layer.msg('驳回成功！');
                                $('.one').css({'display': 'none'});
                                $('.two').css({'display': 'block'});
                                layer.closeAll('page');
                                history.back();
                            } else if (phpdata == 0) {
                                layer.msg('驳回失败！');
                            }
                        },
                        error: function () {
                            layer.msg('操作失败！');
                        }
                    });
                })
                //驳回其他
                // $('select[name="reason"]').change(function () {
                //     if ($(this).val() == 3) {
                //         $('.txa').css({'visibility': 'visible'});
                //     } else {
                //         $('.txa').css({'visibility': 'hidden'});
                //     }
                // })
            }

        })
    })
</script>