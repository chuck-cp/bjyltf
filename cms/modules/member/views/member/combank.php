<?php

$this->title = '绑定银行卡';
$this->params['breadcrumbs'][] = ['label' => '对公账户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop_search">
    <?php echo $this->render('layout/tab',['model'=>$model]);?>
    <?php echo $this->render('layout/barkclass',['model'=>$model]);?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'create_at',
            [
                'label' => '开户银行',
                'value' => function($model){
                    return $model->systemBank['bank_name'];
                }
            ],
            [
                'label' => '开户网点',
                'value' => function($model){
                    return $model->bank_name;
                }
            ],
            'number',
            'name',
            [
                'label' => '身份证',
                'value' => function($model){
                    return $model->membreInfo['id_number'];
                }
            ],
            'mobile',
        ],
    ]); ?>
</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
</style>