<?php

namespace console\controllers;

use yii\base\Controller;

class TestController extends Controller
{

    public function actionIndex(){

        ($a)['b'] = 1;
        print_r($a);
    }
}