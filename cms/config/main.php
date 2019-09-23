<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-pms',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'cms\controllers',
    'bootstrap' => ['log'],
    'language' =>'zh-CN',
    'modules' => [
        'config' => [
            'class' => 'cms\modules\config\config',
        ],
        'shop' => [
            'class' => 'cms\modules\shop\shop',
        ],
        'screen' => [
            'class' => 'cms\modules\screen\screen',
        ],
        'channel' => [
            'class' => 'cms\modules\channel\channel',
        ],
        'member' => [
            'class' => 'cms\modules\member\member',
        ],
        'count' => [
            'class' => 'cms\modules\count\count',
        ],
        'notice' => [
            'class' => 'cms\modules\notice\notice',
        ],
        'feedback' => [
            'class' => 'cms\modules\feedback\feedback',
        ],
        'sysconfig' => [
            'class' => 'cms\modules\sysconfig\sysconfig',
        ],
        'sysfunc' => [
            'class' => 'cms\modules\sysfunc\sysfunc',
        ],
        'systemstartup' => [
            'class' => 'cms\modules\systemstartup\systemstartup',
        ],
        'withdraw' => [
            'class' => 'cms\modules\withdraw\withdraw',
        ],
        'examine' => [
            'class' => 'cms\modules\examine\examine',
        ],
        'ledmanage' => [
            'class' => 'cms\modules\ledmanage\ledmanage',
        ],
        'adverting' => [
            'class' => 'cms\modules\adverting\adverting',
        ],
        'account' => [
            'class' => 'cms\modules\account\account',
        ],
        'authority' => [
            'class' => 'cms\modules\authority\authority',
        ],
        'schedules' => [
            'class' => 'cms\modules\schedules\schedules',
        ],
        'report' => [
            'class' => 'cms\modules\report\report',
        ],
        'sign' => [
            'class' => 'cms\modules\sign\sign',
        ],
        'guest' => [
            'class' => 'cms\modules\guest\guest',
        ],
    ],
    'controllerMap' => [
        'upload' => 'yidashi\\uploader\\actions\\UploadController',
    ],
    'components' => [
        'kafka' => [
            'class' => '',
            'topic_liset' => '',
        ],
        'request' => [
            'csrfParam' => '_csrf-pms',
        ],
        'user' => [
            'identityClass' => 'cms\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-pms', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the pms
            'name' => 'advanced-pms',
        ],
//        'sentry' => [
//            'class' => 'mito\sentry\Component',
//            'dsn' => 'http://53b52eb8180f4228bcb1e86fcf472f66@10.240.0.72:9000/5',
//            'environment' => 'staging',
//            'jsNotifier' => false,
//            'jsOptions' => [
//                'whitelistUrls' => [
//
//                ],
//            ],
//        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
//                [
//                    'class' => 'mito\sentry\Target',
//                    'levels' => ['error','warning'],
//                    'except' => [
//                        'yii\web\HttpException:404',
//                    ],
//                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@app/runtime/logs/error/app.log',
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info','trace'],
                    'logFile' => '@app/runtime/logs/info/app.log',
                    'logVars' => [],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,  //开启美化url配置,默认关闭
            'enableStrictParsing' => false, //不启用严格解析，默认不启用.如果设置为true,则必须建立rules规则，且路径必须符合一条以上规则才允许访问
            'showScriptName' => false, //隐藏index.php
            'rules' => [
                // http://frontend.com/site/index 重写为  http://frontend.com/site
                '<controller:\w+>/'=>'<controller>/index',
                // http://frontend.com/site/view?id=1 重写为 http://frontend.com/site/1
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                // http://frontend.com/site/ceshi?id=123 重写为  http://frontend.com/site/ceshi/123
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
