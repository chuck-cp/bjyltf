<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\modules\shop\models\Shop;
use cms\modules\examine\models\ShopContract;
use cms\models\LoginForm;
$this->title = '商家信息';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="shop_search">
    <?php echo $this->render('layout/shop_option',['model'=>$searchModel]);?>
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>商家</p>
                    <?=$form->field($searchModel,'id')->textInput(['placeholder'=>'商家编号','class'=>'form-control fm'])->label(false);?>
                    <?=$form->field($searchModel,'name')->textInput(['placeholder'=>'商家名称','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>法人</p>
                    <?=$form->field($searchModel,'apply_name')->textInput(['placeholder'=>'法人代表','class'=>'form-control fm'])->label(false);?>
                    <?=$form->field($searchModel,'apply_mobile')->textInput(['placeholder'=>'法人手机','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>业务员</p>
                    <?=$form->field($searchModel,'member_name')->textInput(['placeholder'=>'业务员姓名','class'=>'form-control fm'])->label(false);?>
                    <?=$form->field($searchModel,'member_mobile')->textInput(['placeholder'=>'业务员手机','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>联系人</p>
                    <?=$form->field($searchModel,'contacts_name')->textInput(['placeholder'=>'联系人姓名','class'=>'form-control fm'])->label(false);?>
                    <?=$form->field($searchModel,'contacts_mobile')->textInput(['placeholder'=>'联系人手机','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>安装人</p>
                    <?=$form->field($searchModel,'install_member_name')->textInput(['placeholder'=>'安装人姓名','class'=>'form-control fm'])->label(false);?>
                    <?=$form->field($searchModel,'install_mobile')->textInput(['placeholder'=>'安装人电话','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>创建时间</p>
                    <?= $form->field($searchModel, 'create_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
                <td>
                    <p>店铺审核通过时间</p>
                    <?= $form->field($searchModel, 'shop_examine_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                    <?= $form->field($searchModel, 'shop_examine_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
                <td colspan="">
                    <p>店铺安装完成时间</p>
                    <?= $form->field($searchModel, 'install_finish_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                    <?= $form->field($searchModel, 'install_finish_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
                <td colspan="">
                    <p>合同审核通过时间</p>
                    <?= $form->field($searchModel, 'contract_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                    <?= $form->field($searchModel, 'contract_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>店铺类型</p>
                    <?=$form->field($searchModel,'shop_operate_type')->dropDownList(['1'=>'租赁店','2'=>'自营店','3'=>'连锁店','4'=>'总店'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>店铺状态</p>
                    <?=$form->field($searchModel,'status')->dropDownList(['0'=>'待审核','1'=>'审核未通过','2'=>'待安装','3'=>'安装待审核','4'=>'安装未通过','5'=>'安装完成','6'=>'关闭店铺'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>合同状态</p>
                    <?=$form->field($searchModel,'shop_contract')->dropDownList(['1'=>'待审核','2'=>'审核未通过','3'=>'审核通过','4'=>'解除合同','5'=>'没有合同'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>店铺广告</p>
                    <?=$form->field($searchModel,'agreed')->dropDownList(['1'=>'开启','2'=>'关闭'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>所属省</p>
                    <?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属市</p>
                    <?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属区</p>
                    <?php  echo $form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属街道</p>
                    <?php  echo $form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label(false) ?>
                </td>

                <td style="padding-top: 30px;">
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                    <?if(LoginForm::checkPermission('/export/power/shop_export')):?>
                        <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
                    <?endif;?>
                    <?/*if(in_array(Yii::$app->user->identity->username,['ylcmbeijing','ylcmshanghai','ylcmgaungzhou','ylcmgaungzhou','ylcmtianjin','ylcmhangzhou','shaoshuwei','wangxiaojuan','wuyanfeng'])):*/?><!--
                    <?/*else:*/?>
                        <?/*=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])*/?>
                    --><?/*endif;*/?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end();?>
</div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            [
                'label' => '订单编号',
                'value' => function($searchModel){
                    return $searchModel->apply['apply_code'];
                }
            ],
            'name',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name;
                    //return SystemAddress::getAreaNameById($searchModel->area);
                }
            ],
            'acreage',
            'mirror_account',
            'screen_number',
            'create_at',
            'install_finish_at',
            [
                'label' => '店铺类型',
                'value' => function($searchModel){
                    return Shop::getTypeByNum($searchModel->shop_operate_type);
                }
            ],
            [
                'label' => '是否更换屏幕',
                'value' => function($searchModel){
                    return $searchModel->replace_screen_status == 1?'是':'否';
                }
            ],
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return \cms\modules\shop\models\Shop::getStatusByNum($searchModel->status);
                }
            ],
            [
                'label' => '合同状态',
                'value' => function($searchModel){
                    if($searchModel->shopContract['status'] == 2){
                        return '合同解除';
                    }else {
                        return ShopContract::getContractStatus($searchModel->shopContract['examine_status']);
                    }
                }
            ],
            [
                'label' => '合同通过时间',
                'value' => function($searchModel){
                    if(!$searchModel->shopContract['examine_at']){
                        return '0000-00-00 00:00:00';
                    }
                    return $searchModel->shopContract['examine_at'];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}  {lable}  {store_adver} {close}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/shop/shop/view','id'=>$searchModel->id]);
                    },
                    'lable' => function($url,$searchModel){
                        return Html::a('标签','javascript:void(0);',['class'=>'lable','shopid'=>$searchModel->id]);
                    },
                    'store_adver' => function($url,$searchModel){
                        if($searchModel->agreed==0)
                            return Html::a('开启店铺广告','javascript:void(0);',['class'=>'agreed','id'=>$searchModel->id,'agreed'=>$searchModel->agreed]);
                        return Html::a('关闭店铺广告','javascript:void(0);',['class'=>'agreed','id'=>$searchModel->id,'agreed'=>$searchModel->agreed]);
                    },
                    'close' =>function($url,$searchModel){
                        if($searchModel->screen_number == 0){
                            return Html::a('关店','javascript:void(0);',['class'=>'closed','shopid'=>$searchModel->id]);
                        }
                    }
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '标签',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel) use ($LableArr){
                        $labels = [];
                        foreach ($LableArr as $v){
                            foreach (explode(',',$searchModel->lable_id) as $vv){
                                if($v['id']==$vv){
                                    $labels[] = Html::a($v['title'],'javascript:void(0);',['title'=>$v['desc'],'shopid'=>$searchModel->id]);
                                }
                            }
                        }
                        return implode(',',$labels);
                    },
                ],
            ],
        ]
    ]);?>
</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
    .start {width: 50%}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
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
    //标签窗口
    $('.lable').on('click',function () {
        var shopid = $(this).attr('shopid');
        var pageup = layer.open({
            type: 2,
            title: '标签',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/shop/shop/lables'])?>&shopid='+shopid
        });
    })
    $('.agreed').click(function(){
        var id=$(this).attr('id');
        var agreed=$(this).attr('agreed');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['store-adver'])?>&id='+id,
            type : 'POST',
            dataType : 'json',
            data : {'agreed':agreed},
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
                layer.msg('操作失败！');
            }
        });
    })
    //关店
    $('.closed').on('click',function(){
        var id=$(this).attr('shopid');
        layer.confirm('确定要关闭此店铺吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['close-shop'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id},
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
        });
    })
</script>