<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use cms\modules\authority\models\AuthRule;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\authority\models\search\AuthRuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权限管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-rule-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="auth-rule-search">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('权限名称');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'data')->textInput(['class'=>'form-control fm'])->label('权限描述');?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('创建权限', 'javascript:void(0);', ['class' => 'btn btn-success createrule']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],
        //    'id',
            'name',
            'data',
            [
                'label' => '权限等级',
                'value' => function($searchModel){
                    return  AuthRule::getActionByLevel($searchModel->level);
                }
            ],
            'created_at',
            'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{relevance} {update} {delete} ',
                'buttons' => [
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
    $('.createrule').click(function () {
        layer.open({
            type: 2,
            title: '创建权限',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '70%'],
            content: '<?=\yii\helpers\Url::to(['/authority/auth-rule/create'])?>'
        });
    })

    //修改权限
    $('.updates').click(function () {
        var id = $(this).attr('id');
        layer.open({
            type: 2,
            title: '修改权限',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '70%'],
            content: '<?=\yii\helpers\Url::to(['/authority/auth-rule/update'])?>&name='+id
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

        });
    })
</script>