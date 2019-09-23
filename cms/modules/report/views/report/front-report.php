<?php
use yii\helpers\Html;
use yii\helpers\Url;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
$this->beginBlock('AppPage');
//$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<style type="text/css">
    .list-group-item span{
        display: inline-block;
        width:25%;
    }
    .middle{
        text-align: center;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item">
                    <span>
                        播放总量：<?=html::encode($playTotal)?>
                    </span>
                    <span style="width: 15%;">
                        基础系数：
                    </span>
                    <span><input class="play" type="text"></span>
                </li>
                <li class="list-group-item">
                    <span>
                        店铺总量：<?=html::encode($shopTotal)?>
                    </span>
                    <span style="width: 15%;">
                        基础系数：
                    </span>
                    <span><input class="shop" type="text"></span>
                </li>
            </ul>
        </div>
        <div class="col-md-5 middle" style="height: 92px;padding-top: 30px;text-align: left;">
                <?echo Html::button('预览',['id' => 'preview'])?>
                <?echo Html::button('提交',['id' => 'commit'])?>
        </div>
    </div>
    <h4>修改提示：</h4>
    <ul class="list-group">
        <li class="list-group-item">1. 默认系数为1，为真实数据。修改系数则改变对应数据。</li>
        <li class="list-group-item">2. 修改播放总量会影响播放概况、地区播放情况、日期播放情况 三个模块的报告数据。</li>
        <li class="list-group-item">3. 修改店铺总量会影响覆盖理发店、新增屏幕数两个模块的报告数据。</li>
        <li class="list-group-item">4. 修改店铺和播放总量均会对播放率造成影响，请仔细核对数据。</li>
    </ul>
    <iframe frameborder=0 width=170 height=100 marginheight=0 marginwidth=0 scrolling=no src="<?=Url::to(['report-detail','id'=>1])?>"></iframe>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>

<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //提交
        $("#commit").click(function () {
            var shopNum = $('.shop').val();
            var playNum = $('.play').val();
            if(!checkNum(shopNum) || !checkNum(playNum)){
                layer.msg('请输入正确的倍数！');
                return false;
            }
            var id = '<?echo $id?>';
            var srcs = $("iframe").attr('src');
            alert(srcs);
            $.ajax({
                url: '<?=Url::to(['modify-save'])?>',
                type: 'POST',
                dataType: 'json',
                data:{'playNum':playNum,'shopNum':shopNum,'id':id,'src':srcs},
                success:function (phpdata) {
                    
                }
            })
        })
        //预览
        $("#preview").click(function () {
            var shopNum = $('.shop').val();
            var playNum = $('.play').val();
            if(!checkNum(shopNum) || !checkNum(playNum)){
                layer.msg('请输入正确的倍数！');
                return false;
            }
            //刷新iframe
            sendZch(2,shopNum,playNum);
        })
        function checkNum($num) {
            var reg = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;
            if(!reg.test($num)){
                return false;
            }
            return true;
        }
        function sendZch(id,shopNum,playNum){
            $("iframe").attr("src", '/index.php?r=report%2Freport%2Freport-detail&id='+id+'&shopNum='+shopNum+'&playNum='+playNum);
        }
    })
</script>