<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\config\models\SystemZonePrice;
use common\libs\ToolsClass;
use cms\modules\config\models\SystemZoneList;
use yii\bootstrap\ActiveForm;
$this->title = '每日补助设置';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
    .form-control-select {
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        color: #555555;
        vertical-align: middle;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }
')
?>
<div class="system-zone-price-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('layout/tab')?>
<?php $from=ActiveForm::begin([
//    'action'=>[],
    'method'=>'post',
])?>
    <?= Html::a('创建每日补助', ['subcreate'], ['class' => 'btn btn-primary']) ?>
    <select id="" class="form-control-select" name="subsidy_date">
        <option value="0">请选择发放补助日期</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
    </select>
    <?= Html::Button('保存',['class'=>'btn btn-primary'])?>
    <?php ActiveForm::end(); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => '补助价格(元)',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert(SystemZoneList::getPrice($searchModel->id));
                }
            ],
            [
                'label' => '区域名称',
                'value' => function($searchModel){
                    return SystemZonePrice::getAreaByPrice($searchModel->id);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{subsidy} {delprice}',
                'buttons' => [
                    'subsidy' => function($url,$searchModel){
                        return Html::a('查看详情',['/config/zone-price/subview','id'=>$searchModel->id]);
                    },
                    'delprice' => function($url,$searchModel){
                        return Html::a('删除','javascript:void(0);',['class'=>'delprice','data_id'=>$searchModel->id]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //取消操作返回列表
    $('.delprice').on('click', function () {
        var priceid = $(this).attr('data_id');
        layer.confirm('您确定需要删除该项设置？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['delprice'])?>',
                type : 'GET',
                dataType : 'json',
                data : {'priceid':priceid},
                success:function (resdata) {
                    if(resdata ==1){
                        layer.msg('删除成功');
                    }else{
                        layer.msg('删除失败');
                    }
                    setTimeout(function(){
                        window.parent.location.reload();
                    },2000);
                },error:function (error) {
                    layer.msg('操作失败！');
                }
            })
        }, function(){
            layer.msg('您已取消');
        });
    })
</script>