<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace cms\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot/';
    public $baseUrl = '@web/';
    public $css = [
		'static/css/cloud-admin.css',
		'static/css/viewer.min.css',
		'static/css/themes/night.css',
		'static/css/responsive.css',
        'static/css/common.css',
		'static/font-awesome/css/font-awesome.min.css',
		'static/css/animatecss/animate.min.css',
		'static/js/bootstrap-daterangepicker/daterangepicker-bs3.css',
		'static/js/jquery-todo/css/styles.css',
		'static/js/fullcalendar/fullcalendar.min.css',
		'static/js/gritter/css/jquery.gritter.css',
		'static/js/datepicker/themes/default.min.css',
        'static/js/datepicker/themes/default.time.min.css',
        'static/js/colorpicker/css/colorpicker.min.css',
        'static/js/jquery-ui-1.10.3.custom/css/custom-theme/jquery-ui-1.10.3.custom.min.css',
    ];
    public $js = [
		'static/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js',
		'static/bootstrap-dist/js/bootstrap.min.js',
		'static/js/bootstrap-daterangepicker/moment.min.js',
		'static/js/bootstrap-daterangepicker/daterangepicker.min.js',
		'static/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js',
		'static/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js',
		'static/js/jQuery-slimScroll-1.3.0/slimScrollHorizontal.min.js',
		'static/js/jQuery-BlockUI/jquery.blockUI.min.js',
		'static/js/sparklines/jquery.sparkline.min.js',
		'static/js/jquery-easing/jquery.easing.min.js',
		'static/js/easypiechart/jquery.easypiechart.min.js',
		'static/js/flot/jquery.flot.min.js',
		'static/js/flot/jquery.flot.time.min.js',
		'static/js/flot/jquery.flot.selection.min.js',
		'static/js/flot/jquery.flot.resize.min.js',
		'static/js/flot/jquery.flot.pie.min.js',
		'static/js/flot/jquery.flot.stack.min.js',
		'static/js/flot/jquery.flot.crosshair.min.js',
		'static/js/jquery-todo/js/paddystodolist.js',
		'static/js/timeago/jquery.timeago.min.js',
		'static/js/fullcalendar/fullcalendar.min.js',
		'static/js/jQuery-Cookie/jquery.cookie.min.js',
        'static/js/jquery-raty/jquery.raty.min.js',
        'static/js/script.js',
        'static/js/common.js',
        'static/layer/layer.js',
        'static/js/magic-suggest/magicsuggest-1.3.1-min.js',
        "static/js/datepicker/picker.js",
        "static/js/datepicker/picker.date.js",
        "static/js/datepicker/picker.time.js",
        "static/js/colorpicker/js/bootstrap-colorpicker.min.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
