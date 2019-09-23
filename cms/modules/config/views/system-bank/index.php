<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemBankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '银行管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-bank-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加银行', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'bank_name',
            [
                'label' => '银行LoGo',
                'format' => [
                    'image',
                    [
                        'width'=>'50',
                        'height'=>'auto'
                    ]
                ],
                'value' => function ($searchModel) {
                    return $searchModel->bank_logo;
                }
            ],
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url,$searchModel){
                        return Html::a('修改',$url,['update','id'=>$searchModel->id]);
                    },
                    'delete' => function($url,$searchModel){
                        return Html::a('删除',$url,['data-confirm'=>'您确定要删除此记录吗？']);
                    },

                ],
            ],

        ],
    ]); ?>
</div>
