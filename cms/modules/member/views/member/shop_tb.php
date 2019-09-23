<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\models\TbInfoRegion;

use cms\modules\member\models\MemberAccount;
$this->title = '商家信息';
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
    <input type="hidden" name="id" value="<?=Html::encode($model->id)?>">
    <?=$form->field($searchModel,'user_id')->hiddenInput(['value'=>$model->id])->label(false);?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'id')->textInput(['class'=>'form-control fm'])->label('商家编号');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'province')->dropDownList(TbInfoRegion::getAreasByPid(110),['prompt'=>'全部','data'=>'province','key'=>'area','class'=>'form-control fm'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'city')->dropDownList(TbInfoRegion::getAreasByPid($searchModel->province),['prompt'=>'全部','data'=>'city','key'=>'area','class'=>'form-control fm'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'area')->dropDownList(TbInfoRegion::getAreasByPid($searchModel->city),['prompt'=>'全部','data'=>'area','key'=>'area','class'=>'form-control fm'])->label('所属区') ?>
        </div>
        <script type="text/javascript" src="http://libs.baidu.com/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript">
            $(function () {
              $('[key="area"]').change(function () {
                  var parent_id = $(this).val();
                  var type = $(this).attr('data');
                  if(type == 'province'){
                      var nextSel = $('[data="city"]');
                      $('[key="area"]').not(':first').find('option').not(':first').remove();
                  }else if(type == 'city'){
                      var nextSel = $('[data="area"]');
                      $('[key="area"]:last').find('option').not(':first').remove();
                  }
                  $.ajax({
                      url: "<?= \yii\helpers\Url::to(['/member/member/address'])?>",
                      type: 'POST',
                      dataType: 'json',
                      data:{'parent_id':parent_id},
                      success:function (phpdata) {
                          $.each(phpdata,function (i,item) {
                              nextSel.append('<option value='+i+'>'+item+'</option>');
                          })
                      },error:function (phpdata) {
                          console.log(phpdata);
                          layer.msg('获取失败！');
                      }
                  })
              })

            })
        </script>
<!--        <div class="col-xs-2 form-group">-->
<!--            --><?php // echo $form->field($searchModel, 'area')->dropDownList(TbInfoRegion::getAreasByPid(110),['prompt'=>'全部','data'=>'provience','key'=>'area','class'=>'form-control fm'])->label('所属街道') ?>
<!--        </div>-->
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'shop_name')->textInput(['class'=>'form-control fm'])->label('商家名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'acreage')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('店铺面积');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'mirror_account')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('镜面数量');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'led_account')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请数量');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'channel')->dropDownList(['0'=>'手机端','1'=>'PC端'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请客户端');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'status')->dropDownList(['0'=>'待审核','1'=>'审核通过','2'=>'审核未通过','3'=>'安装完成'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请状态');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'reference_id')->dropDownList(['1'=>'有推荐人','2'=>'无推荐人'],['class'=>'form-control fm','prompt'=>'全部'])->label('入驻方式');?>
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
            'shop_name',
            [
                'label' => '所属省',
                'value' => function($searchModel){
                    return  TbInfoRegion::getAreaByIdLen($searchModel->province,5);
                    //return TbInfoRegion::getAreaNameById($searchModel->area);
                }
            ],
            [
                'label' => '所属市',
                'value' => function($searchModel){
                    return TbInfoRegion::getAreaByIdLen($searchModel->city,7);
                }
            ],
            [
                'label' => '所属区',
                'value' => function($searchModel){
                    return TbInfoRegion::getAreaByIdLen($searchModel->area,9);
                }
            ],
//            [
//                'label' => '所属街道',
//                'value' => function($searchModel){
//                    return TbInfoRegion::getAreaByIdLen($searchModel->area,11);
//                }
//            ],
            'address',
            'acreage',
            'mirror_account',
            'led_account',
            'apply_time',
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return '安装中';
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