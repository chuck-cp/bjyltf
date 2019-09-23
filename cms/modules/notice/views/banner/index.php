<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\notice\models\search\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Banner管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?=Html::a('添加banner',['create'], ['class' => 'btn btn-success'])?>
</p>
<?php echo $this->render('layout/tab',['action'=>$action])?>
<div class="system-banner-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <form id="myform">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
           /* ['class' => 'yii\grid\SerialColumn'],*/
            'id',
            [
                'label' => '图片',
                'format' => [
                    'image',
                    [
                        'width'=>'auto',
                        'height'=>'100'
                    ]
                ],
                'value' => function ($searchModel) {
                    return $searchModel->image_url;
                }
            ],

            'link_url:url',
           // 'sort',

            [
                'header' => '排序',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function($url,$searchModel){
                        return Html::input('text',"sort[$searchModel->id]",$searchModel->sort);
                    }
                ],
            ],
            [
                'label'=> '类型',
                'value' => function($searchModel){
                    return $searchModel->type == 1 ? '首页banner' : '广告页banner';
                }
            ],
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {view} {delete}',
                'buttons' => [
                    'update' => function($url,$searchModel){
                        return Html::a('编辑',$url);
                    },
                    'view' => function($url,$searchModel){
                        return Html::a('查看',$url);
                    },
                    'delete' => function($url,$searchModel){
                        return Html::a('删除',$url,['data-confirm'=>'您确定要删除此记录吗？']);
                    },

                ],
            ]
        ],
    ]); ?>
        <a class="btn btn-primary" id="submitsort"> 排序</a>
    </form>
</div>
<style>
    table th, table td {
        text-align: center;
        vertical-align: middle!important;
    }
    input{
        border:solid 1px #ddd;
        width:20%;
        height:25px;
        text-align: center;
        border-radius:5px;
    }

</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script>
    $(function(){
        $('#submitsort').click(function(){
            var data=$('#myform').serialize();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['sort'])?>',
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
    })

</script>

