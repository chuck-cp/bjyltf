<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\authority\models\search\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '角色管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="auth-item-search">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('角色名称');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'description')->textInput(['class'=>'form-control fm'])->label('角色说明');?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('创建角色', 'javascript:void(0);', ['class' => 'btn btn-success createitem']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{relevance} {update} {delete} ',
                'buttons' => [
                    'relevance' => function($url,$searchModel){
                        return html::a('关联权限','javascript:void(0);',['class'=>'relevances','id'=>$searchModel->name]);
                    },
                    'update' => function($url,$searchModel){
                        return html::a('修改','javascript:void(0);',['class'=>'updates','id'=>$searchModel->name]);
                    },
                    'delete' => function($url,$searchModel){
                        return html::a('删除','javascript:void(0);',['class'=>'deletes','id'=>$searchModel->name]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //创建角色
    $('.createitem').click(function () {
        layer.open({
            type: 2,
            title: '创建角色',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '70%'],
            content: '<?=\yii\helpers\Url::to(['/authority/auth-item/create'])?>'
        });
    })

    //relevances
    $('.relevances').click(function () {
        var name = $(this).attr('id');
        layer.open({
            type: 2,
            title: '关联权限',
            shadeClose: true,
            shade: 0.8,
            area: ['90%', '90%'],
            content: '<?=\yii\helpers\Url::to(['/authority/auth-item/relevance'])?>&ruleid='+name
        });
    })
    //修改角色
    $('.updates').click(function () {
        var id = $(this).attr('id');
        layer.open({
            type: 2,
            title: '修改角色',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '70%'],
//            content:url
            content: '<?=\yii\helpers\Url::to(['/authority/auth-item/update'])?>&name='+id
        });
    })
    //删除
    $('.deletes').click(function(){
        var id = $(this).attr('id');
        layer.confirm('确定删除所选数据吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['delete'])?>',
                type : 'GET',
                dataType : 'json',
                data : {'name':id},
                success:function (resdata) {
                    if(resdata ==1){
                        layer.msg('删除成功');
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg('删除失败');
                    }
                },error:function (error) {
//                    layer.msg('操作失败！');
                }
            });
        }, function(){
            //取消
        });
    })
</script>