<?php

use yii\helpers\Html;
use cms\modules\shop\models\ShopApply;
use cms\modules\member\models\Member;
use cms\models\LogExamine;
use yii\bootstrap\ActiveForm;
use cms\modules\examine\models\ShopLogistics;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\Shop;
use yii\helpers\Url;
use common\libs\ToolsClass;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '商家信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    tr td:nth-child(odd){
        font-weight: 700;
    }
    tr td{
        width:25%;
    }
</style>
<div class="shop-view">
    <?$screen = Screen::getScreenInfo($model->id);?>
    <?$adminman = LogExamine::getShopCheckMan($model->id,1);?>
    <?$wuliu = ShopLogistics::getwuliuInfo($model->id);?>
    <?$shopA = ShopApply::getShopApplyInfo($model->id)?>
    <?$parent = Member::findOne(['id'=>$model->parent_member_id]);?>
    <table class="table table-hover">
        <h4><b>商家信息</b></h4>
        <tr>
            <td>店铺编号：</td>
            <td><?=Html::encode($model->id)?></td>
        </tr>
        <tr>
            <td>公司名称：</td>
            <td><?=Html::encode($shopA['company_name'])?></td>
            <td>商家名称：</td>
            <td><?=Html::encode($model->name)?></td>
        </tr>
        <tr>
            <td>统一社会信用代码：</td>
            <td><?=Html::encode($shopA['registration_mark'])?></td>
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
            <td>
                <span ><?=Html::encode($model->mirror_account)?></span>
            </td>
            <td>店铺面积：</td>
            <td><?=Html::encode($model->acreage)?> (平方米)</td>
        </tr>
        <tr>
            <td>申请的屏幕数量：</td>
            <td>
                <span id="fact"><?=Html::encode($model->apply_screen_number)?></span>
                <? if($model->status == 0 ||$model->status==2):?>
                    <span id="modify"  style="padding-left: 15px;color: darkgreen;cursor: pointer;">修改</span>
                <?endif;?>
            </td>
            <td>实际屏幕数量：</td>
            <td>
                <?=Html::encode($model->screen_number)?>
                <? if($model->status>2):?>
                    <a class="detail chakan" data-type="pass" shop_id="<?=Html::encode($model->id)?>">查看</a>
                <?endif;?>
            </td>
        </tr>
        <tr>
            <td>法人代表：</td>
            <td><?=Html::encode($shopA['apply_name'])?></td>
            <td>法人电话：</td>
            <td><?=Html::encode($shopA['apply_mobile'])?></td>
        </tr>
        <tr>
            <td>店铺联系人姓名：</td>
            <td><?=Html::encode($shopA['contacts_name'])?></td>
            <td>店铺联系人电话：</td>
            <td><?=Html::encode($shopA['contacts_mobile'])?></td>
        </tr>
        <tr>
            <td>业务员姓名：</td>
            <td><?=Html::encode($model->member_name)?></td>
            <td>业务员电话：</td>
            <td><?=Html::encode($model->member_mobile)?></td>
        </tr>
        <tr>
            <td>上级业务员姓名：</td>
            <td>
                <? if($model->parent_member_id > 0):?>
                    <?=Html::encode($parent->name)?>
<!--                    <a href="--><?//=\yii\helpers\Url::to(['/member/member/view','id'=>$model->parent_member_id])?><!--" style="padding-left: 15px;color: darkgreen;cursor: pointer;text-decoration: none;">查看</a>-->
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
            <td>独家买断费用：</td>
            <td><?=Html::encode(ToolsClass::priceConvert($shopA['apply_brokerage']))?> 元</td>
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
            <td><?=Html::encode(Shop::getStatusByNum($model->status))?></td>
        </tr>
        <tr>
            <td>申请编号：</td>
            <td><?=Html::encode($shopA['apply_code'])?></td>
            <td>动态码：</td>
            <td><?=Html::encode($shopA['dynamic_code'])?></td>
        </tr>
        <th>法人证件信息</th>
        <th></th>
        <th>联系人证件信息</th>
        <th></th>
        <tr>
            <td style="text-align: center">
                <?php if($shopA['identity_card_front']):?>
                    <img width="150" height="auto" src="<?=Html::encode($shopA['identity_card_front'])?>" title="身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($shopA['identity_card_back']):?>
                    <img width="150" height="auto" src="<?=Html::encode($shopA['identity_card_back'])?>" title="身份证背面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($shopA['agent_identity_card_front']):?>
                    <img width="150" height="auto" src="<?=Html::encode($shopA['agent_identity_card_front'])?>" title="联系人身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($shopA['agent_identity_card_back']):?>
                    <img width="150" height="auto" src="<?=Html::encode($shopA['agent_identity_card_back'])?>" title="联系人身份证背面照" alt="">
                <?php endif;?>
            </td>
        </tr>
        <th>营业执照：</th>
        <th>店面门面：</th>
        <th>店铺全景：</th>
        <th></th>
        <tr>
            <td style="text-align: center">
                <?php if($shopA['business_licence']):?>
                <img width="150" height="auto" src="<?=Html::encode($shopA['business_licence'])?>" title="营业执照" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($model->shop_image):?>
                <img width="150" height="auto" src="<?=Html::encode($model->shop_image)?>" title="店面门面" alt="">
                <?php endif;?>
            </td>
            <td style="text-align: center">
                <?php if($shopA['panorama_image']):?>
                <img width="150" height="auto" src="<?=Html::encode($shopA['panorama_image'])?>" title="店铺全景" alt="">
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
                    <?if($shopA['authorize_image']):?>
                        <?foreach (explode(',',$shopA['authorize_image']) as $v):?>
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
                <?if($shopA['other_image']):?>
                    <?foreach (explode(',',$shopA['other_image']) as $vv):?>
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vv)?>" title="授权证明" alt="">
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
        <tr><th colspan="4" style="font-size: 18px;"><b>店铺审核</b></th></tr>
        <tr>
            <td>审核人：<?=Html::encode($adminman['create_user_name']) ?></td>
            <td>审核时间：<?=Html::encode($adminman['create_at']) ?></td>
            <td></td>
            <td></td>
        </tr>
    <? if($model->status==2 && $model->delivery_status==1): ?><!--  待配货 -->
<!--        --><?php //$form = ActiveForm::begin([
//            'action'=>['stock-removal'],
//            'method' =>'post',
//        ]); ?>
<!--        <input type="hidden" name="shopid" value="--><?//=Html::encode($model->id)?><!--" />-->
<!--        <tr><th colspan="4" style="font-size: 18px;"><b>安装详情</b></th></tr>-->
<!--        --><?// for($i=1;$i<=$model->screen_number;$i++): ?>
<!--        --><?// if($i%2!=0): ?><!--<tr>--><?// endif;?>
<!--            <td>设备编号：--><?//= $form->field($scmodel, 'number[]')->textInput()->label(false) ?><!--</td>-->
<!--        --><?// if($i%2==0): ?><!--</tr>--><?// elseif($i == $model->screen_number): ?><!--</tr>--><?// endif;?>
<!--        --><?// endfor;?>
<!--        <th><div class="row text-center" shop_id="--><?//=Html::encode($model->id)?><!--">-->
<!--            <button type="button" class="btn btn-primary ck" data-type="pass" id="peihuotijiao">提交</button>-->
<!--            <a href="javascript:void(0);" class="btn btn-primary fanhui">返回</a>-->
<!--        </div></th>-->
<!--        --><?php //ActiveForm::end(); ?>
    <? elseif($model->status==2 && $model->delivery_status==2): ?><!--  待发货 -->
        <?php $form = ActiveForm::begin([
            'action'=>['addscreen'],
            'method' =>'post',
        ]); ?>
            <input type="hidden" name="shopid" value="<?=Html::encode($model->id)?>" />
            <tr><th colspan="4" style="font-size: 18px;"><b>物流信息</b></th></tr>
            <tr>
                <td>物流名称：<!--</td><td>--><?= $form->field($wlmodel, 'name')->dropDownList(ShopLogistics::getLogistList('all'),['class'=>'form-control','prompt'=>'请选择'])->label(false) ?></td>
                <td>订单编号：<!--</td><td>--><?= $form->field($wlmodel, 'logistics_id')->textInput()->label(false) ?></td>
            </tr>
             <th><div class="row text-center" shop_id="<?=Html::encode($model->id)?>">
                <button type="button" class="btn btn-primary ck" data-type="pass" id="fahuotijiao">提交</button>
                <a href="<?=Url::to(['/examine/install/index'])?>" class="btn btn-primary ck">返回</a>
            </div></th>
        <?php ActiveForm::end(); ?>
    <? elseif($model->status==2 && $model->delivery_status==3): ?><!-- 待安装 -->
        <tr><th colspan="4" style="font-size: 18px;"><b>物流信息</b></th></tr>
        <? foreach($wuliu as $kwl=>$vwl):?>
        <tr>
            <td>物流名称：<?=Html::encode(ShopLogistics::getLogistList('',$vwl['name']))?></td>
            <td>快递单号：<?=Html::encode($vwl['logistics_id'])?></td>
            <? if($kwl==0):?><td><a class="detail tianjia" data-type="pass" shop_id="<?=Html::encode($model->id)?>">添加</a></td><?endif; ?>
        </tr>
        <? endforeach; ?>
        <th><div class="row text-center" shop_id="<?=Html::encode($model->id)?>">
            <a href="javascript:void(0);" class="btn btn-primary fanhui">返回</a>
        </div></th>
    <? elseif($model->status==3): ?><!-- 安装待确认 -->
        <tr><th colspan="4" style="font-size: 18px;"><b>安装位置</b></th></tr>
        <? foreach($screen as $skey=>$svalue): ?>
            <? if($skey == 0):?><tr><?endif;?>
            <td>
                <img width="150" height="auto" src="<?=Html::encode($svalue['image'])?>" alt="图片存在"/>
            </td>
            <?if(($skey+1)%4==0):?></tr><tr><?elseif($skey ==(count($screen)-1)):?></tr><?endif;?>
        <?endforeach; ?>


        <tr><th colspan="4" style="font-size: 18px;"><b>物流信息</b></th></tr>
        <? foreach($wuliu as $kwl=>$vwl):?>
        <tr>
            <td>物流名称：<?=Html::encode(ShopLogistics::getLogistList('',$vwl['name']))?></td>
            <td>快递单号：<?=Html::encode($vwl['logistics_id'])?></td>
            <td></td>
            <td></td>
        </tr>
        <? endforeach; ?>
        <tr><th colspan="4" style="font-size: 18px;"><b>安装详情</b></th></tr>
        <tr>
            <td>安装人：<?=Html::encode($model->install_member_name)?></td>
            <td>安装电话：<?=Html::encode($model->install_mobile)?></td>
            <td></td>
            <td></td>
        </tr>
        <? for($i=1;$i<=count($screen);$i++): ?>
            <tr>
                <td>设备编号：<?=Html::encode($screen[$i-1]['number'])?>
                    <?if($screen[$i-1]['status']!=1):?>
                    (<span style="color:red;"><?=Html::encode($screen[$i-1]['statuslist'])?></span>)
                    <?endif;?>
                </td>
                <td><?=Html::encode($screen[$i-1]['remark'])?></td>
                <td colspan="2">安装地址：<span class="screenadd" sid="<?=Html::encode($screen[$i-1]['software_number'])?>"></span></td>
            </tr>
        <? endfor;?>
        <?php $form = ActiveForm::begin([
            'action'=>['anzhuang'],
            'method' =>'post',
        ]); ?>
        <input type="hidden" name="shopid" value="<?=Html::encode($model->id)?>" />
        <input type="hidden" name="line" value="<?=Html::encode($model->install_status)?>" />
        <th colspan="4"><div class="row text-center anzhuang" shop_id="<?=Html::encode($model->id)?>">
        <? if($model->last_examine_user_id != Yii::$app->user->identity->getId()):?>
            <div class="row one">
                <button type="submin" class="btn" data-type="pass" >确认安装</button>
                <a class="btn btn-danger ck" data-type="bohui" id="bh" sid="<?=Html::encode($model->id)?>">驳回</a>
            </div>
        <?else:?>
            <div class="row two">
                <a href="javascript:void(0);" class="btn btn-primary fanhui">返回</a>
            </div>
        <?endif;?>
        </div></th>
        <?php ActiveForm::end(); ?>
    <? elseif($model->status==4): ?><!-- 安装未通过 -->
        <tr><th colspan="4" style="font-size: 18px;"><b>安装位置</b></th></tr>
        <? foreach($screen as $skey=>$svalue): ?>
            <? if($skey == 0):?><tr><?endif;?>
            <td>
                <img width="150" height="auto" src="<?=Html::encode($svalue['image'])?>" alt="图片存在"/>
            </td>
            <?if(($skey+1)%4==0):?></tr><tr><?elseif($skey ==(count($screen)-1)):?></tr><?endif;?>
        <?endforeach; ?>
        <tr><th colspan="4" style="font-size: 18px;"><b>物流信息</b></th></tr>
        <? foreach($wuliu as $kwl=>$vwl):?>
        <tr>
            <td>物流名称：<?=Html::encode(ShopLogistics::getLogistList('',$vwl['name']))?></td>
            <td>快递单号：<?=Html::encode($vwl['logistics_id'])?></td>
            <td></td>
            <td></td>
        </tr>
        <? endforeach; ?>
        <? for($i=1;$i<=count($screen);$i++): ?>
            <? if($i%2!=0): ?><tr><? endif;?>
            <td>设备编号：<?=Html::encode($screen[$i-1]['number'])?></td>
            <td><?=Html::encode($screen[$i-1]['remark'])?></td>
            <? if($i%2==0): ?></tr><? elseif($i == count($screen)): ?></tr><? endif;?>
        <? endfor;?>
        <tr><th colspan="4" style="font-size: 18px;"><b>安装详情</b></th></tr>
        <tr>
            <td>安装人：<?=Html::encode($model->install_member_name)?></td>
            <td>安装电话：<?=Html::encode($model->install_mobile)?></td>
            <td></td>
            <td></td>
        </tr>
        <? for($i=1;$i<=count($screen);$i++): ?>
            <tr>
                <td>设备编号：<?=Html::encode($screen[$i-1]['number'])?> </td>
                <td><?=Html::encode($screen[$i-1]['remark'])?></td>
                <td colspan="2">安装地址：<span class="screenadd" sid="<?=Html::encode($screen[$i-1]['software_number'])?>"></span></td>
            </tr>
        <? endfor;?>
            <tr><th colspan="4" style="font-size: 18px;"><b>审核信息</b></th></tr>
            <?foreach ($desc as $v):?>
                <tr>
                    <td colspan="3">
                        时间：<?=Html::encode($v['create_at']?$v['create_at']:'')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        操作人：<?=Html::encode($v['create_user_name']?$v['create_user_name']:'')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?if($v['examine_result']==2):?>
                            结果：<?=Html::encode($v['examine_desc'])?>
                        <?elseif($v['examine_result']==1):?>
                            结果：审核通过
                        <?endif;?>
                    </td>
                </tr>
            <?endforeach;?>
        <th colspan="4"><div class="row text-center anzhuang" shop_id="<?=Html::encode($model->id)?>">
            <a href="javascript:void(0);" class="btn btn-primary fanhui">返回</a>
        </div></th>
    <?endif;?>
    </table>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.shop-view').viewer({
        url: 'src',
    });
    //点击查看详情
    $('.chakan').click(function () {
        var shop_id = $(this).attr('shop_id');
        layer.open({
            type: 2,
            title: '查看',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=Url::to(['/examine/install/shop-screen'])?>&shop_id='+shop_id
        });
    })
    //添加物流信息
    $('.tianjia').click(function () {
        var shop_id = $(this).attr('shop_id');
        layer.open({
            type: 2,
            title: '添加物流信息',
            shadeClose: true,
            shade: 0.8,
            area: ['35%', '40%'],
            content: '<?=Url::to(['/examine/install/wuliu'])?>&shop_id='+shop_id
        });
    })

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
//            var screenid ='440050374818340f014f,440050374818340f014f';
            var screenid =arrid.join(',');
            var baseApiUrl = "<?=\Yii::$app->params['pushProgram']?>";
            var emptyadd = 0;
            $.ajax({
                url:baseApiUrl+'/front/device/selectLocation/'+screenid,
                type : 'GET',
                dataType : 'json',
//                data : {'number':screenid},
                success:function (resdata) {;
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
//                    if(emptyadd == 0 && arrid.length == resdata.data.length){
//                        $('.anzhuang button').addClass('btn-primary');
//                        $('.anzhuang button').attr('type','submit');
//                        html = '<button type="submit" class="btn btn-primary" data-type="pass" id="anzhuang">确认安装</button>';
//                        $('.anzhuang').prepend(html);
//                    }
                },error:function (error) {
                    layer.msg('屏幕地址获取失败！');
                }
            });
        }

    })
    $("#bh").click(function () {
        var id = $(this).attr('sid');
        pg = layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['450px', '320px'], //宽高
            shadeClose: true,
            content: '<div class="row scroll form-horizontal txa" style="margin-top: 15px;"><label for="" class="col-sm-3 control-label">驳回原因：</label><div class="col-sm-6"><textarea class="form-control" rows="3"></textarea></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
        });
        $('.confirm').bind('click',function () {
            var data = $('.txa textarea').val();
            if(!data){
                layer.msg('请填写驳回原因！',{icon:2});
                return false;
            }
            $.ajax({
                url : '<?=\yii\helpers\Url::to(['reject'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'data':data, 'id': id},
                success:function (data) {
                    if(data.code == 1){
                        layer.msg(data.msg,{icon:1});
                        layer.closeAll('page');
                        history.back();
                    }else{
                        layer.msg(data.msg);
                        layer.closeAll('page');
                        history.back();
                    }
                },
                error:function () {
                    layer.msg('操作失败！');
                }
            });
        })

    })
    //编辑设备编号
    $('.bianji').click(function () {
        var shop_id = $(this).attr('shop_id');
        layer.open({
            type: 2,
            title: '编辑设备编号',
            shadeClose: true,
            shade: 0.8,
            area: ['40%', '70%'],
            content: '<?=Url::to(['/examine/install/shebei'])?>&shop_id='+shop_id
        });
    })
    ////配货
    ///*$('#peihuotijiao').click(function(){
    //    var $inputArr = $('[name="Screen[number][]"]');
    //    var screenid = [];
    //    $inputArr.each(function(){
    //        screenid.push($(this).val());
    //    });
    //    var nary=screenid.sort();
    //    for(var i=0;i<screenid.length;i++){
    //        if(nary[i]==null || nary[i]==undefined || nary[i]==""){
    //            layer.alert("设备编号不能为空！");
    //            return false;
    //        }
    //        if (nary[i]==nary[i+1]){
    //            layer.alert("设备编号相同，请勿重复填写！");
    //            return false;
    //        }
    //    }
    //    $.ajax({
    //        url:'<?///*=Url::to(['check-screenid'])*/?>//',
    //        type : 'POST',
    //        dataType : 'json',
    //        data : {'number':screenid},
    //        success:function (resdata) {
    //            if(resdata ==1){
    //                $('#w0').submit();
    //            }else if(resdata.errorid ==2){
    //                layer.alert('设备编号：'+resdata.number+'已发货，请勿重复提交！');
    //            }else if(resdata.errorid ==3){
    //                layer.alert('设备编号：'+resdata.number+'不在设备库内，请确认！');
    //            }else if(resdata.errorid ==4){
    //                layer.alert('设备编号：'+resdata.number+'还未出库，请确认！');
    //            }
    //            return false;
    //        },error:function (error) {
    //            layer.msg('操作失败！');
    //        }
    //    });
    //})*/
    //发货
    $('#fahuotijiao').click(function(){
        var wlname = $('#shoplogistics-name').val();
        var wlid = $('#shoplogistics-logistics_id').val();
        // /*var shop_id = $('input[name="shopid"]').val();
        // var $inputArr = $('[name="Screen[number][]"]');
        // var screenid = [];*/
        if( wlname=="" || wlid ==""){
            layer.alert("请填写物流信息！");
            return false;
        }
        $('#w0').submit();
        ///*$inputArr.each(function(){
        //    screenid.push($(this).val());
        //});
        //var nary=screenid.sort();
        //for(var i=0;i<screenid.length;i++){
        //    if(nary[i]==null || nary[i]==undefined || nary[i]==""){
        //        layer.alert("请填写设备编号！");
        //        return false;
        //    }
        //    if (nary[i]==nary[i+1]){
        //        layer.alert("设备编号相同，请勿重复填写！");
        //        return false;
        //    }
        //}
        //$.ajax({
        //    url:'<?//=Url::to(['check-right-id'])?>//',
        //    type : 'POST',
        //    dataType : 'json',
        //    data : {'number':screenid,'shopid':shop_id},
        //    success:function (resdata) {
        //        if(resdata ==1){
        //            $('#w0').submit();
        //        }else if(resdata ==2){
        //            layer.alert('发货数量与配货数量不一致，请确认！');
        //        }else if(resdata.errorid ==3){
        //            layer.alert('发货设备编号：'+resdata.number+'不在配货设备库之内！');
        //        }
        //        return false;
        //    },error:function (error) {
        //        layer.msg('操作失败！');
        //    }
        //});*/
    })
    $('#modify').bind('click',function () {
        if($('.inp').size() == 0){
          //  $('.fact').hide();
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
                        layer.msg('修改成功',{icon:1});
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg('修改失败！');
                    }
                },error:function () {
                    layer.msg('修改失败！');
                }
            });
            $(this).html('修改');
            $(this).prev().remove();
            $('#fact').show();
        }
    })
    function isInteger(x) {
        return x % 1 === 0;
    }
    $('.fanhui').on('click',function(){
        history.back();
    })
</script>
