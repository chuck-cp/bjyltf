<?php

/* @var $this \yii\web\View */
/* @var $content string */

use cms\assets\AppAsset;
use pms\modules\config\models\PmsItemLanguage;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
AppAsset::register($this);
$this->registerJs(";!function(){ layer.ready(function(){layer.photos({shade:0.01,shift:0,photos: '.layerImage',area:'auto',maxWidth:'800px'});});}();");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="//s0.zdlao.com/favicon.ico" type="image/x-icon" />
<?= Html::csrfMetaTags() ?>
<title>
<?= Html::encode($this->title) . ' - 广告业务管理平台'?>
</title>
<?php $this->head() ?>
</head>
<body style="background-color: #FFFFFF">
<?php $this->beginBody(); ?>
<?php
if(isset($this->blocks['AppPage'])){
    $this->blocks['AppPage'];
}else{
    $this->registerJs('jQuery(document).ready(function() { App.setPage("index");  App.init(); });',\yii\web\View::POS_READY);
}
?>
<!-- HEADER -->
<header class="navbar clearfix" id="header">
    <div class="container">
        <div class="navbar-brand">
            <a href="javascript:void(0);">

            </a>
            <div id="sidebar-collapse" class="sidebar-collapse btn">
                <i class="fa fa-bars"
                   data-icon1="fa fa-bars"
                   data-icon2="fa fa-bars" ></i>
            </div>
        </div>
        <!-- NAVBAR LEFT -->
        <ul class="nav navbar-nav pull-left hidden-xs" id="navbar-left">
            <?=\cms\core\widgets\TopMenu::widget()?>
        </ul>
        <!-- /NAVBAR LEFT -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <ul class="nav navbar-nav pull-right">
            <!-- BEGIN USER LOGIN DROPDOWN -->
            <?php if(!Yii::$app->user->isGuest){
                ?>
                <li class="dropdown user" id="header-user" style="width: 166px; margin-top: 5px">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="username">欢迎您:<?=Yii::$app->user->identity->username?></span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                           <!-- --><?/*= Html::a('<i class="fa fa-cog "></i> 修改密码',['/site/modify','id'=>Yii::$app->user->identity->getId()])*/?>

                            <a class="password"><i class="fa fa-cog "></i>&nbsp;修改密码</a>
                        </li>
                        <li>
                            <?= Html::a('<i class="fa fa-power-off"></i> 退出系统',['/site/logout'],['data-method' => 'post'])?>
                        </li>
                    </ul>
                </li>

            <?php }?>
        </ul>
        <!-- END TOP NAVIGATION MENU -->
    </div>
</header>
<div style="height: 30px; line-height: 30px; margin-bottom: 10px">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : ['开始'],
    ]) ?>
</div>
<!--/HEADER -->

<section id="page">
    <div id="sidebar" class="sidebar">
        <div class="sidebar-menu nav-collapse">
            <ul>
                <?=\cms\core\widgets\LeftMenu::widget()?>
        </div>
    </div>

    <div id="main-content">

        <div class="container">
            <div class="row">
                <div id="content" class="col-lg-12">
                    <!-- PAGE HEADER-->
<!--                    <div class="row">-->
<!--                        <div class="col-sm-12">-->
<!--                            <div class="page-header">-->
<!--                                <div class="clearfix description">-->
<!--                                    <div class="description">本页功能基本描述信息</div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <!-- /PAGE HEADER -->
                    <!-- NEW ORDERS & STATISTICS -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?=$content?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /NEW ORDERS & STATISTICS -->

                </div><!-- /CONTENT-->
            </div>
        </div>
    </div>
</section>
<!--/PAGE -->

<?php $this->endBody() ?>
<script>
	var wHeight=$(window).height();
    $('#sidebar').css('minHeight',wHeight);
    //layer.config({extend: 'extend/layer.ext.js'});
</script>
</body>
</html>
<?php $this->endPage(); ?>
<script>
    $('.password').click(function(){
        var pageup = layer.open({
            type: 2,
            title: '修改密码',
            shadeClose: true,
            shade: 0.8,
            area: ['40%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/site/modify'])?>'
        });
    })
</script>