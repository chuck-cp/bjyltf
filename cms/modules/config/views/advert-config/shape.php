<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemVersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告配置';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = '';
?>
<div class="system-version-index">

<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('layout/ifram')?>
    <p>
        <?= Html::a('添加', 'javascript:void(0);', ['class' => 'btn btn-success','type'=>1]) ?>
    </p>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'shape',
            'create_user_name',
            'update_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{upplace}  {delplace}',
                'buttons' => [
                    'upplace' => function($url,$model){
                        return html::tag('span','编辑',['class'=>'upplace','id'=>$model->id]);
                    },
                    'delplace' => function($url,$model){
                        return html::tag('span','删除',['class'=>'delplace','id'=>$model->id]);
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
        var type = $(this).attr('type');
        var pg = layer.open({
            type: 2,
            title: '添加配置',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '50%'],
            content: '<?=\yii\helpers\Url::to(['/config/advert-config/configcreate'])?>&type='+type
        });
    })
</script>