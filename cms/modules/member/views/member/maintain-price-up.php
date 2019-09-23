<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $model cms\models\ScreenRunTimeShopSubsidy */
cms\assets\AppAsset::register($this);

$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>

<div class="screen-run-time-shop-subsidy-view">
    <table style="border: 1px solid #dddddd; width:30%; margin:20px 0 0 10px;text-align: center;" >
        <tr>
            <th style="text-align: center;" >
                <p style="margin-top: 10px;font-size: 16px;">
                    费用总计：<?echo $TotalPrice?>元
                </p>
            </th>
            <th style="text-align: center;" >
                <p style="margin-top: 10px;font-size: 16px;">
                    <a class="btn btn-primary" id="SubmitPrice"> 确认修改</a>
                </p>
            </th>
        </tr>
    </table>
    <input type="hidden" value="<?echo $shop_id?>" name="shop_id">
    <input type="hidden" value="<?echo $date?>" name="datee">
    <form id="Myform">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'ID',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '屏幕编号',
                'value' => function($searchModel){
                    return $searchModel->software_number;
                }
            ],
            [
                'label' => '屏幕周期内开启天数',
                'value' => function($searchModel){
                    return $searchModel->number;
                }
            ],
            [
                'header' => '屏幕维护费用',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function($url,$searchModel){
                        return Html::input('text',"price[$searchModel->id]",ToolsClass::priceConvert($searchModel->price));
                    }
                ],
            ],
            [
                'label' => '应发维护费',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->reduce_price);
                }
            ],
        ],
    ]); ?>
    </form>
</div>
<?php $this->endBody() ?>
<style>
    input{
        border:solid 1px #ddd;
        width:20%;
        height:25px;
        text-align: center;
        border-radius:5px;
    }
</style>
<script>

    $('#SubmitPrice').click(function(){
        var data=$('#Myform').serialize();
        var shop_id=$('input[name="shop_id"]').val()
        var datee=$('input[name="datee"]').val()
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['maintain-price-up'])?>&shop_id='+shop_id+'&date='+datee,
            type : 'POST',
            dataType : 'json',
            data : data,
            success:function (data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon:1});
                    setTimeout(function(){
                        parent.location.reload();
                    },2000);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！',{icon:7});
            }
        });
    })

</script>
</body>

<?php $this->endPage() ?>