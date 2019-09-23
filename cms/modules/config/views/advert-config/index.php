<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\AdvertConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Advert Configs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advert-config-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Advert Config', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'shape',
            'content',
            'type',
            'update_at',
            //'create_user_id',
            //'create_user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
