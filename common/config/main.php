<?php
$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'vWLtvK9yeqtdvvEfP1l4snt8VEikNYEQ',
        ],
    ],
];
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'authManager'=>[
            'class'=>'yii\rbac\DbManager',
            'itemTable'=>'{{%auth_item}}',
            'itemChildTable'=>'{{%auth_item_child}}',
            'assignmentTable'=>'{{%auth_assignment}}',
            'ruleTable'=>'{{%auth_rule}}',
        ],
        'cos'=>[
            'class'=>'xplqcloud\cos\Cos',
            'app_id' => '1255626690',
            'secret_id' => 'AKIDqfO1Y9xGh2GYu6ewa3LArNm04xfBNhgU',
            'secret_key' => 'rY4PZYQ8fVAey78zkKjwzvoV1Misr00p',
            'region' => 'sh',
            'bucket'=>'yulongchuanmei',
            'insertOnly'=>true,
            'timeout' => 200
        ],
        'cos_gg'=>[
            'class'=>'xplqcloud\cos\Cos',
            'app_id' => '1252719796',
            'secret_id' => 'AKID2zkNq9TNDqMk3uFQRIzVwFLFzXs1ZXYN',
            'secret_key' => 'mPtBGLF1b0FI9QZXJvMbeV0Tluq041nU',
            'region' => 'sh',
            'bucket'=>'yulong',
            'insertOnly'=>true,
            'timeout' => 200
        ],
    ],
];
