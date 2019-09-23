<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\models\SystemAddress;
use cms\modules\member\models\MemberAccount;
$this->title = '我的伙伴';
$this->params['breadcrumbs'][] = ['label' => '人员查询', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $('[key=\"area\"]').change(function () {
        var parent_id = $(this).val();
        var type = $(this).attr('data');
        if(type == 'provience'){
            var nextSel = $('[data=\"city\"]');
            nextSel.find('option').not(':first').remove();
        }
        $.ajax({
            url: '".\yii\helpers\Url::to(['member/address'])."',
            type: 'POST',
            dataType: 'json',
            data:{'parent_id':parent_id},
            success:function (phpdata) {
                $.each(phpdata,function (i,item) {
                    nextSel.append('<option value='+i+'>'+item+'</option>');
                })
            },error:function (phpdata) {
                layer.msg('获取失败！');
            }
        })
    })
");
?>
<div class="partner_view">
    <?php echo $this->render('layout/tab',['model'=>$model]);?>
    <?php
        $form = ActiveForm::begin([
                'action' => ['partner'],
                'method' => 'get',
        ]);
    ?>
    <div class="" style="height: 50px;">
        <div class="col-xs-2">
            <?= Html::activeInput('text',$searchModel,'name',['class'=>"col-xs-2 form-control",'placeholder'=>'姓名']);?>
        </div>
        <input type="hidden" name="id" value="<?=Html::encode($model->id)?>">
        <div class="col-xs-2">
            <?= Html::activeInput('text',$searchModel,'mobile',['class'=>"col-xs-2 form-control",'placeholder'=>'电话']);?>
        </div>
        <div class="col-xs-2">
            <?= Html::activeInput('text',$searchModel,'id',['class'=>"col-xs-2 form-control",'placeholder'=>'工号']);?>
        </div>
<!--        <div class="col-xs-2">-->
<!--            <label for="">级别</label>-->
<!--            --><?//=Html::activeDropDownList($searchModel,'level',['1'=>'一级','2'=>'二级','3'=>'三级','4'=>'四级','5'=>'五级'],['class'=>'form-control fm','prompt'=>'全部']);?>
<!--        </div>-->
<div class="col-xs-2 form-group">
<?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','data'=>'provience','key'=>'area','class'=>'form-control fm'])->label('所属省') ?>
 </div>
        <div class="col-xs-2 form-group">
            <td><?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','data'=>'city','key'=>'area','class'=>'form-control fm'])->label('所属市') ?>
</div>
        <div class="col-xs-1">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
        </div>
    </div>
    <?php ActiveForm::end();?>
   <style>
       .fm{ width:115px ;display: inline-block;}
   </style>


    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'label' => '工号',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            'name',
            [
                'label' => '联系电话',
                'value' => function($searchModel){
                    return $searchModel->mobile;
                }
            ],
            [
                'label' => '所属省',
                'value' => function($searchModel){
                    return  SystemAddress::getAreaByIdLen($searchModel->area,5);
                }
            ],
            [
                'label' => '所属市',
                'value' => function($searchModel){
                    return SystemAddress::getAreaByIdLen($searchModel->area,7);
                }
            ],
            [
                'label' => '收益金额',
                'value' => function($searchModel){
                    return number_format(MemberAccount::getMemTotalPrice($searchModel->id)['count_price']/100,2) ;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['member/view','id'=>$searchModel->id]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
