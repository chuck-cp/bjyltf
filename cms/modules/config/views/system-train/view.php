<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
cms\assets\AppAsset::register($this);
$this->registerCssFile('/static/css/tcplayer/tcplayer.css');
$this->registerJsFile('/static/js/tcplayer/videojs-ie8.js');
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="system-train-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2 style="text-align: center;"><?php echo $model->name;?></h2>
    <?php if($model->type==1):?>
        <div style="margin:0 auto;">
            <?php echo $model->content?>
        </div>
    <?php else:?>
    <div style="margin:0 auto; width:780px">
        <video  controls  width="780" height="560" ><source src=<?php echo $model->content;?> type="video/mp4" />  </video>
    </div>
    <?php endif;?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<script src="//imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.min.js"></script>
<script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>
<style>
.system-train-view { padding: 0 25px}
.system-train-view p{ line-height: 25px;}
</style>