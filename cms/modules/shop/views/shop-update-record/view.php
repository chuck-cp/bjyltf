<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\ShopUpdateRecord */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Update Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-update-record-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'shop_id',
            'shop_name',
            'apply_name',
            'apply_mobile',
            'identity_card_num',
            'registration_mark',
            'company_name',
            'update_shop_name',
            'update_apply_mobile',
            'update_identity_card_num',
            'update_registration_mark',
            'update_company_name',
            'images:ntext',
            'examine_status',
            'create_user_name',
            'create_at',
        ],
    ]) ?>

</div>
