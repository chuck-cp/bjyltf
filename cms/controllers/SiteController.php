<?php
namespace cms\controllers;
use cms\core\CmsController;
use common\libs\DataClass;
use common\libs\ToolsClass;
use common\libs\SystemClass;
use common\libs\SystemSource;
use cms\modules\config\models\PmsItemLanguage;
use cms\modules\config\models\PmsUserCountry;
use cms\modules\config\models\SysAddress;
use cms\modules\config\models\SysCountry;
use cms\modules\config\models\UserLanguage;
use moonland\phpexcel\Excel;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use cms\models\LoginForm;
use cms\models\User;
/**
 * Site controller
 */
class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','captcha'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout', 'index','language','password','modify','login'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }
    public function actionIndex()
    {
        //获取我的角色
        $roleName = Yii::$app->session->get('role_name');
        return $this->render('index',[
            'roleName'=>$roleName,
        ]);
    }

    public function actionLogin()
    {
        $this->layout = false;
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 修改密码
     */
    public function actionModify(){
        $id=Yii::$app->user->identity->getId();
        $data=Yii::$app->request->post();
        if(!empty($data)){
            /*if(!LoginForm::validatePassword($data['User']['old_cipher'])){
                return json_encode(['error'=>3,'msg'=>'原始密码错误']);
            }*/
            $password_hash=Yii::$app->security->generatePasswordHash($data['User']['new_password']);
            if(User::updateAll(['password_hash'=>$password_hash],['id'=>$id])){
                //清除cookies
                yii::$app->response->cookies->remove('advanced-pms');
                return json_encode(['error'=>1,'msg'=>'修改成功，退出登录']);
            }else{
                return json_encode(['error'=>2,'msg'=>'修改失败']);
            }
        }
        $model = User::findOne(['id'=>$id]);
        return $this->renderPartial('modify', [
            'model' => $model,
        ]);
    }
    public function actionLogoss(){
        echo 1234;
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    //modify user password
    public function actionPassword(){
        //$obj = new Passw
        echo 123;
    }
    //错误页面
    public function actionError()
    {
        return $this->render('error');
    }
}
