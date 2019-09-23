<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemVersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告配置';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="system-version-index">

<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('layout/ifram')?>
    <p>
        <?= Html::a('添加', 'javascript:void(0);', ['class' => 'btn btn-success']) ?>
    </p>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            [
                'label' => '广告形式',
                'value' => function($model){
                    return $model->type == 1 ? '视频' : '图片';
                }
            ],
            'time',
            'spec',
            'create_user_name',
            'update_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{upplace}  {delplace}',
                'buttons' => [
                    'upplace' => function($url,$model){
                        return html::a('编辑','javascript:void(0);',['class'=>'upplace','id'=>$model->id]);
                    },
                    'delplace' => function($url,$model){
                        return html::a('删除','javascript:void(0);',['class'=>'delplace','id'=>$model->id,'action'=>$action = Yii::$app->controller->action->id]);
                    }
                ],
            ],

        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.btn-success').click(function () {
        var pg = layer.open({
            type: 2,
            title: '添加配置',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/config/advert-config/placecreate'])?>'
        });
    })
    $('.upplace').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '修改配置',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/config/advert-config/placeup'])?>&id='+id
        });
    })
    $('.delplace').click(function(){
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
//            layer.msg('也可以这样', {
//                time: 20000, //20s后自动关闭
//                btn: ['明白了', '知道了']
//            });
        });
    })

</script>