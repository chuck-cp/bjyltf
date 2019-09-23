<?php

namespace cms\modules\authority\models;

use common\libs\ToolsClass;
use Yii;
use cms\modules\authority\models\AuthArea;
use cms\models\SystemAddress;
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
            [['create_at', 'update_at','old_cipher','new_password','phone','email'], 'safe'],
            [['status'], 'integer'],
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
            'true_name' => '姓名',
            'phone' => '电话',
            'email' => '邮箱',
            'password_hash' => '密码',
            'create_at' => '注册时间',
            'update_at' => '最后登录时间',
            'old_cipher' => '原始密码',
            'new_password' => '新密码',
            'status' => '状态',
            'member_group' => '商家审核组',
        ];
    }

    public static function getUserStatus($number){
        $srr = [
            '1' => '启用',
            '2' => '禁用',
        ];
        return array_key_exists($number,$srr) ? $srr[$number] : '未设置';
    }
    /*
     * 根据id获得用户名
     *
     */
    public static function getNameById($id){
        return self::findOne(['id'=>$id]) ? self::findOne(['id'=>$id])->getAttribute('username') : '---';
    }

    //userAddItem
    public static function userAddItem($array){
        AuthAssignment::deleteAll(['user_id'=>$array['userid']]);
        foreach($array['item'] as $key=>$value){
            $authass = new AuthAssignment();
            $authass->item_name = $value;
            $authass->user_id = $array['userid'];
            $authass->created_at = date("Y-m-d H:i:s");
            $addres = $authass->save();
            if(!$addres){
                return false;
            }
        }
        return true;
    }

    /**
     * 组别的数组
     */
    public static function MemberGroupArr(){
        $arr=[
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
            '11'=>'11',
            '12'=>'12',
            '13'=>'13',
            '14'=>'14',
            '15'=>'15',
            '16'=>'16',
            '17'=>'17',
            '18'=>'18',
            '19'=>'19',
            '20'=>'20',
        ];
        foreach ($arr as $v){
            if(self::find()->where(['member_group'=>$v])->count()>=2){
                continue;
            }
            $MemberGroupArr[$v]=$v;
        }
        return $MemberGroupArr;
    }
    
    /**
     * @param $id
     * @return int
     * 添加用户
     */
    public static function userAdd($arr){
        $model =new User;
        if(!$arr['User']['username'] || !$arr['User']['true_name'] || !$arr['User']['password_hash']){
            return json_encode(['code' => 3, 'msg' => '所有选项不能为空!']);
        }
        if(!preg_match("/^1[34578]{1}\d{9}$/",$arr['User']['phone'])){
            return json_encode(['code' => 3, 'msg' => '手机号格式不正确!']);
        }
        if(!preg_match('/\w+([-+.\']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$arr['User']['email'])){
            return json_encode(['code' => 3, 'msg' => '邮箱格式不正确!']);
        }
        $model->username=trim($arr['User']['username']);
        $model->true_name=trim($arr['User']['true_name']);
        $model->phone=trim($arr['User']['phone']);
        $model->email=trim($arr['User']['email']);
        $model->member_group=$arr['User']['member_group']?trim($arr['User']['member_group']):'0';
        $model->auth_key=Yii::$app->security->generateRandomString();
        $model->password_hash = Yii::$app->security->generatePasswordHash(trim($arr['User']['password_hash']));
        $model->office_auth = empty($arr['User']['office_name'])?'0':implode(',',$arr['User']['office_name']);
        if ($model->save()){
            $user_id=$model->attributes['id'];
            $AuthArea=new AuthArea();
            $AuthArea->user_id=$user_id;
            $AuthArea->area_id=0;
            $AuthArea->save(false);
            return json_encode(['code' => 1, 'msg' => '添加成功!']);
        }
        return json_encode(['code' => 2, 'msg' => '添加失败!']);
    }



    //删除用户后，清楚权限
    public static function afterDel($id){
        return AuthAssignment::deleteAll(['user_id'=>$id]);

    }

    /*
     * 关联用户地区权限表
     *
     */
    public function getAreaId(){
        return $this->hasOne(AuthArea::className(),['user_id'=>'id'])->select('user_id,area_id');
    }

    /**
     * 截取地区权限
     */
    public static function substrArea($area){
        $areas=substr($area,0,15);
        foreach (explode(',',$areas) as $v){
            $areaArr[]=SystemAddress::find()->where(['id'=>$v])->select('name')->asArray()->one()['name'];
        }
        return implode(',',$areaArr);
    }
}
