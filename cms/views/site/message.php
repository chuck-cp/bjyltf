<?php
use yii\widgets\ActiveForm;
if(isset($close_time)){
$close_time = 3;
\cms\assets\AppMinAsset::register($this);
$this->title = $title;
$this->registerJs("window.onkeyup=function(ev){var key=ev.keyCode||ev.which; if(key==27){parent.layer.closeAll('iframe'); }}");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody(); ?>
<script>
    function closePage(){
        var nowTime = $('#closeTime').html();
        if(nowTime == 1){
            var url = '<?=isset($url) ? $url : ''?>';
            if(url !== ''){
                parent.location.href = url;
            }else{
                parent.location.reload();
            }
        }else{
            $('#closeTime').html(nowTime - 1);
        }
    }
    function closePageLoop(){
        setInterval(closePage,1000);
    }
</script>
<div class="juhe-default-index" style="padding: 10px">
    <?if($title == 'success'){?>
        <div class="alert alert-block alert-success fade in">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true"></a>
            <p></p><h4><i class="fa fa-heart"></i> <?=$message?></h4>
            <?='页面将在<span id="closeTime">'.$close_time.'</span>后自动关闭!<script>closePageLoop();</script>';?><p></p>
        </div>
    <?}else{?>
        <div class="alert alert-block alert-danger fade in">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true"></a>
            <h4><i class="fa fa-times"></i><?=$message?></h4>
            <p>
                <?= '页面将在<span id="closeTime">'.$close_time.'</span>后自动关闭!<script>closePageLoop();</script>';?>
            </p>
        </div>
    <?}?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
}else{
    echo $message;
}?>
