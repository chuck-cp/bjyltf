<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \cms\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'DMS 登录|数据管理平台';

$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('static/css/cloud-admin.css');
$this->registerCssFile('static/css/font-awesome.min.css');

$this->registerCssFile('static/js/bootstrap-daterangepicker/daterangepicker-bs3.css');
$this->registerCssFile('static/js/uniform/css/uniform.default.min.css');
$this->registerCssFile('static/css/animatecss/animate.min.css');
$this->registerCssFile('static/js/select2/select2.min.css');
#$this->registerCssFile('static/font-awesome/css/font-useso.css');

//$this->registerJsFile('static/js/jquery/jquery-2.0.3.min.js');
//$this->registerJsFile('static/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js');
//$this->registerJsFile('static/bootstrap-dist/js/bootstrap.min.js');
//$this->registerJsFile('static/js/uniform/jquery.uniform.min.js');
//$this->registerJsFile('static/js/backstretch/jquery.backstretch.min.js');
//$this->registerJsFile('static/js/script.js');

$this->beginPage() ?>
    <!DOCTYPE html>
    <!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
    <!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
    <!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>B2C | <?= Html::encode($this->title) ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <?php $this->head() ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- PAGE -->
<section id="page">
    <!-- LOGIN -->
    <section id="login_bg" class="visible">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-box">
                        <h2 class="bigintro">系统登录</h2>
                        <div class="divide-40"></div>
                        <?php $form = ActiveForm::begin([ 'id' => 'da-login-form']); ?>
                        <div class="form-group">
                            <label for="exampleInputEmail1">登录邮箱</label>
                            <i class="fa fa-envelope"></i>
                            <?php echo Html::activeTextInput($model, 'username',['class'=>'form-control','placeholder'=>'登录账号'])?>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">密 码</label>
                            <i class="fa fa-lock"></i>
                            <?php echo Html::activeTextInput($model, 'password',['class'=>'form-control','type'=>'password','placeholder'=>'登录密码'])?>
                        </div>
                        <? if(isset($model->errors['password'][0])):?>
                            <p style="color: darkred;">
                                <?=Html::encode($model->errors['password'][0])?>
                            </p>
                        <? endif;?>
<!--                        <div class="form-group">-->
<!--                            <label for="exampleInputPassword1">语 言</label>-->
<!--                            --><?php //echo Html::activeDropDownList($model, 'language',$model->getSysLanguage(),['class'=>'form-control-login','id'=>'e3','placeholder'=>'请选择语言'])?>
<!--                        </div>-->
                        <div>
                            <label class="checkbox"> </label>
                            <button type="submit" class="btn btn-danger">登  录</button>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/LOGIN -->
</section>
<!--/PAGE -->



<script src="static/js/jquery/jquery-2.0.3.min.js"></script>
<!-- JQUERY UI-->
<script src="static/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
<!-- BOOTSTRAP -->
<script src="static/bootstrap-dist/js/bootstrap.min.js"></script>

<script type="text/javascript" src="static/js/select2/select2.min.js"></script>
<script type="text/javascript" src="static/js/typeahead/typeahead.min.js"></script>
<!-- AUTOSIZE -->
<script type="text/javascript" src="static/js/autosize/jquery.autosize.min.js"></script>
<!-- COUNTABLE -->
<script type="text/javascript" src="static/js/countable/jquery.simplyCountable.min.js"></script>

<!-- UNIFORM -->
<script type="text/javascript" src="static/js/uniform/jquery.uniform.min.js"></script>
<!-- BACKSTRETCH -->
<script type="text/javascript" src="static/js/backstretch/jquery.backstretch.min.js"></script>
<!-- CUSTOM SCRIPT -->
<script src="static/js/script.js"></script>

<script>
    jQuery(document).ready(function() {
        App.setPage("login_bg");  //Set current page
        App.init(); //Initialise plugins and elements
    });
</script>
<script type="text/javascript">
    function swapScreen(id) {
        jQuery('.visible').removeClass('visible animated fadeInUp');
        jQuery('#'+id).addClass('visible animated fadeInUp');
    }
</script>

</body>



</html>
<?php $this->endPage();?>