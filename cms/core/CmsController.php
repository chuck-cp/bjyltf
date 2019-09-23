<?php

namespace cms\core;

use cms\models\LoginForm;
use Yii;
use yii\web\Controller;

/**
 * Pms继承的控制器
 */
class CmsController extends Controller
{
    public $examineListArr = [];
    public function checkSaveResult($result,$url=''){
        $message = '';
        if(is_array($result)){
            $message = $result['message'];
            $result = $result['result'];
        }
        if($result){
            echo $this->showMessage('操作成功!'.$message,'success',['url'=>$url]);
        }else{
            echo $this->showMessage('操作失败!'.$message,'error',['url'=>$url]);
        }
    }
    public function showMessage($message = null, $title = '提示',$params=[])
    {
        if ($message === null)
        {
            $message = '权限不足，无法进行此项操作';
        }
        if($title == 'error' || $title == 'success'){
            $params['close_time'] = 3;
        }
        if(is_array($message)){
            $message = Json::encode($message);
        }
        $params=array_merge(['title'=>$title,'message'=>$message],$params);
        $this->layout =false;
        return $this->render('//site/message',$params);
    }
    public function beforeAction($action){
        //如果未登录，则直接返回
        if(Yii::$app->user->isGuest){
            $this->goHome();
            return false;
        }
        $controller = $action->controller->id;
        $actionName = $action->id;
        $moduleName =$action->controller->module->id;
        if(LoginForm::checkPermission('/'.$moduleName.'/'.$controller.'/'.$actionName)){
            return true;
        }
        $this->layout = false;
        echo $this->render('//public/auth');
    }

    /**
     * 通用成功跳转
     * @param mixed  $msg    提示信息
     * @param string $url    跳转的 URL 地址
     * @param int    $sec   跳转等待时间
     * @return Ambigous <string, string>
     */
    public function success($msg, $url= [] ,$sec = 3){
        if(empty($url)){
            $url='javascript:history.back(-1)';
        }else{
            $url= \yii\helpers\Url::toRoute($url);
        }
        return $this->renderPartial('//public/msg',['gotoUrl'=>$url,'sec'=>$sec,'msg'=>$msg]);
    }
    /**
     * 通用错误跳转
     * @param string $msg 错误提示信息
     * @param string $url 跳转的 URL 地址
     * @param number $sec 跳转等待时间
     * @return Ambigous <string, string>
     */
    public function error($msg, $url= [] ,$sec = 3){
        if(empty($url)){
            $url='javascript:history.back(-1)';
        }else{
            $url= \yii\helpers\Url::toRoute($url);
        }
        return $this->renderPartial('//public/msg',['gotoUrl'=>$url,'errorMessage'=>$msg,'sec'=>$sec]);
    }
}
