<?php

use yii\helpers\Html;
use cms\modules\screen\models\Screen;
use cms\modules\member\models\Member;
use cms\modules\examine\models\ShopScreenReplace;

$this->title = '换屏审核';
$this->params['breadcrumbs'][] = ['label' => '换屏审核', 'url' => ['res-examine']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-screen-replace-view">
    <?$parent = Member::findOne(['id'=>$shopModel->parent_member_id]);?>
    <table class="table table-hover">
        <th style="border-top: none;font-size: 18px;"><b>商家信息：</b></th>
        <th style="border-top: none"></th>
        <th style="border-top: none"></th>
        <th style="border-top: none"></th>
        <tr>
            <td>店铺编号：</td>
            <td class="shopid"><?=Html::encode($shopModel->id)?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>商家名称：</td>
            <td><?=Html::encode($shopModel->name)?></td>
            <td>公司名称：</td>
            <td><?=Html::encode($shopappModel->company_name)?></td>
        </tr>
        <tr>
            <td>所属地区：</td>
            <td><?=Html::encode($shopModel->area_name)?></td>
            <td>详细地址：</td>
            <td><?=Html::encode($shopModel->address)?></td>
        </tr>
        <tr>
            <td>屏幕数量：</td>
            <td><?=Html::encode($shopModel->screen_number)?></td>
            <td>镜面数量：</td>
            <td><?=Html::encode($shopModel->mirror_account)?></td>
        </tr>
        <tr>
            <td>店铺面积：</td>
            <td><?=Html::encode($shopModel->acreage)?> (平方米)</td>
            <td>申请时间：</td>
            <td><?=Html::encode($shopModel->create_at)?></td>
        </tr>
        <tr>
            <td>法人代表：</td>
            <td><?=Html::encode($shopappModel->apply_name)?></td>
            <td>法人电话：</td>
            <td><?=Html::encode($shopappModel->apply_mobile)?></td>
        </tr>
        <tr>
            <td>业务员姓名：</td>
            <td><?=Html::encode($shopModel->member_name)?></td>
            <td>业务员电话：</td>
            <td><?=Html::encode($shopModel->member_mobile)?></td>
        </tr>
        <tr>
            <td>上级业务员姓名：</td>
            <td><?=Html::encode($parent?$parent->name:'---')?></td>
            <td>上级业务员电话：</td>
            <td><?=Html::encode($parent?$parent->mobile:'---')?></td>
        </tr>
        <tr>
            <td>店铺联系人姓名：</td>
            <td><?=Html::encode($shopappModel->contacts_name)?></td>
            <td>店铺联系人电话：</td>
            <td><?=Html::encode($shopappModel->contacts_mobile)?></td>
        </tr>

        <tr><th colspan="4" style="font-size: 18px;"><b>安装位置：</b></th></tr>
        <? foreach($screenModel as $skey=>$svalue): ?>
            <? if($skey == 0):?><tr><?endif;?>
            <td>
                <img width="150" height="auto" src="<?=Html::encode($svalue->image)?>" alt="图片存在"/>
            </td>
            <?if(($skey+1)%4==0):?></tr><tr><?elseif($skey ==(count($screenModel)-1)):?></tr><?endif;?>
        <?endforeach; ?>
        <tr><th colspan="4" style="font-size: 18px;"><b>维护详情：</b></th></tr>
        <tr><td colspan="4">
            <table class="table table-hover">
                <tr>
                    <th>维护类型</th>
                    <th>申请人</th>
                    <th>申请时间</th>
                    <th>屏幕数</th>
                    <th>维护人</th>
                    <th>安装屏幕编号</th>
                    <th>拆除屏幕编号</th>
                    <th>备注</th>
                </tr>
                <tr>
                    <td style="width: 80px;"><?=Html::encode(ShopScreenReplace::getMaintainType($resModel->maintain_type))?></td>
                    <td style="width: 100px;"><?=Html::encode($resModel->create_user_name)?></td>
                    <td style="width: 100px;"><?=Html::encode($resModel->create_at)?></td>
                    <td style="width: 70px;"><?=Html::encode($resModel->replace_screen_number)?></td>
                    <td style="width: 80px;"><?=Html::encode($resModel->install_member_name)?></td>
                    <td><?=Html::encode($resModel->install_device_number)?></td>
                    <td><?=Html::encode($resModel->remove_device_number)?></td>
                    <td><?=Html::encode($resModel->description)?></td>
                </tr>
            </table>
        </td></tr>
        <?if($resModel->maintain_type != 3):?>
            <?if(!empty($resModel->install_device_number)):?>
            <? foreach(explode(',',$resModel->install_device_number) as $skey=>$svalue): ?>
                <tr>
                    <td>设备编号：<?=Html::encode($svalue)?> </td>
                    <td><?=Html::encode($resModel->screen_status == 1?'已激活':'未激活')?> </td>
                    <td class="screenaddr" sid="<?=Html::encode(explode(',',$resModel->install_software_number)[$skey])?>" colspan='2'></td>
<!--                    <td class="screenaddr" ></td>-->
                </tr>
            <?endforeach; ?>
            <?endif;?>
        <?endif;?>
        <tr><th colspan="4" style="font-size: 18px;"><b>问题描述：</b></th></tr>
        <tr>
            <td colspan="4">
                <?=Html::encode($resModel->problem_description)?>
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
                            结果：<?=Html::encode('已驳回！ 驳回原因：'.$v['examine_desc'])?>
                        <? elseif ($v['examine_result']==1):?>
                            结果：已通过审核
                        <? endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
        <?endif;?>
        <tr><td colspan="4" style="text-align: center;">
            <div class="row one">
                <? if($resModel->status == 2):?>
                    <button type="button" class="btn btn-primary ck" data-type="pass" res="<?=Html::encode($resModel->id)?>">审核通过</button>
                    <?if($resModel->maintain_type != 3):?>
                    <button type="button" class="btn btn-danger ck" data-type="rebut" res="<?=Html::encode($resModel->id)?>">驳回</button>
                    <?endif;?>
                <?else:?>
                    <button type="button" class="btn btn-primary" data-type="close">确定</button>
                <?endif;?>
            </td></div>
        </tr>
    </table>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.shop-screen-replace-view').viewer({
        url: 'src',
    });
    //获取屏幕地址
    $(function(){
        var arrid = [];
        $('.screenaddr').each(function(){
            var sid = $(this).attr('sid');
            if(sid!=''){
                arrid.push(sid);
            }
        });

        if(arrid.length!=0){
//            var screenid ='3400503685246419064e,34105036c824382a084e';
            var screenid =arrid.join(',');
            var baseApiUrl = "<?=\Yii::$app->params['pushProgram']?>";
            var emptyadd = 0;
            $.ajax({
                url:baseApiUrl+'/front/device/selectLocation/'+screenid,
                type : 'GET',
                dataType : 'json',
                success:function (resdata) {;
                    console.log(resdata);
                    if(resdata.code==0){
                        resdata.data.forEach(function(val,index){
                            var add = val.location;
                            if(add!=null){
                                $('.screenaddr').eq(index).html("安装地址："+add.address);
                            }else{
                                emptyadd +=1;
                                $('.screenaddr').eq(index).html("安装地址：---");
                            }
                        })
                    }
                },error:function (error) {
                    layer.msg('屏幕地址获取失败！');
                }
            });
        }
    })
    //审核不通过详情页点击确定
    $('.firm').bind('click',function () {
        history.back();
    })
    //审核通过
    $('.ck').bind('click',function () {
        var type = $(this).attr('data-type');
        var resid = $(this).attr('res');
        if(type == 'pass'){
            layer.confirm('您确定要审核通过吗？', {
                btn: ['通过','取消'] //按钮
            }, function(){
                $.ajax({
                    url : '<?=\yii\helpers\Url::to(['re-examine'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'type':type, 'resid': resid},
                    success:function (phpdata) {
                        if(phpdata.code == 3){
                            layer.msg(phpdata.msg);
                            return false;
                        }
                        if(phpdata.code==1){
                            layer.msg(phpdata.msg);
                            setTimeout(function(){
                                window.parent.location.reload();
                            },2000);
                        }else if(phpdata.code ==2){
                            layer.msg(phpdata.msg);
                            setTimeout(function(){
                                window.parent.location.reload();
                            },2000);
                        }
                    },
                    error:function () {
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
                area: ['450px', '320px'], //宽高
                shadeClose: true,
                content: '<div class="row scroll form-horizontal txa" style="margin-top: 15px;"><label for="" class="col-sm-3 control-label">驳回原因：</label><div class="col-sm-6"><textarea class="form-control" rows="3"></textarea></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
            });
            //驳回
            $('.confirm').bind('click',function () {
                var desc = $('.txa textarea').val();
                if(!desc){
                    layer.msg('请填写驳回原因！',{icon:2});
                    return false;
                }
                $.ajax({
                    url : '<?=\yii\helpers\Url::to(['re-examine'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'desc':desc, 'resid': resid,'type':type},
                    success:function (res) {
                        if(res.code == 1){
                            layer.msg(res.msg,{icon:1});
                            layer.closeAll('page');
                            setTimeout(function(){
                                window.parent.location.reload();
                            },2000);
                        }else{
                            layer.msg(res.msg);
                            layer.closeAll('page');
                            setTimeout(function(){
                                window.parent.location.reload();
                            },2000);
                        }
                    },
                    error:function () {
                        layer.msg('操作失败！');
                    }
                });
            })
        }
    })
</script>