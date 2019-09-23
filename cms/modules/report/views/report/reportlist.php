<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use cms\models\SystemAddress;
use \yii\widgets\ActiveForm;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
    table{text-align:center;}
    table th{text-align:center;}
    .btn-primary{float: right;margin: 0px 0px 10px;}
');
$this->title = '播放报告';
$this->params['breadcrumbs'][] = $this->title;
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="" style="margin: 5px;">
    <h4>投放信息：</h4>
    <? ActiveForm::begin([
//    'action' => [],
        'method' => 'post',
    ])?>
    <?=  html::submitButton('导出列表',['class' => 'btn btn-primary', 'name'=>'daochu', 'value'=>1]); ?>
    <?php ActiveForm::end(); ?>
    <div class="container" style="overflow-x: auto; overflow-y: auto; width:97%;">
    <table class="table table-bordered" style="table-layout:auto;width:95%;">
        <thead>
        <tr>
            <th style="min-width:199px;">投放地区/播放量</th>
            <? foreach($datelist as $kdate=>$vdate):?>
            <th><?=Html::encode($vdate)?></th>
            <? endforeach;?>
            <th style="">播放总量</th>
            <th style="">应播总量</th>
            <th style="">播放率%</th>
        </tr>
        </thead>
        <? foreach($srr as $key=>$value):?>
        <tr>
            <td style="max-width: 201px;">
                <?=Html::encode(SystemAddress::getAreaNameById($value['area_id']))?>
            </td>
            <? foreach(explode(',',$value['data_list']) as $kl=>$vl):?>
                <td>
                    <?=Html::encode($vl)?>
                </td>
            <? endforeach;?>
            <td style="min-width: 81px;">
                <?=Html::encode($value['play_total'])?>
            </td>
            <td style="min-width: 81px;">
                <?=Html::encode($value['should_total'])?>
            </td>
            <td style="min-width: 81px;">
                <?=Html::encode($value['percentage'])?>
            </td>
        </tr>
        <? endforeach;?>
        <? if((empty($_GET['page'])?1:$_GET['page']) == $lastpage): ?>
        <tr>
            <td style="min-width:199px;">合计<?=Html::encode($pages->totalCount)?>街道</td>
                <? foreach(explode(',',$newtotal['data_list']) as $kt=>$vt):?>
                <td>
                    <?=Html::encode($vt)?>
                </td>
                <? endforeach;?>
                <td>
                    <?=Html::encode($newtotal['play_total'])?>
                </td>
                <td>
                    <?=Html::encode($newtotal['should_total'])?>
                </td>
                <td>
                    <?=Html::encode($newtotal['percentage'])?>
                </td>
        </tr>
        <? endif;?>
    </table>
    <div style="text-align: center;">
        <?= LinkPager::widget([
            'pagination' => $pages,
            'nextPageLabel' => '下一页',
            'prevPageLabel' => '上一页',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
        ]); ?>
    </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">

</script>