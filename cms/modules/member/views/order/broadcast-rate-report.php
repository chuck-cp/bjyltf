<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;

/*$this->title = $model->id;*/
$this->params['breadcrumbs'][] = ['label' => '广告查询', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-device-index">
    <?php echo $this->render('layout/tab',['id'=>$id]);?>
    <div class="order-search">
        <?php $form = ActiveForm::begin([
            'action' => ['broadcast-rate-report','id'=>$id],
            'method' => 'get',
        ]); ?>
        <div class="container">
            <br />
            <div class="row">
                <div class="col-md-3">
                    <?=$form->field($searchModel,'shop_id')->textInput(['class'=>'form-control fm'])->label('店铺ID:')  ?>
                </div>
                <div class="col-md-3">
                    <?=$form->field($searchModel,'shop_name')->textInput(['class'=>'form-control fm'])->label('店铺名称：')  ?>
                </div>
                <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
               <!-- --><?/*=  html::submitButton('导出列表',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]); */?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="aa">
    <table class="table table-striped table-bordered" style="margin-top:10px;">
        <thead>
        <tr>
            <th width="15%">店铺ID</th>
            <th >地区</th>
            <th>街道</th>
            <th>店铺</th>
            <?foreach ($dateArr as $v):?>
                <th><?echo $v?></th>
            <?endforeach;?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($asArr['data'] as $k=>$v):?>
            <tr>
                <td><?echo $v['shop_id'];?></td>
                <td><?echo $v['area_name'];?></td>
                <td><?echo $v['street_name'];?></td>
                <td><?echo $v['shop_name'];?></td>
                <?foreach ($dateArr as $vv):?>
                   <td>
                       <?if(!empty($BroadcastData)):?>
                           <?if(isset($BroadcastData[$v['order_id'].$v['shop_id'].str_replace("-",'',$vv)])):?>
                               <?echo $BroadcastData[$v['order_id'].$v['shop_id'].str_replace("-",'',$vv)]?>/<?echo $v['ShouldNumber'] ;?>&nbsp;&nbsp;&nbsp;<?echo   sprintf("%.4f",$BroadcastData[$v['order_id'].$v['shop_id'].str_replace("-",'',$vv)]/$v['ShouldNumber'])*100?>%
                           <?endif;?>
                       <?endif;?>
                   </td>
                <?endforeach;?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    </div>
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
<style>
    .aa{
        white-space: nowrap; overflow: hidden; overflow-x: scroll; -webkit-backface-visibility: hidden; -webkit-overflow-scrolling: touch;
    }
</style>
