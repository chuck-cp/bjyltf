<?php

use yii\helpers\Html;
use cms\modules\shop\models\ShopApply;
use cms\modules\examine\models\ShopScreenReplace;
use common\libs\ToolsClass;
use cms\modules\shop\models\Shop;
use cms\models\LogExamine;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\ShopRemark;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '商家信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    table th:nth-child(odd){
        font-weight: 700;
    }
</style>
<?$applyinfo = ShopApply::getCompanyById($model->id);?>
<?$LogExamine = LogExamine::getCkeck($model->id,1);?>
<?$LogExamine4 = LogExamine::getCkeck($model->id,4);?>
<?$screen = Screen::getScreenInfo($model->id);?>
<?$remarkArr = ShopRemark::getRemarkArr($model->id)?>
<?$rescreenlist = ShopScreenReplace::getReplaceScreenList($model->id);?>
<div class="shop-view">
    <table class="table table-hover">
        <th colspan="4" style="border-top: none;font-size: 18px;"><b>商家基本信息：</b></th>
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
            <td>店铺联系人姓名：</td>
            <td><?=Html::encode($applyinfo->contacts_name)?></td>
            <td>店铺联系人电话：</td>
            <td><?=Html::encode($applyinfo->contacts_mobile)?></td>
        </tr>
        <tr>
            <td>推荐人姓名：</td>
            <td><?=Html::encode($model->introducer_member_name)?></td>
            <td>介绍人手机号：</td>
            <td><?=Html::encode($model->introducer_member_mobile)?></td>
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
            <td colspan="3"><?=Html::encode(ToolsClass::priceConvert($applyinfo->apply_brokerage))?></td>
        </tr>
        <tr>
            <td>申请编号：</td>
            <td><?=Html::encode($applyinfo->apply_code)?></td>
            <td>动态码：</td>
            <td><?=Html::encode($applyinfo->dynamic_code)?></td>
        </tr>
    </table>

    <table class="table table-hover">
        <th colspan="2" style="border-top: none;font-size: 18px;">商家门店信息：</th>
        <tr>
            <td>营业执照</td>
            <td>店铺门面图</td>
            <td>店铺全景图</td>
        </tr>
        <tr>
            <td>
                <?php if($applyinfo->business_licence):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->business_licence)?>" title="营业执照" alt="">
                <?php endif;?>
            </td>
            <td>
                <?php if($applyinfo->panorama_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($applyinfo->panorama_image)?>" title="店铺全景" alt="">
                <?php endif;?>
            </td>
            <td>
                <?php if($model->shop_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->shop_image)?>" title="店面门面" alt="">
                <?php endif;?>
            </td>

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
        <th colspan="4"><b>安装位置：</b></th>
        <? foreach($screen as $skey=>$svalue): ?>
            <? if($skey == 0):?><tr><?endif;?>
            <td>
                <img width="150" height="auto" src="<?=Html::encode($svalue['image'])?>" alt="图片存在"/>
            </td>
            <?if(($skey+1)%4==0):?></tr><tr><?elseif($skey ==(count($screen)-1)):?></tr><?endif;?>
        <?endforeach; ?>
    </table>
    <table class="table table-hover">
        <th colspan="4" style="border-top: none;font-size: 18px;"><b>审核及安装信息：</b></th>
        <tr>
           <th colspan="4"><b>审核信息：</b></th>
        </tr>
        <tr>
            <td>审核人：</td>
            <td><?=Html::encode($LogExamine['create_user_name'])?></td>
            <td>审核时间：</td>
            <td><?=Html::encode($LogExamine['create_at'])?></td>
        </tr>
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
        <th style="font-size: 18px;">备注信息：</th>
        <tr>
            <td><textarea style="width: 100%;height: 150px" placeholder='填写备注信息 （备注信息100字以内）' name="remark"></textarea></td>
        </tr>
        <tr>
            <td><button type="button" class="btn btn-primary remark" id='<?=Html::encode($model->id)?>'>保存备注</button></td>
        </tr>
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

    /**
     * 保存备注
     */
    $('.remark').click(function(){
        var remark=$('textarea[name="remark"]').val();
        var id=$(this).attr('id');
        var jmz = {};
        jmz.GetLength = function(str) {
            return str.replace(/[\u0391-\uFFE5]/g,"aa").length;  //先把中文替换成两个字节的英文，在计算长度
        };
        var len=jmz.GetLength(remark);
        if(len>200){
            layer.msg('备注信息100字以内',{icon:2});
            return false;
        }
        if(!remark){
            layer.msg('请填写备注细信息',{icon:2});
            return false;
        }
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['remark'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'remark':remark,'id':id},
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
                layer.msg('操作失败！',{icon:7});
            }
        });
    })

</script>