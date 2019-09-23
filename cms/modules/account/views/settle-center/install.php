<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\libs\ToolsClass;
use cms\models\SystemAddress;
$this->title = '安装费用支出';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['install'],
        'method' => 'get',
    ]);
    ?>
    <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
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

    </script>

    <input type="hidden" name="id" value="<?=Html::encode($searchModel->id)?>">
    <?=$form->field($searchModel,'member_id')->hiddenInput(['value'=>$searchModel->id])->label(false);?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>申请时间</p>
                    <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker'])->label(false); ?>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker'])->label(false); ?>
                </td>
                <td>
                    <p>业务合作人</p>
                    <?=$form->field($searchModel,'member_name')->textInput(['placeholder'=>'姓名','class'=>'form-control'])->label(false);?>
                    <?=$form->field($searchModel,'member_mobile')->textInput(['placeholder'=>'手机号','class'=>'form-control'])->label(false);?>
                </td>
                <td>
                    <p>商家</p>
                    <?=$form->field($searchModel,'id')->textInput(['placeholder'=>'编号','class'=>'form-control'])->label(false);?>
                    <?=$form->field($searchModel,'name')->textInput(['placeholder'=>'名称','class'=>'form-control'])->label(false);?>
                </td>
                <td>
                    <p>法人代表</p>
                    <?=$form->field($searchModel,'apply_name')->textInput(['placeholder'=>'姓名','class'=>'form-control'])->label(false);?>
                    <?=$form->field($searchModel,'apply_mobile')->textInput(['placeholder'=>'手机号','class'=>'form-control'])->label(false);?>
                </td>
                <td>
                    <p>安装人</p>
                    <?=$form->field($searchModel,'install_name')->textInput(['placeholder'=>'姓名','class'=>'form-control'])->label(false);?>
                    <?=$form->field($searchModel,'install_mobile')->textInput(['placeholder'=>'手机号','class'=>'form-control'])->label(false);?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>法人ID</p>
                    <?=$form->field($searchModel,'shop_member_id')->textInput(['placeholder'=>'法人ID','class'=>'form-control'])->label(false);?>
                </td>
                <td>
                    <p>业务合作人ID</p>
                    <?=$form->field($searchModel,'member_id')->textInput(['placeholder'=>'业务合作人ID','class'=>'form-control'])->label(false);?>
                </td>
                <td>
                    <p>推荐人ID</p>
                    <?=$form->field($searchModel,'introducer_member_id')->textInput(['placeholder'=>'推荐人ID','class'=>'form-control'])->label(false);?>
                </td>
                <td>
                    <p>邀请人ID</p>
                    <?=$form->field($searchModel,'parentmember_id')->textInput(['placeholder'=>'邀请人ID','class'=>'form-control'])->label(false);?>
                </td>
                <td>
                    <p>安装人ID</p>
                    <?=$form->field($searchModel,'install_member_id')->textInput(['placeholder'=>'安装人ID','class'=>'form-control'])->label(false);?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>所属省</p>
                    <?php  echo $form->field($searchModel, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control area'])->label(false) ?>
                </td>
                <td>
                    <p>所属市</p>
                    <?php  echo $form->field($searchModel, 'city')->dropDownList(SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control area'])->label(false) ?>
                </td>
                <td>
                    <p>所属区</p>
                    <?php  echo $form->field($searchModel, 'area')->dropDownList(SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control area'])->label(false) ?>
                </td>
                <td>
                    <p>所属街道</p>
                    <?php  echo $form->field($searchModel, 'town')->dropDownList(SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control'])->label(false) ?>
                </td>
                <td>
                    <p></p>
                    <br/>
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                    <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
                </td>
            </tr>
        </table>
    </div>
    <div class="aa">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '商家名称',
                'value' => function($searchModel){
                    return $searchModel->name;
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name;
                    //return SystemAddress::getAreaNameById($searchModel->area);
                }
            ],
            [
                'label' => '法人ID',
                'value' => function($searchModel){
                    return $searchModel->shopreplace['shop_member_id'];
                }
            ],
            [
                'label' => '法人代表',
                'value' => function($searchModel){
                    return $searchModel->shopreplace['apply_name'];
                }
            ],
            [
                'label' => '法人手机号',
                'value' => function($searchModel){
                    return $searchModel->shopreplace['apply_mobile'];
                }
            ],
            'create_at',
            [
                'label' => '完成时间',
                'value' => function($searchModel){
                    return $searchModel->install_finish_at;
                }
            ],
            [
                'label' => '店铺费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->apply['apply_brokerage']);
                }
            ],
            [
                'label' => '业务合作人ID',
                'value' => function($searchModel){
                    return $searchModel->member_id;
                }
            ],
            [
                'label' => '业务合作人',
                'value' => function($searchModel){
                    return $searchModel->member_name;
                }
            ],
            [
                'label' => '业务合作人手机号',
                'value' => function($searchModel){
                    return $searchModel->member_mobile;
                }
            ],
            [
                'label' => '安装联系费',
                'value' => function($searchModel){
                    if(empty($searchModel->shopContract) || $searchModel->shopContract['examine_status'] == 1){
                        return ToolsClass::priceConvert($searchModel->member_price);
                    }
                    return '---';
                }
            ],
            [
                'label' => '业务合作人红包',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->member_reward_price);
                }
            ],
            [
                'label' => '业务合作人总费用',
                'value' => function($searchModel){
                    if(empty($searchModel->shopContract) || $searchModel->shopContract['examine_status'] == 1) {
                        return ToolsClass::priceConvert($searchModel->member_price + $searchModel->member_reward_price);
                    }else{
                        return ToolsClass::priceConvert($searchModel->member_reward_price);
                    }
                }
            ],
            [
                'label' => '推荐人ID',
                'value' => function($searchModel){
                    return $searchModel->introducer_member_id?$searchModel->introducer_member_id:'--';
                }
            ],
            [
                'label' => '推荐人',
                'value' => function($searchModel){
                    return $searchModel->introducer_member_name?$searchModel->introducer_member_name:'--';
                }
            ],
            [
                'label' => '推荐人手机',
                'value' => function($searchModel){
                    return $searchModel->introducer_member_mobile?$searchModel->introducer_member_mobile:'--';
                }
            ],
            [
                'label' => '推荐人奖励金',
                'value' => function($searchModel){
                    if($searchModel->introducer_member_price==0){
                        return '--';
                    }
                    return ToolsClass::priceConvert($searchModel->introducer_member_price);
                }
            ],
            [
                'label' =>'邀请人ID',
                'value' => function($searchModel){
                    return $searchModel->parentMember['id']?$searchModel->parentMember['id']:'--';
                }
            ],
            [
                'label' =>'邀请人',
                'value' => function($searchModel){
                    return $searchModel->parentMember['name']?$searchModel->parentMember['name']:'--';
                }
            ],
            [
                'label' =>'邀请人手机号',
                'value' => function($searchModel){
                    return $searchModel->parentMember['mobile']?$searchModel->parentMember['mobile']:'--';
                }
            ],
            [
                'label' =>'邀请人奖励金',
                'value' => function($searchModel){
                    if(!$searchModel->parentMember['name'] && !$searchModel->parentMember['mobile']){
                        return '--';
                    }
                    return ToolsClass::priceConvert($searchModel->parent_member_price);
                }
            ],
            [
                'label' => '安装人员ID',
                'value' => function($searchModel){
                    return $searchModel->install_member_id;
                }
            ],
            [
                'label' => '安装人员',
                'value' => function($searchModel){
                    return $searchModel->install_member_name;
                }
            ],
            [
                'label' => '安装人员手机号',
                'value' => function($searchModel){
                    return $searchModel->install_mobile;
                }
            ],
            [
                'label' => '安装地区价格',
                'value' => function($searchModel){
                    if($searchModel->shopreplace['replace_screen_number']){
                        return ToolsClass::priceConvert($searchModel->install_price/$searchModel->shopreplace['replace_screen_number']);
                    }else{
                        return 0;
                    }

                }
            ],
            [
                'label' => '安装屏幕数',
                'value' => function($searchModel){
                    //return $searchModel->screen_number;
                    return $searchModel->shopreplace['replace_screen_number'];
                }
            ],
            [
                'label' => '安装人员总费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->install_price);
                }
            ],
            [
                'label' => '总支出',
                'value' => function($searchModel){
                    if(empty($searchModel->shopContract) || $searchModel->shopContract['examine_status'] == 1) {
                        return ToolsClass::priceConvert($searchModel->apply['apply_brokerage'] + $searchModel->member_price + $searchModel->member_reward_price + $searchModel->install_price + $searchModel->parent_member_price + $searchModel->introducer_member_price);
                    }else{
                        return ToolsClass::priceConvert($searchModel->apply['apply_brokerage'] + $searchModel->member_reward_price + $searchModel->install_price + $searchModel->parent_member_price + $searchModel->introducer_member_price);
                    }
                }
            ],
        ]

    ]);?>
    <?php ActiveForm::end();?>
    </div>
</div>
<style type="text/css">
    .aa{
       white-space: nowrap; overflow: hidden; overflow-x: scroll; -webkit-backface-visibility: hidden; -webkit-overflow-scrolling: touch;
    }
    .search tr td{
        background-color: #f2f2f2;
    }
    p{font-weight: bold;}
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
</style>