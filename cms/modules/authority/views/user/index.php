<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\authority\models\User;
use cms\modules\authority\models\AuthAssignment;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\authority\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
cms\assets\AppAsset::register($this);
$this->registerJs("

");
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'true_name',
            [
                'label' => '已关联角色',
                'value' => function($searchModel){
                    return  AuthAssignment::getUserRole($searchModel->id);
                }
            ],
            'phone',
            'email',
            //'password_hash',
            //'create_at',
            'update_at',
           // 'status',
            [
                'label' => '审核类型',
                'value' => function($searchModel){
                    return  User::getUserStatus($searchModel->status);
                }
            ],
            'member_group',
            [
                'label' => '地区',
                'value' => function($searchModel){
                    if($searchModel->areaId['area_id']==101){
                        return '全国';
                    }else if($searchModel->areaId['area_id']==0){
                        return '未设置';
                    }else{
                        return User::substrArea($searchModel->areaId['area_id']);
                    }
                }
            ],
            [
                'label' => '签到维护组',
                'value' => function($searchModel){
                    return  $searchModel->sign_team;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{rule}  {update}  {password}  {test}  {delete} {auth-area} {sign_team}',
                'buttons' => [
                    'rule' => function($url,$searchModel){
                        return html::a('关联角色','javascript:void(0);',['class'=>'upplace','id'=>$searchModel->id]);
                    },
                    'update' => function($url,$searchModel){
                        return html::a('修改','javascript:void(0);',['class'=>'edituser','id'=>$searchModel->id]);
                    },
                    'password' => function($url,$searchModel){
                        return html::a('重置密码','javascript:void(0);',['class'=>'resetpw','id'=>$searchModel->id]);
                    },
                    'test' => function($url,$searchModel){
                        if($searchModel->status==1){
                            return html::a('禁用','javascript:void(0);',['class'=>'status','id'=>$searchModel->id,'status'=>$searchModel->status]);
                        }else{
                            return html::a('启用','javascript:void(0);',['class'=>'status','id'=>$searchModel->id,'status'=>$searchModel->status]);
                        }
                    },
                    'delete' => function($url,$searchModel){
                        return html::a('删除','javascript:void(0);',['class'=>'delplace','id'=>$searchModel->id]);
                    },
                    'auth-area'=>function($url,$searchModel){
                        return html::a('关联地区','javascript:void(0);',['class'=>'auth-area','id'=>$searchModel->id]);
                    },
                    'sign_team'=>function($url,$searchModel){
                        return html::a('签到组','javascript:void(0);',['class'=>'sign_team','id'=>$searchModel->id]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<!--<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
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
            title: '创建用户',
            shadeClose: true,
            shade: 0.8,
            area: ['70%', '70%'],
            content: '<?=\yii\helpers\Url::to(['/authority/user/create'])?>'
        });
    })
    $('.upplace').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '关联角色',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/authority/user/update'])?>&id='+id
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
    $('.auth-area').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '关联地区',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/authority/user/auth-area'])?>&user_id='+id
        });
    })
    //签到组
    $('.sign_team').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '关联地区',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/authority/user/sign-team'])?>&user_id='+id
        });
    })
</script>
<style>
    .action-column{width:17%}
</style>
