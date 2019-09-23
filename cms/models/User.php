<?php

namespace cms\models;

use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $true_name
 * @property string $create_at
 * @property string $update_at
 * @property integer $status
 * @property string $password_reset_token
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $old_cipher;
    public $new_password;
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
            [['create_at', 'update_at','old_cipher','new_password','office_auth','area_auth'], 'safe'],
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
            'new_password'=>'新密码',
            'password_reset_token' => 'Password Reset Token',
        ];
    }

    /**
     * @param $username
     * @return static
     * 用户名查找用户信息
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username'=>$username]);
    }
    /**
     * @inheritdoc
     * 根据user_backend表的主键（id）获取用户
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     * 根据access_token获取用户，我们暂时先不实现，我们在文章 http://www.manks.top/yii2-restful-api.html 有过实现，如果你感兴趣的话可以看看
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     * 用以标识 Yii::$app->user->id 的返回值
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * 获取auth_key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     * 验证auth_key
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * 验证用户密码是否正确
     */
    public function validatePassword($password){
       /* var_dump(Yii::$app->security->validatePassword($password, $this->password_hash));die;*/
        return Yii::$app->security->validatePassword($password, $this->password_hash);
        return true;
    }
}
