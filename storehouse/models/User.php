<?php

namespace storehouse\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'true_name'], 'required'],
            [['create_at', 'update_at'], 'safe'],
            [['status'], 'integer'],
            [['username', 'true_name'], 'string', 'max' => 80],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'true_name' => 'True Name',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'status' => 'Status',
            'password_reset_token' => 'Password Reset Token',
        ];
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return self::findOne(['username'=>$username]);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * 验证用户密码是否正确
     */
    public function validatePassword($password){
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}
