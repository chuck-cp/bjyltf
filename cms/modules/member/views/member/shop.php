<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\models\SystemAddress;
use cms\modules\member\models\MemberAccount;
$this->title = '商家信息';
$this->params['breadcrumbs'][] = ['label' => '人员查询', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="shop_search">
    <?php echo $this->render('layout/tab',['model'=>$model]);?>
    <?php
    $form = ActiveForm::begin([
        'action' => ['shop'],
        'method' => 'get',
    ]);
    ?>
<!--    <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
    <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.area').change(function () {
                var type = $(this).attr('key');
                var selObj = $('[key='+type+']').parents('.col-xs-2');
                selObj.nextAll().find('select').find('option:not(:first)').remove();
                var parent_id = $(this).val();
                if(!parent_id){
                    return false;
                }
                $.ajax({
                        url: '<?=\yii\helpers\Url::to(['member/address'])?>',
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
    
    <input type="hidden" name="id" value="<?=Html::encode($model->id)?>">
    <?=$form->field($searchModel,'member_id')->hiddenInput(['value'=>$model->id])->label(false);?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'id')->textInput(['class'=>'form-control fm'])->label('商家编号');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'city')->dropDownList(SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'area')->dropDownList(SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'town')->dropDownList(SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('商家名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'acreage')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('店铺面积');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'apply_screen_number')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请数量');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'apply_client')->dropDownList(['0'=>'手机端','1'=>'PC端'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请客户端');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'status')->dropDownList(['0'=>'待审核','1'=>'申请未通过','2'=>'待安装','3'=>'安装待审核','4'=>'安装未通过','5'=>'已安装'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请状态');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'way')->dropDownList(['0'=>'有推荐人','1'=>'无推荐人'],['class'=>'form-control fm','prompt'=>'全部'])->label('入驻方式');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
        </div>
    </div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            'name',
            [
                'label' => '所属省',
                'value' => function($searchModel){
                    return  SystemAddress::getAreaByIdLen($searchModel->area,5);
                    //return SystemAddress::getAreaNameById($searchModel->area);
                }
            ],
            [
                'label' => '所属市',
                'value' => function($searchModel){
                    return SystemAddress::getAreaByIdLen($searchModel->area,7);
                }
            ],
            [
                'label' => '所属区',
                'value' => function($searchModel){
                    return SystemAddress::getAreaByIdLen($searchModel->area,9);
                }
            ],
            [
                'label' => '所属街道',
                'value' => function($searchModel){
                    return SystemAddress::getAreaByIdLen($searchModel->area,11);
                }
            ],
            'acreage',
            'apply_screen_number',
            'screen_number',
            'create_at',
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return \cms\modules\shop\models\Shop::getStatusByNum($searchModel->status);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/shop/shop/view','id'=>$searchModel->id]);
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