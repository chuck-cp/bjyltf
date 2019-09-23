<?php
namespace storehouse\controllers;


use storehouse\core\BaseController;
use storehouse\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SiteController extends BaseController
{

    public $publicAction = ['login'];
    public function init(){
        $this->layout = false;
    }

    /*
     * 框架页面
     * */
    public function actionIndex(){
        return $this->render('index');
    }

    /*
     * 系统首页
     * */
    public function actionSystem(){
        return $this->render('system');
    }

    /*
     * 退出
     * */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /*
     * 登陆
     * */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if(Yii::$app->request->isAjax){
            //登陆
            if ($model->load(Yii::$app->request->post(),'')) {
                list($status,$message) = $model->login();
                return json_encode(['status'=>$status,'message'=>$message]);
            }
        }
        $this->layout = false;
        return $this->render('login');
    }

}
