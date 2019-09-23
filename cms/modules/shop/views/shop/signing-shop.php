<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\modules\shop\models\Shop;
use cms\modules\examine\models\ShopContract;
$this->title = '商家信息';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="shop_search">
    <?php echo $this->render('layout/store_type',['searchModel'=>$searchModel]);?>
    <?php
    $form = ActiveForm::begin([
        'action' => ['signing-shop','create_at_start'=>$searchModel->create_at_start,'create_at_end'=>$searchModel->create_at_end,'areas'=>$searchModel->areas],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>店铺名称</p>
                    <?=$form->field($searchModel,'name')->textInput(['placeholder'=>'店铺名称','class'=>'form-control fm'])->label(false);?>
                </td>

                <td>
                    <p>店铺联系人</p>
                    <?=$form->field($searchModel,'contacts_name')->textInput(['placeholder'=>'店铺联系人','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>店铺类型</p>
                    <?=$form->field($searchModel,'shop_operate_type')->dropDownList(['1'=>'租赁店','2'=>'自营店','3'=>'连锁店','4'=>'总店'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>联络人电话</p>
                    <?=$form->field($searchModel,'contacts_mobile')->textInput(['placeholder'=>'联络人电话','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>操作业务员</p>
                    <?=$form->field($searchModel,'member_name')->textInput(['placeholder'=>'操作业务员','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>店铺位置</p>
                    <?=$form->field($searchModel,'address')->textInput(['placeholder'=>'店铺位置','class'=>'form-control fm'])->label(false);?>
                </td>

            </tr>
            <tr>
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
                    <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end();?>
</div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '时间',
                'value' => function($searchModel){
                    return $searchModel->shop_examine_at;
                }
            ],
            'name',
            [
                'label' => '店铺联系人',
                'value' => function($searchModel){
                    return  $searchModel->apply['contacts_name'];
                    //return SystemAddress::getAreaNameById($searchModel->area);
                }
            ],
            [
                'label' => '联系人电话',
                'value' => function($searchModel){
                    return  $searchModel->apply['contacts_mobile'];
                    //return SystemAddress::getAreaNameById($searchModel->area);
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
                'label' => '店铺位置',
                'value' => function($searchModel){
                    return  $searchModel->address;
                }
            ],
            [
                'label' => '经度',
                'value' => function($searchModel){
                    return  $searchModel->bd_longitude;
                    //return SystemAddress::getAreaNameById($searchModel->area);
                }
            ],
            [
                'label' => '纬度',
                'value' => function($searchModel){
                    return  $searchModel->bd_latitude;
                    //return SystemAddress::getAreaNameById($searchModel->area);
                }
            ],
            [
                'label' => '店铺类型',
                'value' => function($searchModel){
                    return Shop::getTypeByNum($searchModel->shop_operate_type);
                }
            ],
            [
                'label' => '操作业务员',
                'value' => function($searchModel){
                    return $searchModel->member_name;
                }
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
</script>