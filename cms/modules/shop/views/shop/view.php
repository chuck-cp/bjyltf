<?php
use yii\helpers\Html;
use cms\modules\shop\models\ShopApply;
use cms\modules\examine\models\ShopScreenReplace;
use common\libs\ToolsClass;
use cms\modules\shop\models\Shop;
use cms\models\LogExamine;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\ShopRemark;
use cms\modules\member\models\Member;
use cms\modules\shop\models\ShopUpdateRecord;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '商家信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    table th:nth-child(odd){
        font-weight: 700;
    }
    table td{word-break: break-word}
</style>
<?$applyinfo = ShopApply::getCompanyById($model->id);?>
<?$LogExamine = LogExamine::getCkeck($model->id,1);?>
<?$LogExamine4 = LogExamine::getCkeck($model->id,4);?>
<?$screen = Screen::getScreenInfo($model->id);?>
<?$remarkArr = ShopRemark::getRemarkArr($model->id);?>
<?$parent = Member::findOne(['id'=>$model->parent_member_id]);?>
<?$rescreenlist = ShopScreenReplace::getReplaceScreenList($model->id);?>
<?$shopChoose = ShopUpdateRecord::getChooseList($model->id);?>
<div class="shop-view" style="width: 100%;">
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
            <td>推荐人姓名：</td>
            <td><?=Html::encode($model->introducer_member_name?$model->introducer_member_name:'---')?></td>
            <td>推荐人电话：</td>
            <td><?=Html::encode($model->introducer_member_mobile?$model->introducer_member_mobile:'---')?></td>
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
<!--    </table>-->
<!--    <table class="table table-hover">-->
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
                <img width="150" height="auto" src="<?=Html::encode($svalue['image'])?>" alt="图片存在"/>
            </td>
            <?if(($skey+1)%4==0):?></tr><tr><?elseif($skey ==(count($screen)-1)):?></tr><?endif;?>
        <?endforeach; ?>
<!--    </table>-->
<!--    <table class="table table-hover">-->
        <th colspan="4" style="border-top: none;font-size: 18px;background-color:#486d93; color: #fff;">
            <b>屏幕安装信息</b>
            <?if($model->status==5):?>
                <b colspan="1" style="float: right;">
                    <button type="button" screentype="2" class="btn btn-primary replace">更换屏幕</button>
                    <button type="button" screentype="4" class="btn btn-primary addnew">新增屏幕</button>
                    <button type="button" screentype="3" class="btn btn-primary delold">拆除屏幕</button>
                </b>
            <?endif;?>
        </th>
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
                <td><?=Html::encode(Screen::getScreenStatus($screen[$i-1]['status']))?> </td>
                <td>安装地址：<span class="screenadd" sid="<?=Html::encode($screen[$i-1]['software_number'])?>"></span></td>
                <td><button type="button" screentype="2" class="btn-primary upcoordinate " rid="<?=Html::encode($screen[$i-1]['software_number'])?>">更新坐标</button></td>
            </tr>
        <? endfor;?>
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
    <table class="table table-hover" style="width: 100%;">
        <tr>
            <th colspan="9" style="font-size: 18px;background-color:#486d93; color: #fff;"><b>维护屏幕信息</b></th>
        </tr>
        <tr>
            <th style="width: 3%;">序号</th>
            <th style="width: 5%;">维护类型</th>
            <th style="width: 5%">申请人</th>
            <th style="width: 5%;">维护人</th>
            <th style="width: 20%;">安装屏幕编号</th>
            <th style="width: 20%;">拆除屏幕编号</th>
            <th style="width: 10%">审核人</th>
            <th style="width: 13%;">审核时间</th>
            <th style="width: 18%;">备注</th>
        </tr>
        <?foreach($rescreenlist as $keyre=>$valuere):?>
            <tr>
                <td><?=Html::encode($keyre+1)?></td>
                <td><?=Html::encode(ShopScreenReplace::getMaintainType($valuere['maintain_type']))?></td>
                <td><?=Html::encode($valuere['create_user_name'])?></td>
                <td><?=Html::encode($valuere['install_member_name'])?></td>
                <td><?=Html::encode($valuere['install_device_number'])?></td>
                <td><?=Html::encode($valuere['remove_device_number'])?></td>
                <td><?=Html::encode($valuere['examine_user_name'])?></td>
                <td><?=Html::encode($valuere['install_finish_at'])?></td>
                <td><?=Html::encode($valuere['description'])?></td>
            </tr>
        <?endforeach;?>
    </table>
    <table class="table table-hover">
        <th colspan="6" style="font-size: 18px;background-color:#486d93; color: #fff;">商家变更信息：</th>
        <tr>
            <th>序号</th>
            <th>变更前法人</th>
            <th>变更后法人</th>
            <th>发起变更时间</th>
            <th>审核通过时间</th>
            <th>操作</th>
        </tr>
        <?if(!empty($shopChoose)):?>
            <?foreach ($shopChoose as $ksc=>$vsc):?>
                <tr>
                    <td><?echo ($ksc+1)?></td>
                    <td><?echo $vsc['apply_name']?></td>
                    <td><?echo $vsc['update_apply_name']?></td>
                    <td><?echo $vsc['create_at']?></td>
                    <td><?echo $vsc['examine_at']?></td>
                    <td><a href="<?=\yii\helpers\Url::to(['/shop/shop/choose-view','id'=>$vsc['id']])?>" style="padding-left: 15px;color: darkgreen;cursor: pointer;text-decoration: none;">变更详情</a></td>
                </tr>
            <?endforeach;?>
        <?endif;?>
    </table>
    <table class="table table-hover">
        <th colspan="4" style="font-size: 18px;background-color:#486d93; color: #fff;">商家备注信息：</th>
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
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/jquery.media.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.panel-body').viewer({
        url: 'src',
    });
    $('.media').on('click',function(){
        $('a.media').media({width:800, height:600});//autoplay: true,src:'视频播放安装协议.pdf',src="https://i1.bjyltf.com/agreement/"+shopid+".pdf"
    });
    //获取屏幕地址
    $(function(){
        var arrid = [];
        $('.screenadd').each(function(){
            var sid = $(this).attr('sid');
            if(sid!=''){
                arrid.push(sid);
            }
        });
        if(arrid.length!=0){
//            var screenid ='241050378318302801cf,240050378318182b070f';
            var screenid =arrid.join(',');
            var baseApiUrl = "<?=\Yii::$app->params['pushProgram']?>";
            var emptyadd = 0;
            $.ajax({
                url:baseApiUrl+'/front/device/selectLocation/'+screenid,
                type : 'GET',
                dataType : 'json',
//                data : {'number':screenid},
                success:function (resdata) {
                    if(resdata.code==0){
                        resdata.data.forEach(function(val,index){
                            var add = val.location;
                            if(add!=null){
                                $('.screenadd').eq(index).html(add.address);
                            }else{
                                emptyadd +=1;
                                $('.screenadd').eq(index).html('');
                            }
                        })
                    }
                },error:function (error) {
                    layer.msg('屏幕地址获取失败！');
                }
            });
        }
    })
    //点击换屏幕/新增/拆屏
    $('.btn').on('click',function(){
        var shop_id = $('.shopid').html();
        var type = $(this).attr('screentype');
        if(type == 2){
            var title = '更换屏幕';
        }else if(type == 3){
            var title = '拆除屏幕';
        }else if(type == 4){
            var title = '新增屏幕';
        }
        layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            shade: 0.8,
            area: ['40%', '50%'],
            content: '<?=\yii\helpers\Url::to(['/shop/shop/upscreen'])?>&shop_id='+shop_id+'&type='+type //iframe的url
//            content: '<?//=\yii\helpers\Url::to(['/shop/shop/rescreen'])?>//&shop_id='+shop_id //iframe的url
        });
    })
    $('.upcoordinate').on('click',function () {
        var shop_id = $('.shopid').html();
        var rid = $(this).attr('rid');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['update-coordinate'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'rid':rid,'shop_id':shop_id},
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
    })
</script>
