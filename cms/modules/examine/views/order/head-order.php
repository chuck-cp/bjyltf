<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\modules\shop\models\Shop;
use cms\modules\member\models\MemberAccount;
$this->title = '自定义广告';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>
    <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.area').change(function () {
                var type = $(this).attr('key');
                var selObj = $('[key='+type+']').parents('.col-xs-2');
                selObj.nextAll().find('select').find('option:not(:first)').remove();
                var parent_id = $(this).val();
                //alert(parent_id);
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

    <div class="row">
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'company_name')->textInput(['class'=>'form-control fm'])->label('总部名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
            <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
        </div>
    </div>
    <?php echo $this->render('layout/sorder',['searchModel'=>$searchModel]);?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\CheckboxColumn'],
//            'id',
            [
                'label' => '总部ID',
                'value' =>function($model){
                    return $model->head['id'];
                }
            ],
            [
                'label' => '总部名称',
                'value' =>function($model){
                    return $model->head['company_name'];
                }
            ],
            [
                'label' => '所属地区',
                'value' =>function($model){
                    return html_entity_decode($model->head['company_area_name']);
                }
            ],
            [
                'label' => '详细地址',
                'value' =>function($model){
                    return $model->head['company_address'];
                }
            ],
            [
                'label' => '屏幕数量',
                'value' =>function($model){
                    return '-';
                }
            ],
            [
                'label' => '图片数量',
                'value' =>function($model){
                    return $model->imgnum;
                }
            ],
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/examine/order/shop-advert-image-view','shop_id'=>$searchModel->shop_id, 'shop_type'=>$searchModel->shop_type]);
                    }
                ],
            ],
        ]

    ]);?>
    <?php ActiveForm::end();?>
</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
</style>