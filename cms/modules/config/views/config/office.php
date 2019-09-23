<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\authority\models\User;
use cms\modules\authority\models\AuthAssignment;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\authority\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '办事处配置';
cms\assets\AppAsset::register($this);
$this->registerJs("

");
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>
    <?php /* echo $this->render('_search', ['model' => $searchModel]); */?>-->
    <p>
        <?= Html::a('添加办事处', 'javascript:void(0);', ['class' => 'btn btn-success Createuser'])?>
    </p>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'ID',
                'value' => function($searchModel){
                    return  $searchModel->id;
                }
            ],

            [
                'label' => '办事处名称',
                'value' => function($searchModel){
                    return  $searchModel->office_name;
                }
            ],
            [
                'label' => '仓库名称',
                'value' => function($searchModel){
                    return  $searchModel->storehouse;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{rule}  {update} ',
                'buttons' => [
                    'update' => function($url,$searchModel){
                        return html::a('编辑','javascript:void(0);',['class'=>'update','id'=>$searchModel->id]);
                    },
                    /*'rule' => function($url,$searchModel){
                        return html::a('修改','javascript:void(0);',['class'=>'edituser','id'=>$searchModel->id]);
                    },*/
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.delplace').click(function(){
        var id = $(this).attr('id');
        layer.confirm('确定删除所选数据吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['del'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id},
                success:function (data) {
                    if(data.error==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！');
                }
            });
        });
    })
    $('.Createuser').click(function(){
        var pageup = layer.open({
            type: 2,
            title: '添加办事处',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/config/config/add-office'])?>'
        });return false;
    })
    $('.update').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '编辑',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/config/config/edit-office'])?>&id='+id
        });
    })
    $('.edituser').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '修改用户',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/authority/user/edituser'])?>&id='+id
        });
    })
    $('.resetpw').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '重置密码',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/authority/user/resetpw'])?>&id='+id
        });
    })
    $('.status').click(function(){
        var id = $(this).attr('id');
        var status = $(this).attr('status');
        if(status==1){
            var title='禁用账号';
            var Prompt='禁用此账号后，该账号属于冻结状态，且不可登陆，恢复此账号请再次启用！';
        }else{
            var title='启用账号';
            var Prompt='确定启用此账号？'
        }
        layer.confirm(Prompt, {
            title:title,
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['status'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'status':status},
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！');
                }
            });
        });
    })
</script>
<style>
    .action-column{width:17%}
</style>
