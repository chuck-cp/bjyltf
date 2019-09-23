<?php

use yii\helpers\Html;
use cms\modules\shop\models\ShopApply;
use cms\models\LogExamine;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\ShopRemark;
use cms\modules\examine\models\ShopScreenReplace;
use cms\modules\shop\models\Shop;
use common\libs\ToolsClass;
use cms\modules\member\models\Member;
use cms\modules\examine\models\ShopHeadquarters;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '总部信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?$applyinfo = ShopApply::getCompanyById($model->id);?>
<?$LogExamine = LogExamine::getCkeck($model->id,1);?>
<?$LogExamine4 = LogExamine::getCkeck($model->id,4);?>
<?$screen = Screen::getScreenInfo($model->id);?>
<?$remarkArr = ShopRemark::getRemarkArr($model->id);?>
<?$parent = Member::findOne(['id'=>$model->parent_member_id]);?>
<?if($model->replace_screen_status>0):?>
    <?$rescreenlist = ShopScreenReplace::getReplaceScreenList($model->id);?>
<?endif;?>
<?$headModel = ShopHeadquarters::findOne(['id'=>$model->headquarters_id]);?>

<div class="shop-headquarters-view">
    <table class="table table-hover">
        <th colspan="4" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;"><b>商家信息：</b></th>
        <tr>
            <td>店铺编号：</td>
            <td class="shopid"><?=Html::encode($model->id)?></td>
        </tr>
        <tr>
            <td>商家名称：</td>
            <td><?=Html::encode($model->name)?></td>
            <td>公司名称：</td>
            <td><?=Html::encode($applyinfo->company_name)?></td>
        </tr>
        <tr>
            <td>所属地区：</td>
            <td><?=Html::encode($model->area_name)?></td>
            <td>详细地址：</td>
            <td><?=Html::encode($model->address)?></td>
        </tr>
        <tr>
            <td>法人代表：</td>
            <td><?=Html::encode($applyinfo->apply_name)?></td>
            <td>法人电话：</td>
            <td><?=Html::encode($applyinfo->apply_mobile)?></td>
        </tr>
        <tr>
            <td>业务员姓名：</td>
            <td><?=Html::encode($model->member_name)?></td>
            <td>业务员电话：</td>
            <td><?=Html::encode($model->member_mobile)?></td>
        </tr>
        <tr>
            <td>上级业务员姓名：</td>
            <td><?=Html::encode($parent?$parent->name:'---')?></td>
            <td>上级业务员电话：</td>
            <td><?=Html::encode($parent?$parent->mobile:'---')?></td>
        </tr>
        <tr>
            <td>店铺联系人姓名：</td>
            <td><?=Html::encode($applyinfo->contacts_name)?></td>
            <td>店铺联系人电话：</td>
            <td><?=Html::encode($applyinfo->contacts_mobile)?></td>
        </tr>
        <tr>
            <td>申请数量：</td>
            <td><?=Html::encode($model->apply_screen_number)?></td>
            <td>修改安装数量：</td>
            <td><?=Html::encode($model->screen_number)?></td>
        </tr>
        <tr>
            <td>镜面数量：</td>
            <td><?=Html::encode($model->mirror_account)?></td>
            <td>店铺面积：</td>
            <td><?=Html::encode($model->acreage)?> (平方米)</td>
        </tr>
        <tr>
            <td>申请时间：</td>
            <td><?=Html::encode($model->create_at)?></td>
            <td>状态：</td>
            <td><?=Html::encode(Shop::getStatusByNum($model->status))?></td>
        </tr>
        <tr>
            <? if($model->status == 5):?>
                <td>协议附件：</td>
                <td>
                    <a class="media" href="https://i1.bjyltf.com/agreement/<?=Html::encode($model->agreement_name)?>" target="_blank">预览</a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="<?=\yii\helpers\Url::to(['/examine/order/confirm'])?>&url=https://i1.bjyltf.com/agreement/<?=Html::encode($model->agreement_name)?>&filename=视频播放安装协议.pdf"><i>下载</i></a>
                </td>
            <? endif; ?>
            <td>独家买断费用：</td>
            <td ><?=Html::encode(ToolsClass::priceConvert($applyinfo->apply_brokerage))?></td>
        </tr>
        <tr>
            <td>开机时间</td>
            <td><?=Html::encode($applyinfo->screen_start_at)?> - <?=Html::encode($applyinfo->screen_end_at)?></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table class="table table-hover">
        <th colspan="4" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;"><b>总部信息：</b></th>
        <tr>
            <th>
                <span class="listvs">公司名称</span>
                <span class="listvs">所属地区</span>
                <span class="listvs">详细地址</span>
                <span class="listvs">申请时间</span>
                <span class="listvs">操作</span>
            </th>
        </tr>
        <tr>
            <td colspan="4">
                <span class="listvs"><?=Html::encode($headModel->company_name)?></span>
                <span class="listvs"><?=Html::encode($headModel->company_area_name)?></span>
                <span class="listvs"><?=Html::encode($headModel->company_address)?></span>
                <span class="listvs"><?=Html::encode($headModel->create_at)?></span>
                <span class="listvs"><?=Html::a('查看',['/shop/shop-headquarters/view','id'=>$headModel->id],['target'=>'_blank'])?></span>
            </td>
        </tr>
    </table>
    <table class="table table-hover">
        <th colspan="4" style="border-top: none;font-size: 18px;background-color:#486d93; color: #fff;"><b>商家图片信息</b></th>
        <tr>
            <th>法人证件信息</th>
            <th></th>
            <th>联系人证件信息</th>
            <th></th>
        </tr>
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
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->agent_identity_card_front)?>" title="代理人身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($applyinfo->agent_identity_card_back):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->agent_identity_card_back)?>" title="代理人身份证背面照" alt="">
                <?php endif;?>
            </td>
        </tr>
        <th>营业执照信息：</th>
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
                <td  colspan="4">
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
        <th colspan="4"><b>安装位置：</b></th>
        <? foreach($screen as $skey=>$svalue): ?>
            <? if($skey == 0):?><tr><?endif;?>
            <td style="text-align: center;">
                <img  width="150" height="auto" src="<?=Html::encode($svalue['image'])?>" alt="图片存在"/>
            </td>
            <?if(($skey+1)%4==0):?></tr><tr><?elseif($skey ==(count($screen)-1)):?></tr><?endif;?>
        <?endforeach; ?>
    </table>
    <table class="table table-hover">
        <th colspan="4" style="border-top: none;font-size: 18px;background-color:#486d93; color: #fff;"><b>屏幕安装信息</b></th>
        <tr>
            <th colspan="4"><b>审核信息：</b></th>
        </tr>
        <tr>
            <td>审核人：</td>
            <td><?=Html::encode($LogExamine['create_user_name'])?></td>
            <td>审核时间：</td>
            <td><?=Html::encode($LogExamine['create_at'])?></td>
        </tr>
        <?if($model->install_status==1):?>
            <th colspan="4"><b>物流信息：</b></th>
            <tr>
                <td>物流名称：</td>
                <td>
                    <?=Html::encode(\cms\modules\examine\models\ShopLogistics::getLogist($model->id)['name'])?>
                </td>
                <td>订单编号：</td>
                <td>
                    <?=Html::encode(\cms\modules\examine\models\ShopLogistics::getLogist($model->id)['logistics_id'])?>
                </td>
            </tr>
        <?endif;?>
        <th colspan="4"><b>安装详情：</b></th>
        <tr>
            <td>安装人：</td>
            <td><?=Html::encode($model->install_member_name)?></td>
            <td>安装电话：</td>
            <td><?=Html::encode($model->install_mobile)?></td>
        </tr>
        <? for($i=1;$i<=count($screen);$i++): ?>
            <tr>
                <td>设备编号：<?=Html::encode($screen[$i-1]['number'])?> </td>
                <!--<td>--><?//=Html::encode($screen[$i-1]['remark'])?><!--</td>-->
                <td><?=Html::encode(Screen::getScreenStatus($screen[$i-1]['status']))?> </td>
                <td colspan="2">安装地址：<span class="screenadd" sid="<?=Html::encode($screen[$i-1]['software_number'])?>"></span></td>
            </tr>
        <? endfor;?>
        <?if($model->status==5):?>
            <tr>
                <td colspan="4" style="text-align: center;"><button type="button" class="btn btn-primary">更换屏幕</button></td>
            </tr>
        <?endif;?>
        <tr>
            <th colspan="4"><b>安装反馈：</b></th>
        </tr>
        <tr>
            <td>验收人：</td>
            <td>
                <?=Html::encode($LogExamine4['create_user_name'])?>
            </td>
            <td>验收时间：</td>
            <td>
                <?=Html::encode($LogExamine4['create_at'])?>
            </td>
        </tr>
    </table>
    <table class="table table-hover">
        <?if($model->replace_screen_status>0):?>
            <tr>
                <th colspan="4" style="font-size: 18px;background-color:#486d93; color: #fff;"><b>更换屏幕信息</b></th>
            </tr>
            <?foreach($rescreenlist as $keyre=>$valuere):?>
                <tr>
                    <td><?=Html::encode($keyre+1)?>、申请更换时间：<?=Html::encode($valuere['create_at'])?></td>
                    <td>申请更换人：<?=Html::encode($valuere['create_user_name'])?></td>
                    <td>申请更换屏幕数：<?=Html::encode($valuere['replace_screen_number'])?></td>
                    <td></td>
                </tr>
                <?foreach($valuere['data'] as $kvs=>$vvs):?>
                    <tr>
                        <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<?=Html::encode(($kvs+1).'>')?> 设备编号：<?=Html::encode($vvs['device_number'])?> 更换为 设备编号：<?=Html::encode($vvs['replace_device_number'])?></td>
                        <td colspan="2">更换理由：<?=Html::encode($vvs['replace_desc'])?></td>
                    </tr>
                <?endforeach;?>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;安装人：<?=Html::encode($valuere['install_member_name'])?></td>
                    <td>安装电话：<?=Html::encode($valuere['mobile'])?></td>
                    <td></td>
                    <td></td>
                </tr>
                <?foreach($valuere['examines'] as $kex=>$vex):?>
                    <tr>
                        <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;
                            时间：<?=Html::encode($vex['create_at']?$vex['create_at']:'')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            操作人：<?=Html::encode($vex['create_user_name']?$vex['create_user_name']:'')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?if($vex['examine_result']==2):?>
                                结果：审核驳回，原因：<?=Html::encode($vex['examine_desc'])?>
                            <?elseif($vex['examine_result']==1):?>
                                结果：审核通过
                            <?endif;?>
                        </td>
                    </tr>
                <?endforeach;?>
            <?endforeach;?>
        <?endif;?>
    </table>
    <table class="table table-hover">
        <th style="font-size: 18px;background-color:#486d93; color: #fff;">商家备注信息：</th>
        <?if(!empty($remarkArr)):?>
            <?foreach ($remarkArr as $k=>$v):?>
                <tr>
                    <td>
                        <span style="margin-right: 100px;">备注时间：<?echo $v['create_at']?></span> 备注用户名：<?echo $v['create_user_name']?><br /><br />
                        <span>备注内容：<?echo $v['content']?></span><br /><br />
                    </td>
                </tr>
            <?endforeach;?>
        <?endif;?>
    </table>
    <div class="row text-center" style="margin-top: 50px;" shop_id="<?=Html::encode($model->id)?>">
        <button type="button" class="btn btn-primary firm" data-type="close">确定</button>
    </div>
</div>

<style type="text/css">
    .listvs{display: inline-block;text-align: center;width: 19%;}
</style>

<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.shop-headquarters-view').viewer({
        url: 'src'
    });
    $('.firm').bind('click',function () {
        history.back();
    });
</script>