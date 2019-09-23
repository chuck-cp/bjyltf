<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
use yii\helpers\Html;
?>

<style type="text/css">
    body, h1, h2, p,dl,dd,dt{margin: 0;padding: 0;font: 15px/1.5 微软雅黑,tahoma,arial;}
    body{background:#efefef;}
    h1, h2, h3, h4, h5, h6 {font-size: 100%;cursor:default;}
    ul, ol {list-style: none outside none;}
    a {text-decoration: none;color:#447BC4}
    a:hover {text-decoration: underline;}
    .ip-attack{width:60%; margin:10% auto 0;}
    .ip-attack dl{ background:#fff; padding:30px; border-radius:10px;border: 1px solid #CDCDCD;-webkit-box-shadow: 0 0 8px #CDCDCD;-moz-box-shadow: 0 0 8px #cdcdcd;box-shadow: 0 0 8px #CDCDCD;}
    .ip-attack dt{text-align:center;}
    .ip-attack dd{font-size:16px; color:#333; text-align:center;}
    .tips{text-align:center; font-size:14px; line-height:50px; color:#999;}
</style>

<div class ="ip-attack"><dl>
    <?php if(isset($errorMessage)):?>
        <dt style="color: red"><?php echo $errorMessage;?></dt>
    <?php else:?>
        <dt style="color: green"><?php echo $msg?></dt>
    <?php endif;?>
    <dt>
        页面自动 <a id="href" href="javascript:void(0);" onclick="onurl()">跳转</a> 等待时间： <b id="wait"><?php echo($sec);?></b>
        <input type="hidden" value="<?php echo($gotoUrl);?>" id="hidden">
    </dt></dl>
</div>
<script>
    <?php if(!isset($gotoUrl)):?>
    setInterval("history.go(-1);",<?php echo $sec;?>000);
    <?php else:?>
    setInterval("window.parent.location.href='<?php echo  $gotoUrl;?>'",<?php echo $sec;?>000);
    <?php endif;?>
    (function(){
        var wait = document.getElementById('wait'),
            href = document.getElementById('hidden').value;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
    function onurl(){
        href = document.getElementById('hidden').value;
        parent.location.href = href;
    }
</script>