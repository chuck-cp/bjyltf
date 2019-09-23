<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\authority\models\CustomUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客服用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?/*= Html::a('添加', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '用户名',
                'value' => function($searchModel){
                    return $searchModel->username;
                }
            ],
            [
                'label' => '用户姓名',
                'value' => function($searchModel){
                    return $searchModel->name;
                }
            ],
            [
                'label' => '用户状态',
                'value' => function($searchModel){
                    return $searchModel->status==1?'启用':'禁用';
                }
            ],
            [
                'label' => '创建时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{update}  {password}  {test}  {delete} ',
                'buttons' => [

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
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $(function () {
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
                area: ['50%', '60%'],
                content: '<?=\yii\helpers\Url::to(['/authority/custom-user/create'])?>'
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
                content: '<?=\yii\helpers\Url::to(['/authority/custom-user/update'])?>&id='+id
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
                content: '<?=\yii\helpers\Url::to(['/authority/custom-user/resetpw'])?>&id='+id
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
    })
</script>
