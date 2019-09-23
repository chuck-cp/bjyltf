<?php

namespace cms\modules\authority\models;

use Yii;

/**
 * This is the model class for table "yl_custom_user".
 *
 * @property string $id 管理员ID
 * @property string $username 管理员登录名
 * @property string $name 真实姓名
 * @property string $password_hash 密码
 * @property string $auth_key 自动登录key
 * @property int $status 状态(1、正常 2、锁定)
 * @property string $create_at 创建时间
 */
class CustomUser extends \yii\db\ActiveRecord
{
    public $new_password;
    public $old_cipher;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_custom_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'auth_key'], 'required'],
            [['status'], 'integer'],
            [['create_at','new_password','old_cipher'], 'safe'],
            [['username', 'name'], 'string', 'max' => 20],
            [['password_hash'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'name' => 'Name',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'status' => 'Status',
            'create_at' => 'Create At',
        ];
    }
}
