<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\models\AdvertPosition;
use common\libs\ToolsClass;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemVersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告配置';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = '';
?>
<div class="system-version-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('layout/ifram')?>
    <p>
        <?= Html::a('添加','javascript:void(0);', ['class' => 'btn btn-success']) ?>
    </p>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'label' => '广告位名称',
                'value' => function($model){
                    return AdvertPosition::getAdvername($model->advert_id);
                }
            ],
            'time',
//            'price',
            [
                'label' => '一级广告价格',
                'value' => function($model){
                    return ToolsClass::priceConvert($model->price_1);
                }
            ],
            [
                'label' => '二级广告价格',
                'value' => function($model){
                    return ToolsClass::priceConvert($model->price_2);
                }
            ],
            [
                'label' => '三级广告价格',
                'value' => function($model){
                    return ToolsClass::priceConvert($model->price_3);
                }
            ],
            'create_user_name',
            'update_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{upprice}  {delprice}',
                'buttons' => [
                    'upprice' => function($url,$model){
                        return html::a('编辑','javascript:void(0);',['class'=>'upprice','id'=>$model->id]);
                    },
                    'delprice' => function($url,$model){
                        return html::a('删除','javascript:void(0);',['class'=>'delprice','id'=>$model->id,'action'=>$action = Yii::$app->controller->action->id]);
                    }
                ],
            ],

        ],
    ]); ?>
</div>
<!--<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.btn-success').click(function () {
        var pg = layer.open({
            type: 2,
            title: '添加配置',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/config/advert-config/pricecreate'])?>'
        });
    })
    $('.upprice').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '修改配置',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/config/advert-config/priceup'])?>&id='+id
        });
    })
    $('.delprice').click(function(){
        var id = $(this).attr('id');
        var action = $(this).attr('action');
        layer.confirm('确定删除所选数据吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['deldate'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'action':action},
                success:function (resdata) {
                    if(resdata ==1){
                        layer.msg('删除成功');
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg('删除失败');
//                        setTimeout(function(){
//                            window.parent.location.reload();
//                        },2000);
                    }
                },error:function (error) {
//                    layer.msg('操作失败！');
                }
            });
        }, function(){

        });
    })
</script>