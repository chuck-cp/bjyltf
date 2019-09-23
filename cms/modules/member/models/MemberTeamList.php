<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "{{%member_team_list}}".
 *
 * @property string $member_id 成员ID
 * @property string $member_name 成员姓名
 * @property int $install_shop_number 已安装的店铺数量
 * @property int $install_screen_number 已安装的屏幕数量
 * @property int $wait_shop_number 待安装的屏幕数量
 */
class MemberTeamList extends \yii\db\ActiveRecord
{
    public $mobile;
    public $address;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_team_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_name'], 'required'],
            [['member_id', 'install_shop_number', 'install_screen_number', 'wait_shop_number'], 'integer'],
            [['member_name'], 'string', 'max' => 50],
            [['mobile','team_id'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'team_id' => '团队ID',
            'member_name' => '成员姓名',
            'install_shop_number' => '已安装的店铺数量',
            'install_screen_number' => '已安装的屏幕数量',
            'wait_shop_number' => '待安装的屏幕数量',
            'mobile' => '手机号',
        ];
    }

    public function getCsvAttributes(){
        return [
            'member_name' => '成员姓名',
            'address' => '现住地址',
            'mobile' => '手机号',
        ];
    }
    public function reformExport($column,$value)
    {
        switch ($column) {
            case 'address':
                return $this->getMemberAddress();
                break;
            case 'mobile':
                return $this->getMemberPhone();
                break;
            default:
                return $value."\t";
                break;
        }
    }
    /*
     * 获取用户姓名
     */
    public function getMemberAddress()
    {
        $memberObj = MemberInfo::findOne($this->member_id);
        if($memberObj){
            return $memberObj->live_area_name.$memberObj->live_address;
        }
        return '---';

    }
    /*
     * 获取用户电话
     */
    public function getMemberPhone()
    {
        if($this->member_id){
            return Member::findOne($this->member_id)->mobile;
        }
        return '---';
    }




    /*
     * 关联member
     */
    public function getMemberMobile()
    {
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('mobile');
    }
}
