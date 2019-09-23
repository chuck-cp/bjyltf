<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

\cms\assets\AppAsset::register($this);
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
<div class="system-notice-view content">
    <table class="table table-striped table-bordered" style="text-align: center">
        <tr>
            <td style="font-size: 26px">
                <?php echo $model->title;?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $model->create_at;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php if($model->top==1):?>
                    推送至首页
                <?php else:?>
                    不推送
                <?php endif;?>
            </td>
        </tr>
        <tr><td>链接地址：https://wap.bjyltf.com/message/<?=$model->id?></td></tr>

    </table>
    <div class="conter" style="">
        <?php echo $model->content;?>
    </div>
</div>
<style>
   /* p{text-align:left;text-indent: 2em;}*/
    p{text-align:left;text-indent: 2em;line-height: 30px;margin: 15px 0;display:block;}
    img{display:block;margin: 0 auto;}
    .conter{width: 80%;margin:0 auto;}
</style>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
