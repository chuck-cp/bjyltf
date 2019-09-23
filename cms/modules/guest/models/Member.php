<?php

namespace cms\modules\guest\models;

use cms\modules\member\models\MemberInfo;
use Yii;

/**
 * This is the model class for table "yl_member".
 *
 * @property string $id
 * @property string $parent_id 上级的工号
 * @property string $admin_area 负责的区域
 * @property int $member_type 用户类型(1、兼职人员 2、正式兼职人员)
 * @property string $name 姓名
 * @property string $name_prefix 姓名前缀
 * @property string $avatar 头像
 * @property string $mobile 手机号
 * @property string $school 毕业学校
 * @property string $education 学历
 * @property string $area 所在地区ID
 * @property string $area_name 地区
 * @property string $address 街道地址
 * @property string $emergency_contact_name 紧急联系人姓名
 * @property string $emergency_contact_mobile 紧急联系人电话
 * @property string $emergency_contact_relation 紧急联系人关系
 * @property int $quit_status 离职状态(0、未离职 1、已离职)
 * @property int $status 状态(1、开启 2、关闭)
 * @property int $inside 是否是内部人员(1、为内部人员)
 * @property int $team 用户是否创建团队(1、已创建)
 * @property string $sign_team_id 签到小组ID
 * @property int $sign_team_admin 团队签到管理人员(1、是管理人员)
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mobile'], 'required'],
            [['id', 'parent_id', 'admin_area', 'member_type', 'area', 'quit_status', 'status', 'inside', 'team', 'sign_team_id', 'sign_team_admin'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['name', 'emergency_contact_name'], 'string', 'max' => 50],
            [['name_prefix'], 'string', 'max' => 1],
            [['avatar', 'area_name', 'address'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 11],
            [['school'], 'string', 'max' => 100],
            [['education'], 'string', 'max' => 3],
            [['emergency_contact_mobile'], 'string', 'max' => 20],
            [['emergency_contact_relation'], 'string', 'max' => 10],
            [['mobile'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'admin_area' => '管理区域',
            'member_type' => '用户类型',
            'name' => '姓名',
            'name_prefix' => '姓名前缀',
            'avatar' => '头像',
            'mobile' => '手机',
            'school' => '毕业学校',
            'education' => '学历',
            'area' => '所在地区ID',
            'area_name' => '地区',
            'address' => '街道地址',
            'emergency_contact_name' => '紧急联系人姓名',
            'emergency_contact_mobile' => '紧急联系人电话',
            'emergency_contact_relation' => '紧急联系人关系',
            'quit_status' => '离职状态',
            'status' => '状态',
            'inside' => '是否是内部人员',
            'team' => '用户是否创建团队',
            'sign_team_id' => '签到小组ID',
            'sign_team_admin' => '团队签到管理人员',
            'create_at' => '创建时间',
            'update_at' => 'Update At',
        ];
    }

    /**
     * 获得用户身份证信息
     */
    public function getMemIdcardInfo(){
        return $this->hasOne(MemberInfo::className(),['member_id'=>'id']);
    }

    /**
     * 根据工号获得上级信息
     */
    public function getMemByNumber($number){
        if(!$number){
            return ['id'=>0,'name'=>'---'];
        }
        $obj = self::find()->where(['id'=>$number]);
        return $obj ==true ? $obj->select('id,name')->asArray()->one() : ['id'=>0,'name'=>'---'];
    }
}
