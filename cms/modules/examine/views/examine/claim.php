<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\modules\shop\models\Shop;
use cms\modules\member\models\MemberAccount;
$this->title = '商家审核';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop_search">
    <?php echo $this->render('layout/shop_option',['model'=>$searchModel]);?>
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>
    <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.area').change(function () {
                var type = $(this).attr('key');
                var selObj = $('[key='+type+']').parents('.col-xs-2');
                selObj.nextAll().find('select').find('option:not(:first)').remove();
                var parent_id = $(this).val();
                //alert(parent_id);
                if(!parent_id){
                    return false;
                }
                $.ajax({
                    url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
                    type: 'POST',
                    dataType: 'json',
                    data:{'parent_id':parent_id},
                    success:function (phpdata) {
                        $.each(phpdata,function (i,item) {
                            selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                        })
                    },error:function (phpdata) {
                        layer.msg('获取失败！');
                    }
                })
            })
        })
    </script>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'member_name',
            [
                'label' => '身份类别',
                'value' => function($searchModel){
                    return $searchModel->member_inside == 1?'内部合作人':'外部合作人';
                }
            ],
            [
                'label' => '法人代表',
                'value' => function($searchModel){
                    return $searchModel->apply['apply_name'];
                }
            ],
            'name',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name;
                }
            ],

            'acreage',
            'mirror_account',
            'apply_screen_number',
            'examine_user_name',
            'create_at',
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return Shop::getStatusByNum($searchModel->status);
                }
            ],
            [
                'label' => '审核状态',
                'value' => function($searchModel){
                    return Shop::getExamineByNum($searchModel->examine_number);
                }
            ],
            [
                'label' => '店铺安装来源状态',
                'value' => function($searchModel){
                    return $searchModel->install_status==1?'外部安装':'内部安装';
                }
            ],
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        if(Yii::$app->user->identity->member_group>0){
                            return html::a('认领','javascript:void(0);',['class'=>'claim','id'=>$searchModel->id]);
                        }else{
                            return '无法认领';
                        }
                    }
                ],
            ],
        ]
    ]);?>
    <?php ActiveForm::end();?>
</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
</style>
<script type="text/javascript">
    $('.claim').click(function(){
        var id = $(this).attr('id');
        layer.confirm('确定认领？', {
            title:'商家认领',
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['confirm-claim'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id},
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