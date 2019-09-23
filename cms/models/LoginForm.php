<?php

namespace cms\models;

use cms\modules\authority\models\AuthAssignment;
use cms\modules\authority\models\AuthItemChild;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码不正确！');
            }
            if($user['status']!==1){
                $this->addError($attribute, '此用户已被禁用！');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

    //权限判断
    public static function checkPermission($url)
    {
        //不需要权限限制的URL
        $noAuthUrl = ['/member/member/address','/examine/order/qm'];
        if(in_array($url,$noAuthUrl)){
            return true;
        }
        //游客登陆到首页
        if(Yii::$app->user->isGuest){
            return Yii::$app->response->redirect('index');
        }
        //空URL不执行
        if(empty($url)){
            return false;
        }
        $username = Yii::$app->user->identity->username;
        if($username === 'admin'){
            return true;
        }
        $userid = Yii::$app->user->identity->getId();
        $items = AuthAssignment::find()->where(['user_id'=>$userid])->select('item_name')->asArray()->all();
        if(empty($items)){
            return false;
        }
        foreach($items as $key=>$value){
            if($value['item_name'] == '超级管理员'){
                return true;
            }
            $actions[] = AuthItemChild::find()->where(['parent'=>$value['item_name']])->select('child')->asArray()->all();
        }
        $actionslist = [];
        foreach ($actions as $ka=>$va) {
            foreach($va as $kl=>$vl){
                $actionslist[] = $vl['child'];
            }
        }
        if(empty($actionslist)){
            return false;
        }
        if(in_array($url,$actionslist)){
            return true;
        }else{
            return false;
        }
    }
}
