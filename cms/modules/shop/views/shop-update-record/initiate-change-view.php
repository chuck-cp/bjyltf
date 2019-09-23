<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '商家信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

?>
<style type="text/css">
    .fm{width: 250px;}
</style>
<div class="shop-view" style="width: 100%;">
<!--    <h3 style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;"><b>商家信息：</b></h3>-->
    <?php $form = ActiveForm::begin([
        'action' => ['admin-member'],
        'method' => 'post',
    ]); ?>

    <table class="table table-hover">
        <tr>
            <th colspan="6" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;">商家信息:</th>
        </tr>
        <tr>
            <td>商家ID：</td>
            <td><input type="text" readonly class="form-control fm" name="shop_id" value="<?=Html::encode($model->shop_id)?>"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>商家名称：</td>
            <td><input type="text" disabled class="form-control fm" name="shop_name" value="<?=Html::encode($model->shop_name)?>"></td>
            <td>法人名称：</td>
            <td><input type="text" disabled class="form-control fm" name="apply_name" value="<?=Html::encode($model->apply_name)?>"></td>
            <td>手机号：</td>
            <td><input type="text" disabled class="form-control fm" name="apply_mobile" value="<?=Html::encode($model->apply_mobile)?>"></td>
        </tr>
        <tr>
            <td>身份证号：</td>
            <td><input type="text" disabled class="form-control fm" name="identity_card_num" value="<?=Html::encode($model->identity_card_num)?>"></td>
            <td>公司名称：</td>
            <td><input type="text" disabled class="form-control fm" name="company_name" value="<?=Html::encode($model->company_name)?>"></td>
            <td>店铺地址：</td>
            <td><input type="text" disabled class="form-control fm" name="address" value="<?=Html::encode($model->area_name.$model->address)?>"></td>
        </tr>
        <tr>
            <td>统一社会信用码：</td>
            <td><input type="text" disabled class="form-control fm" name="registration_mark" value="<?=Html::encode($model->registration_mark)?>"></td>
            <td>联系人：</td>
            <td><input type="text" disabled class="form-control fm" name="contacts_name" value="<?=Html::encode($model->contacts_name)?>"></td>
            <td>联系人电话：</td>
            <td><input type="text" disabled class="form-control fm" name="contacts_mobile" value="<?=Html::encode($model->contacts_mobile)?>"></td>
        </tr>
<!--    </table>-->
<!--    <table cclass="table table-hover" style="width: 100%;">-->
        <tr>
            <th colspan="6" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;">法人变更</th>
        </tr>
        <tr>
            <td><span style="color: red; font-size: 20px;">*</span> 变更后法人：</td>
            <td>
                <input type="text" disabled name="update_apply_name" class="form-control fm" value="<?=Html::encode($model->update_apply_name)?>">
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><span style="color: red; font-size: 20px;">*</span> 变更手机号：</td>
            <td><input type="text" disabled id="btn_getNum" name="update_apply_mobile" class="form-control fm" value="<?=Html::encode($model->update_apply_mobile)?>"></td>
            <td></td>
            <td><p class="check_member" style="font-size: 15px;"></p></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><span style="color: red; font-size: 20px;">*</span> 变更后法人身份证号：</td>
            <td><input type="text" disabled name="update_identity_card_num" class="form-control fm" value="<?=Html::encode($model->update_identity_card_num)?>"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center"><span style="color: red; font-size: 20px;">*</span>法人身份证正面照</th>
            <th style="text-align: center"><span style="color: red; font-size: 20px;">*</span>法人身份证背面照</th>
            <th style="text-align: center"></th>
            <th style="text-align: center"></th>
            <th style="text-align: center"></th>
            <th style="text-align: center"></th>
        </tr>
        <tr>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_identity_card_front)?>" title="法人身份证正面照" alt="">
            </td>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_identity_card_back)?>" title="法人身份证背面照" alt="">
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th colspan="6" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;">联系人变更</th>
        </tr>
        <tr>
            <td><p>联系人：</p></td>
            <td><input type="text" disabled name="update_contacts_name" class="form-control fm" value="<?=Html::encode($model->update_contacts_name)?>"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><p>联系人电话：</p></td>
            <td><input type="text" disabled name="update_contacts_mobile" class="form-control fm" value="<?=Html::encode($model->update_contacts_mobile)?>"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center">联系人身份证正面照</th>
            <th style="text-align: center">联系人身份证背面照</th>
            <th style="text-align: center"></th>
            <th style="text-align: center"></th>
            <th style="text-align: center"></th>
            <th style="text-align: center"></th>
        </tr>
        <tr>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_agent_identity_card_front)?>" title="联系人身份证正面照" alt="">
            </td>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_agent_identity_card_back)?>" title="联系人身份证背面照" alt="">
            </td>
            <td style="text-align: center"></td>
            <td style="text-align: center"></td>
            <td style="text-align: center"></td>
            <td style="text-align: center"></td>
        </tr>
        <tr>
            <th colspan="6" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;">店铺信息变更</th>
        </tr>
        <tr>
            <td><p>变更店铺名称：</p></td>
            <td><input type="text" disabled name="update_shop_name" class="form-control fm" value="<?=Html::encode($model->update_shop_name)?>"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><p>变更公司名称：</p></td>
            <td><input type="text" disabled name="update_company_name" class="form-control fm" value="<?=Html::encode($model->update_company_name)?>"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><p>统一社会信用码：</p></td>
            <td><input type="text" disabled name="update_registration_mark" class="form-control fm" value="<?=Html::encode($model->update_registration_mark)?>"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><p>所属地区</p></td>
            <td><input type="text" disabled name="update_area_name" class="form-control fm" value="<?=Html::encode($model->update_area_name)?>"></td>
            <td><p>详细地址：</p></td>
            <td><input type="text" disabled name="update_address" class="form-control fm" value="<?=Html::encode($model->update_address)?>"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th colspan="6" style="border-top: none;font-size: 18px; background-color:#486d93; color: #fff;">店铺图片变更</th>
        </tr>
        <tr>
            <th style="text-align: center">营业执照信息</th>
            <th style="text-align: center">店铺门面</th>
            <th style="text-align: center">店铺全景</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="text-align: center">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_business_licence)?>" title="营业执照信息" alt="">
            </td>
            <td style="text-align: center;">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_shop_image)?>" title="店铺门面" alt="">
            </td>
            <td style="text-align: center;">
                <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($model->update_panorama_image)?>" title="店铺全景" alt="">
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center">授权证明</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <?foreach (explode(',',$model->update_authorize_image) as $kua=>$vua):?>
                <td style="text-align: center">
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vua)?>" title="授权证明" alt="">
                </td>
            <?endforeach;?>
        </tr>
        <tr>
            <th style="text-align: center;">其他资质</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <?foreach (explode(',',$model->update_other_image) as $kuo=>$vuo):?>
                <td style="text-align: center">
                    <img style="margin: 0 5% 0 5%" width="150" height="auto" src="<?=Html::encode($vuo)?>" title="其他资质" alt="">
                </td>
            <?endforeach;?>
        </tr>
        <?if(!empty($desc)):?>
            <tr><th colspan="6" style="border-top: none;font-size: 18px;background-color:#486d93; color: #fff;"><b>审核信息</b></th></tr>
            <?php foreach ($desc as $v):?>
                <tr>
                    <td colspan="6">
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
        <?if($model->examine_status == 2 || $model->examine_status===null):?>
        <tr style="text-align: center;">
            <td colspan="5"><?=Html::Button('提交',['class'=>'btn btn-primary identity', 'id'=>'submit123'])?></td>
        </tr>
        <?endif;?>
    </table>
    <?php ActiveForm::end(); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/jquery.media.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.panel-body').viewer({
        url: 'src',
    });
    $(function () {
        $('.area').change(function () {
            var type = $(this).attr('key');
            var selObj = $('[key='+type+']').parents('td');
            selObj.nextAll().find('select').find('option:not(:first)').remove();
            var parent_id = $(this).val();
            if(!parent_id){
                return false;
            }
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
                type: 'POST',
                dataType: 'json',
                data:{'parent_id':parent_id},
                success:function (phpdata) {
                    $.each(phpdata,function (i,item) {
                        selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                    })
                },error:function (phpdata) {
                    layer.msg('获取失败！');
                }
            })
        })
    })
    $(function(){
        $('#submit123').click(function(){
            var data=$('#w0').serialize();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['admin-member'])?>',
                type : 'POST',
                dataType : 'json',
                data : data,
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            $(location).attr('href', '<?=\yii\helpers\Url::to(['index'])?>');
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！',{icon:7});
                }
            })
        })
    })

    //商家选择层
    $('.choose').bind('click', function () {
        layer.open({
            type: 2,
            title: '商家选择',
            shadeClose: true,
            shade: 0.8,
            area: ['90%', '90%'],
            content: '<?=\yii\helpers\Url::to(['choose-shops'])?>'
        });
    })
    //验证手机号是否注册
    $('.checkbut').on('click',function () {
        var mobile = $('input[name="update_apply_mobile"]').val();
        var checkMobiles = checkMobile(mobile);
        if(checkMobiles){
            if(checkMobiles.code == 2 ){
                $('.check_member').html('');
                var html='';
                $.ajax({
                    url: '<?=\yii\helpers\Url::to(['mobile-check-register'])?>',
                    type: 'GET',
                    dataType: 'json',
                    data:{'mobile':mobile},
                    success:function (phpdata) {
                        html += phpdata.name+','+phpdata.mobile;
                        $('.check_member').append(html);
                    },error:function (phpdata) {
                        layer.msg('获取失败！');
                    }
                });
            }else{
                alert(checkMobiles.msg);
            }
        }
    });

    //input是否为手机号
    function checkMobile(str) {
        if(str==""){
            return{'code':1,'msg':'手机号不能为空!'}
        }else{
            var re = /^1\d{10}$/
            if (re.test(str)) {
                return{'code':2,'msg':'手机号格式正确!'}
            } else {
                return{'code':3,'msg':'手机号格式错误!'}
            }
        }
    }
</script>