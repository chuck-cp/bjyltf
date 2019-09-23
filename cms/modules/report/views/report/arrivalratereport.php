<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $model app\modules\member\models\Order */

/*$this->title = $model->id;*/
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-device-index">
    <?php echo $this->render('layout/tab',['id'=>$id]);?>
    <table class="table table-striped table-bordered" style="margin-top:10px;">
        <thead>
        <tr>
            <!--                <th><input id="check" type="checkbox" class="select-on-check-all" name="selection_all" value="1"></th>-->
            <th>ID</th>
            <th>name</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($asArr['data'] as $k=>$v):?>
            <tr>
                <td><?echo $v['id'];?></td>
                <td><?echo $v['name'];?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div style="text-align: center;">
        <?= LinkPager::widget([
            'pagination' => $asArr['pages'],
            'nextPageLabel' => '下一页',
            'prevPageLabel' => '上一页',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
        ]); ?>
    </div>
</div>
