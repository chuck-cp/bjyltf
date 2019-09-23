<?php
use yii\widgets\ActiveForm;
\pms\assets\AppMinAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body style="padding: 10px">
<?php $this->beginBody();
if(isset($this->blocks['AppPage'])){
    $this->blocks['AppPage'];
}else{
    $this->registerJs('jQuery(document).ready(function() { App.setPage("index");  App.init(); });',\yii\web\View::POS_READY);
}
echo $content;
$this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
