<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\screen\models\ShopScreenAdvertMaintain */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Screen Advert Maintains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-screen-advert-maintain-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'mongo_id',
            'shop_id',
            'apply_name',
            'apply_mobile',
            'shop_name',
            'shop_image',
            'shop_area_id',
            'shop_area_name',
            'shop_address',
            'screen_number',
            'create_user_id',
            'create_user_name',
            'status',
            'install_member_id',
            'install_member_name',
            'install_finish_at',
            'create_at',
            'assign_at',
            'assign_time',
            'problem_description',
            'images:ntext',
        ],
    ]) ?>

</div>
