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
class AppMinAsset extends AssetBundle
{
    public $basePath = '@webroot/';
    public $baseUrl = '@web/';
    public $css = [
		'static/css/cloud-admin.css',
        'static/js/jquery-ui-1.10.3.custom/css/custom-theme/jquery-ui-1.10.3.custom.min.css',
    ];
    public $js = [
        'static/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js',
        'static/js/script.js',
        'static/js/common.js',
        'static/layer/layer.js',
        'static/js/magic-suggest/magicsuggest-1.3.1-min.js',
        "static/js/datepicker/picker.js",
        "static/js/datepicker/picker.date.js",
        "static/js/datepicker/picker.time.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
