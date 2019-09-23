<?php

use yii\helpers\Html;
use cms\modules\shop\models\BuildingShopFloor;
use cms\modules\shop\models\BuildingCompany;
/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\BuildingShopFloor */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '楼宇LED详情', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?$buildingCompanyModel = BuildingCompany::findOne(['id'=>$model->company_id])?>
<div class="building-shop-floor-view">
    <h4 style="font-weight: bold;">楼宇基本信息(LED)</h4>
    <table class="table table-striped table-bordered">
        <tr>
            <td>楼宇名称</td>
            <td><?=Html::encode($model->shop_name)?></td>
            <td>楼宇ID</td>
            <td><?=Html::encode($model->id)?></td>
            <td>楼宇类型</td>
            <td>
                <?if($model->floor_type ==1):?>
                    写字楼
                <?elseif ($model->floor_type == 2):?>
                    商住两用
                <?endif;?>
            </td>
        </tr>
        <tr>
            <td>楼宇等级</td>
            <td><?=Html::encode($model->shop_level)?></td>
            <td>地址</td>
            <td><?=Html::encode($model->address)?></td>
            <td>层数</td>
            <td><?=Html::encode($model->floor_number)?></td>
        </tr>
        <tr>
            <td>地下层数</td>
            <td><?=Html::encode($model->low_floor_number)?></td>
            <td>联系人</td>
            <td><?=Html::encode($model->contact_name)?></td>
            <td>联系电话</td>
            <td><?=Html::encode($model->contact_mobile)?></td>
        </tr>
        <tr>
            <td>LED数量</td>
            <td><?=Html::encode($model->led_screen_number)?></td>
            <td>设备开机时间</td>
            <td><?=Html::encode($model->screen_start_at)?></td>
            <td>申请时间</td>
            <td><?=Html::encode($model->led_create_at)?></td>
        </tr>
        <tr>
            <td>审核通过时间</td>
            <td><?=Html::encode($model->led_examine_at)?></td>
            <td>申请状态</td>
            <td><?=Html::encode(BuildingShopFloor::getStatusfloor($model->led_examine_status))?></td>
            <td>安装完成时间</td>
            <td><?=Html::encode($model->led_install_finish_at)?></td>
        </tr>
        <tr>
            <td>买断费用</td>
            <td colspan="5"><?=Html::encode($model->led_install_price)?></td>
        </tr>
        <tr>
            <td>公司名称</td>
            <td><?=html::encode($buildingCompanyModel->company_name)?></td>
            <td>公司地址</td>
            <td><?=html::encode($buildingCompanyModel->address)?></td>
            <td>统一社会信用码</td>
            <td><?=html::encode($buildingCompanyModel->registration_mark)?></td>
        </tr>
        <tr>
            <td>申请人</td>
            <td><?=html::encode($buildingCompanyModel->apply_name)?></td>
            <td>申请电话</td>
            <td><?=html::encode($buildingCompanyModel->apply_mobile)?></td>
            <td>业务员姓名</td>
            <td><?=html::encode($buildingCompanyModel->member_name)?></td>
        </tr>
        <tr>
            <td>联系方式</td>
            <td><?=html::encode($buildingCompanyModel->member_mobile)?></td>
            <td>合同附件</td>
            <td>预览 下载</td>
            <td>安装信息</td>
            <td><a href="<?=\yii\helpers\Url::to(['/shop/building-shop-floor/install-led-view','id'=>$buildingCompanyModel->id])?>">安装详情</a></td>
        </tr>
    </table>
    <h4 style="font-weight: bold;">物业照片信息</h4>
    <table class="table table-hover">
        <tr>
            <td>营业执照信息</td>
        </tr>
        <tr>
            <td>
                <?php if($buildingCompanyModel->business_licence):?>
                    <img width="150" height="auto" src="<?=Html::encode($buildingCompanyModel->business_licence)?>" title="营业执照图片" alt="">
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <td>其他</td>
        </tr>
        <tr>
            <td>
                <?if($buildingCompanyModel->other_image):?>
                    <?foreach (explode(',',$buildingCompanyModel->other_image) as $v):?>
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($v)?>" alt="">
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
    </table>
    <h4 style="font-weight: bold;">楼宇照片信息</h4>
    <table class="table table-hover">
        <tr>
            <td>楼宇外观照</td>
            <td>平面结构图</td>
            <td>楼宇层数图</td>
        </tr>
        <tr>
            <td>
                <?php if($model->shop_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->shop_image)?>" title="楼宇外观照" alt="">
                <?php endif;?>
            </td>
            <td>
                <?php if($model->plan_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->plan_image)?>" title="平面结构图" alt="">
                <?php endif;?>
            </td>
            <td>
                <?php if($model->floor_image):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->floor_image)?>" title="楼宇层数图" alt="">
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <td>其他</td>
        </tr>
        <tr>
            <td>
                <?if($model->other_image):?>
                    <?foreach (explode(',',$model->other_image) as $vv):?>
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vv)?>" alt="">
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
        <?if(!empty($desc)):?>
            <tr><th colspan="6" style="font-size: 18px;"><b>审核信息</b></th></tr>
            <?php foreach ($desc as $v):?>
                <tr>
                    <td colspan="6">
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
        <? if($model->led_examine_status == 0):?>
            <? if($model->led_last_examine_user_id != Yii::$app->user->identity->getId()):?>
                <div class="row one">
                    <button type="button" class="btn btn-primary ck" data-type="pass">审核通过</button>
                    <button type="button" class="btn btn-danger ck" data-type="rebut">驳回</button>
                </div>
            <?else:?>
                <div class="row two">
                    <button type="button" class="btn btn-primary firm" data-type="close">确定</button>
                </div>
            <?endif;?>
        <? else:?>
            <div class="row two">
                <button type="button" class="btn btn-primary firm" data-type="close">确定</button>
            </div>
        <? endif;?>
    </div>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/jquery.media.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.panel-body').viewer({
        url: 'src',
    });
    //审核通过
    $('.ck').bind('click',function () {
        var type = $(this).attr('data-type');
        var shop_id = $(this).parents('.text-center').attr('shop_id');
        var device_type = 'led';
        if(type == 'pass'){
            layer.confirm('您确定要审核通过吗？', {
                btn: ['通过','取消'] //按钮
            }, function(){
                $.ajax({
                    url : '<?=\yii\helpers\Url::to(['floor-examine'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'type':type, 'shop_id': shop_id, 'device_type': device_type},
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
                content: '<div class="row scroll form-horizontal"  style="margin-top: 15px;"><label class="col-sm-3 control-label" for="formGroupInputLarge">驳回原因：</label><div class="col-sm-6"><select name="reason" id="" class="form-control"><option value="3">其他</option><option value="1">商家信息有误</option><option value="2">地址有误</option></select></div></div><div class="row scroll form-horizontal txa" style="margin-top: 15px;"><label for="" class="col-sm-3 control-label">其他原因：</label><div class="col-sm-6"><textarea class="form-control" rows="3"></textarea></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
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
                    url : '<?=\yii\helpers\Url::to(['floor-examine'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'type':type, 'shop_id': shop_id, 'device_type': device_type, 'desc':desc},
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
</script>
