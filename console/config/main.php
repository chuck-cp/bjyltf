<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
    #require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@app/runtime/logs/error/app.log',
                    'logVars'=>[],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info','trace'],
                    'logFile' => '@app/runtime/logs/info/app.log',
                    'logVars'=>[],
                ],
                [
                    'class' => 'yii\log\FileTarget',//类文件
                    'categories' => ["jiemudanA"],//日志分类：即程序中使用此分类来区分记录的是哪类日志
                    'levels' => ['trace','error', 'warning','info'],//日志记录的级别
                    'logFile' => '@app/runtime/logs/jiemudanA'.date('Y-m-d').'.log',
                    'logVars' => ['_POST'],
//                    'maxFileSize' => 1000,//设置文件大小，以kB为单位
//                    'maxLogFiles' => 3,//同名文件最大数量（实际数量+1）
                ],
                [
                    'class' => 'yii\log\FileTarget',//类文件
                    'categories' => ["jiemudanB"],//日志分类：即程序中使用此分类来区分记录的是哪类日志
                    'levels' => ['trace','error', 'warning','info'],//日志记录的级别
                    'logFile' => '@app/runtime/logs/jiemudanB'.date('Y-m-d').'.log',
                    'logVars' => ['_POST'],
//                    'maxFileSize' => 1000,//设置文件大小，以kB为单位
//                    'maxLogFiles' => 3,//同名文件最大数量（实际数量+1）
                ],
            ],
        ],
    ],
    'params' => $params,
];
