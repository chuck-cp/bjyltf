<?php

namespace cms\modules\config\models;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id 管理员ID
 * @property string $username 管理员登录名
 * @property string $true_name 真实姓名
 * @property string $password_hash 密码
 * @property string $create_at 创建时间
 * @property string $update_at 最后一次修改时间
 * @property int $status
 * @property int $type 0不能进行财务审核，1财务审核 2审计审核 3 出纳审核
 */
class User extends \yii\db\ActiveRecord
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
            [['username', 'password_hash'], 'required'],
            [['create_at', 'update_at','old_cipher','new_password'], 'safe'],
            [['status', 'type'], 'integer'],
            [['username', 'true_name'], 'string', 'max' => 50],
            [['password_hash'], 'string', 'max' => 255],
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
            'true_name' => '真实姓名',
            'password_hash' => '密码',
            'create_at' => '注册时间',
            'update_at' => 'Update At',
            'old_cipher' => '原始密码',
            'new_password' => '新密码',

            //'status' => '能否进行财务审核',
            'type' => '能否进行财务审核',
        ];
    }

    public static function getUserType($type = false, $key = 0){
        $srr = [
            '0'=>'不能进行财务审核',
            '1' => '财务审核',
            '2' => '审计审核',
            '3' => '出纳审核',
        ];
        return $srr;
    }

    //对比原始密码是否正确
    public static function ComparePassword($old_cipher,$id){
        if(isset($old_cipher) && isset($id)){
            $findOne=self::findOne(['id'=>$id]);
           // echo $old_cipher."<br />";
           // ToolsClass::p($findOne);
            if($old_cipher==$findOne['password_hash']){
                return 1;
            }else{
                return 2;
            }
        }
    }
}
