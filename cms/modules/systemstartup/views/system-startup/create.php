<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\systemstartup\models\SystemStartup */

$this->title = '新建启动页';
$this->params['breadcrumbs'][] = ['label' => '启动页管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('static/js/jquery-ui-1.10.3.custom/css/custom-theme/jquery-ui-1.10.3.custom.min.css');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->registerJs("
    $(function(){
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            autoclose: true,
            minView: 0,
            minuteStep:1
        });
    })
");
?>
<div class="system-startup-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
