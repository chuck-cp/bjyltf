<?php

namespace storehouse\models;

use Yii;
use yii\base\Model;


class LoginForm extends Model
{
    public $username;
    public $password;


    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
        ];
    }


    public function login()
    {
        if($this->hasErrors()){
            return false;
        }
        $userModel = User::findByUsername($this->username);
        if (!$userModel || !$userModel->validatePassword($this->password)) {
            return [401,'用户名或密码不正确'];
        }
        if($userModel['status']!==1){
            return [400,'此用户已被禁用'];
        }
        if ($this->validate() && Yii::$app->user->login($userModel, true ? 3600*24*30 : 0)) {
            return [200,'登陆成功'];
        }
        return [400,'登陆失败'];
    }
}
