<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use cms\models\SystemAddress;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
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
<style>
    .sy_rhb{ width: 100%; padding: 0 30px; height: 40%;  position: relative; /*border: 1px solid #c03;*/}
    .riqibox{ border:1px solid #ddd}
    .sy_rhb table{ border-collapse:collapse; width: 100%}
    .sy_rhb table  td{border:1px solid #ddd;height:30px; color:#181818; font-size:13px; text-align: center;}
    .sy_rhb table  td:first-child{ width:200px}
    .sy_lfrhtopb{ text-align: center; line-height: 30px; margin: 0}
    .sy_lf{ position: absolute; left: 10px; top:50%}
    .sy_rh{ position: absolute; right: 10px; top:50%}
</style>
<?php $this->beginBody(); ?>
<div class="container">
    <div class="sy_rhb">
        <div class="riqibox"">
        <table class="riqi">
            <tr><td>地区/日期</td>
                <? foreach($datepagelist as $kd=>$vd):?>
                    <td><?=Html::encode($vd)?></td><!-- 日期 -->
                <? endforeach;?>
            </tr>
            <? foreach($resule as $kr=>$vr):?>
                <tr>
                    <td><?=Html::encode(SystemAddress::getAreaNameById($kr))?></td>
                    <? foreach($vr as $krs=>$vrs):?>
                        <td><?=Html::encode($vrs)?></td>
                    <? endforeach;?>
                </tr>
            <? endforeach;?>
        </table>
        </div>
       <p class="sy_lfrhtopb">
            <a href="<?=Url::to(['/member/order/paiqi','id'=>$array['id'],'start'=>$array['start'],'end'=>$array['end'],'areapage'=>$areapage-1,'timepage'=>$datepage])?>" >上一页</a>
            <a href="<?=Url::to(['/member/order/paiqi','id'=>$array['id'],'start'=>$array['start'],'end'=>$array['end'],'areapage'=>$areapage+1,'timepage'=>$datepage])?>" >下一页</a>
            <a href="<?=Url::to(['/member/order/paiqi','id'=>$array['id'],'start'=>$array['start'],'end'=>$array['end'],'areapage'=>$areapage,'timepage'=>$datepage-1])?>" class="sy_lf" ><img src="/static/img/01-zuo.png" style="margin-left: -15px;"></a>
            <a href="<?=Url::to(['/member/order/paiqi','id'=>$array['id'],'start'=>$array['start'],'end'=>$array['end'],'areapage'=>$areapage,'timepage'=>$datepage+1])?>" class="sy_rh" ><img src="/static/img/01-you.png" style="margin-right: -22px;"></a>
       </p>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script src="/static/js/common.js"></script>
<script type="text/javascript">

</script>
