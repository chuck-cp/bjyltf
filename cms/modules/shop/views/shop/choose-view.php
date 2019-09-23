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
$this->title = $model->shop_id;
$this->params['breadcrumbs'][] = ['label' => '商家信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?$LogExamine = LogExamine::getLogExamin($model->id,8);?>

<style type="text/css">
    table th:nth-child(odd){
        font-weight: 700;
    }
    table td{word-break: break-word}
</style>

<div class="shop-view" style="width: 100%;">
    <table class="table table-hover">
        <tr><th colspan="5" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;"><b>变更详情： <?=Html::encode($model->shop_id)?></b></th></tr>
        <tr>
            <td>变更法人：</td>
            <td><?=Html::encode($model->apply_name)?></td>
            <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
            <td><?=Html::encode($model->update_apply_name)?></td>
            <td></td>
        </tr>
        <tr>
            <td>变更法人电话：</td>
            <td><?=Html::encode($model->apply_mobile)?></td>
            <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
            <td><?=Html::encode($model->update_apply_mobile)?></td>
            <td></td>
        </tr>
        <tr>
            <td>变更法人身份证号：</td>
            <td><?=Html::encode($model->identity_card_num)?></td>
            <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
            <td><?=Html::encode($model->update_identity_card_num)?></td>
            <td></td>
        </tr>
        <?if($model->update_shop_name):?>
            <tr>
                <td>变更店铺名称：</td>
                <td><?=Html::encode($model->shop_name)?></td>
                <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
                <td><?=Html::encode($model->update_shop_name)?></td>
                <td></td>
            </tr>
        <?endif;?>

        <?if($model->update_company_name):?>
            <tr>
                <td>变更公司名称：</td>
                <td><?=Html::encode($model->company_name)?></td>
                <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
                <td><?=Html::encode($model->update_company_name)?></td>
                <td></td>
            </tr>
        <?endif;?>

        <?if($model->update_registration_mark):?>
            <tr>
                <td>变更统一社会信用码：</td>
                <td><?=Html::encode($model->registration_mark)?></td>
                <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
                <td><?=Html::encode($model->update_registration_mark)?></td>
                <td></td>
            </tr>
        <?endif;?>

        <?if($model->update_contacts_name):?>
            <tr>
                <td>变更联系人：</td>
                <td><?=Html::encode($model->contacts_name)?></td>
                <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
                <td><?=Html::encode($model->update_contacts_name)?></td>
                <td></td>
            </tr>
        <?endif;?>

        <?if($model->update_contacts_mobile):?>
            <tr>
                <td>变更联系人电话：</td>
                <td><?=Html::encode($model->contacts_mobile)?></td>
                <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
                <td><?=Html::encode($model->update_contacts_mobile)?></td>
                <td></td>
            </tr>
        <?endif;?>

        <?if($model->update_address):?>
            <tr>
                <td>变更地址：</td>
                <td><?=Html::encode($model->area_name.$model->address)?></td>
                <td>变更为：<!--<img src="/static/img/u293_seg1.png" width="100px" height="20px">--></td>
                <td><?=Html::encode($model->update_area_name.$model->update_address)?></td>
                <td></td>
            </tr>
        <?endif;?>
        <tr>
            <th style="text-align: center">法人身份证正面照</th>
            <th style="text-align: center">法人身份证背面照</th>
            <th style="text-align: center">联系人身份证正面照</th>
            <th style="text-align: center">联系人身份证背面照</th>
            <th></th>
        </tr>
        <tr>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_identity_card_front)?>" title="法人身份证正面照" alt="">
            </td>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_identity_card_back)?>" title="法人身份证背面照" alt="">
            </td>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_agent_identity_card_front)?>" title="联系人身份证正面照" alt="">
            </td>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_agent_identity_card_back)?>" title="联系人身份证背面照" alt="">
            </td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center">营业执照信息</th>
            <th style="text-align: center">门脸照</th>
            <th style="text-align: center">全景图</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_business_licence)?>" title="营业执照信息" alt="">
            </td>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_shop_image)?>" title="门脸照" alt="">
            </td>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_panorama_image)?>" title="全景图" alt="">
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center">授权证明</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <?if($model->update_authorize_image):?>
            <?foreach (explode(',',$model->update_authorize_image) as $ka=>$va):?>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($va)?>" title="授权证明" alt="">
            </td>
            <?endforeach;?>
            <?endif;?>
        </tr>
        <tr>
            <th style="text-align: center">其他资质</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <?if($model->update_other_image):?>
                <?foreach (explode(',',$model->update_other_image) as $ko=>$vo):?>
                    <td style="text-align: center">
                        <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vo)?>" title="其他资质" alt="">
                    </td>
                <?endforeach;?>
            <?endif;?>
        </tr>
    </table>
    <table class="table table-hover">
        <th colspan="5" style="font-size: 18px;background-color:#486d93; color: #fff;">审核信息：</th>
        <?if(!empty($LogExamine)):?>
            <?foreach ($LogExamine as $kl=>$vl):?>
                <tr>
                    <td>
                        <span style="margin-right: 100px;">审核时间：<?echo $vl['create_at']?></span>
                        <span style="margin-right: 100px;">审核人：<?echo $vl['create_user_name']?></span>
                        <span style="margin-right: 100px;">审核结果：
                            <?if($vl['examine_result'] == 0):?>
                                系统申请
                            <?elseif ($vl['examine_result'] == 1):?>
                                审核通过
                            <?elseif ($vl['examine_result'] == 2):?>
                                审核驳回
                            <?endif;?>
                        </span>
                        <span>
                            <?if ($vl['examine_result'] == 2):?>
                                审核内容：<?echo $vl['examine_desc']?>
                            <?endif;?>
                        </span>
                        <br /><br />
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
</script>
