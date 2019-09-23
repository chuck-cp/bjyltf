<?php
namespace storehouse\core;

use yii\filters\AccessControl;
use yii\web\Controller;

class BaseController extends Controller{

    public $publicAction = [];
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'except' => [],
                'rules' => [
                    [
                        'actions' => $this->publicAction,
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}