<?php
use yii\helpers\Html;
use cms\modules\shop\models\Shop;
use cms\modules\examine\models\ShopContract;
use yii\bootstrap\ActiveForm;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    #w0{padding:0 10px;width:98%;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html><head>
    <?php $this->head() ?>
</head>

<?php $this->beginBody(); ?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'id')->textInput(['placeholder'=>'商家编号','class'=>'form-control fm'])->label('商家编号');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'apply_name')->textInput(['placeholder'=>'法人姓名','class'=>'form-control fm'])->label('法人姓名');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'apply_mobile')->textInput(['placeholder'=>'法人手机','class'=>'form-control fm'])->label('法人手机');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
        </div>
    </div>
    <?php ActiveForm::end();?>
</div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\CheckboxColumn'],
            'id',
            'name',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name.$searchModel->address;
                }
            ],
            [
                'label' => '法人姓名',
                'value' => function($searchModel){
                    return $searchModel->apply['apply_name'];
                }
            ],
            [
                'label' => '法人电话',
                'value' => function($searchModel){
                    return $searchModel->apply['apply_mobile'];
                }
            ],

            [
                'label' => '店铺类型',
                'value' => function($searchModel){
                    return Shop::getTypeByNum($searchModel->shop_operate_type);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{choose}',
                'buttons' => [
                    'choose' => function($url,$searchModel){
                        return Html::a('选择','javascript:void(0);',['class'=>'choose','id'=>$searchModel->id,'name'=>$searchModel->name,'apply_name'=>$searchModel->apply['apply_name'],'apply_mobile'=>$searchModel->apply['apply_mobile'],'identity_card_num'=>$searchModel->apply['identity_card_num'],'company_name'=>$searchModel->apply['company_name'],'address'=>$searchModel->area_name.$searchModel->address,'registration_mark'=>$searchModel->apply['registration_mark'],'contacts_name'=>$searchModel->apply['contacts_name'],'contacts_mobile'=>$searchModel->apply['contacts_mobile']]);
                    },
                ],
            ],
        ]
    ]);?>
</div>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.choose').on('click',function () {
        var index = parent.layer.getFrameIndex(window.name);
        var shopid = $(this).attr('id');//商家ID
        var shopname = $(this).attr('name');//商家名称
        var apply_name = $(this).attr('apply_name');//法人名称
        var apply_mobile = $(this).attr('apply_mobile');//手机号
        var identity_card_num = $(this).attr('identity_card_num');//身份证号
        var company_name = $(this).attr('company_name');//公司名称
        var address = $(this).attr('address');//店铺地址
        var registration_mark = $(this).attr('registration_mark');//统一社会信用码
        var contacts_name = $(this).attr('contacts_name');//联系人
        var contacts_mobile = $(this).attr('contacts_mobile');//联系人电话
        parent.$("input[name='shop_id']").val(shopid);
        parent.$("input[name='shop_name']").val(shopname);
        parent.$("input[name='apply_name']").val(apply_name);
        parent.$("input[name='apply_mobile']").val(apply_mobile);
        parent.$("input[name='identity_card_num']").val(identity_card_num);
        parent.$("input[name='company_name']").val(company_name);
        parent.$("input[name='address']").val(address);
        parent.$("input[name='registration_mark']").val(registration_mark);
        parent.$("input[name='contacts_name']").val(contacts_name);
        parent.$("input[name='contacts_mobile']").val(contacts_mobile);
        parent.layer.close(index);
    })
</script>