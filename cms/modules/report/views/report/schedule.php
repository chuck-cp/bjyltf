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
$this->title = '播放排期';
$this->params['breadcrumbs'][] = $this->title;
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
    <style type="text/css">
        #tableDiv{width:100%;overflow-x:auto; margin:0 auto; border:1px #ccc solid}
        #tableDiv table{color:#000;text-align:center;border-collapse:collapse;font-size:12px;}
        #tableDiv table td{border:1px solid #ddd;border-top:0px;border-left:0px;height:30px; padding:0 10px; min-width:80px;}
        #tableDiv table td.dizhi{min-width:200px;}
        /*火狐兼容描边*/
        .firefoxbm{position:relative;border-right:1px solid #ddd;}
        .firefoxbm:after{ height:1px; content:""; width:100%; border-bottom:1px solid #ddd;position:absolute; bottom:-1px; right:0px; z-index:10;}
        .firefoxbm:before{height:100%;content: '';width:1px;border-right: 1px solid #ddd;position: absolute;top:0px;right:-1px;z-index:5}
    </style>
</head>
<body>
<?php $this->beginBody(); ?>
<h4>投放信息：</h4>
<? ActiveForm::begin([
//    'action' => [],
    'method' => 'post',
])?>
<?=  html::submitButton('导出列表',['class' => 'btn btn-primary', 'name'=>'daochu', 'value'=>1]); ?>
<?php ActiveForm::end(); ?>
<div class="container" style="overflow-x: auto; overflow-y: auto; height: 90%; width:95%;">
    <div id="tableDiv">
        <table>
            <tr>
                <td class="dizhi">投放地区/排期</td>
                <? foreach($datelist as $kdate=>$vdate):?>
                    <td><?=Html::encode($vdate)?></td>
                <? endforeach;?>
            </tr>
            <? foreach($srr as $key=>$value):?>
                <tr>
                    <td class="dizhi"">
                        <?=Html::encode(SystemAddress::getAreaNameById($value))?>
                    </td>
                    <? foreach($datelist as $kdate=>$vdate):?>
                        <td>
                            <?=Html::encode(empty($newdate[$value][$vdate])?'无排期':'播放')?>
                        </td>
                    <? endforeach;?>
                </tr>
            <? endforeach;?>
        </table>
    </div>
    <?= LinkPager::widget([
        'pagination' => $pages,
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '上一页',
        'firstPageLabel' => '首页',
        'lastPageLabel' => '尾页',
    ]); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function (){
        $("#tableDiv").find("table tr").each(function() {
            $(this).find("td").eq(0).css({"background-color":"#e9f8ff"});
        });
    })
    $("#tableDiv").scroll(function(){//给table外面的div滚动事件绑定一个函数
        var left=$("#tableDiv").scrollLeft();//获取滚动的距离
        var trs=$("#tableDiv table tr");//获取表格的所有tr
        trs.each(function(i){//对每一个tr（每一行）进行处理
            $(this).children().eq(0).css({"position":"relative","top":"0px","left":left,"background-color":"#e9f8ff"});
            $(this).children().eq(0).addClass("firefoxbm");
        });
    });
</script>