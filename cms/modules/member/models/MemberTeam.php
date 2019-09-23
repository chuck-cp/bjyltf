<?php

namespace cms\modules\member\models;

use cms\modules\shop\models\Shop;
use Yii;
use cms\modules\member\models\Member;
/**
 * This is the model class for table "{{%member_team}}".
 *
 * @property string $member_id 用户ID
 * @property string $member_name 组长的姓名
 * @property string $team_name 团队名称
 * @property string $live_area_id 现住址地区ID
 * @property string $live_area_name 现住地址的地区名称
 * @property string $live_address 现住址详细地址
 * @property string $company_name 公司名称
 * @property string $company_area_name 公司地址的地区名称
 * @property string $company_area_id 公司所在地区ID
 * @property string $company_address 公司详细地址
 * @property int $install_shop_number 已安装店铺数量
 * @property int $not_install_shop_number 未安装的店铺数量
 * @property int $not_assign_shop_number 未指派的店铺数量
 */
class MemberTeam extends \yii\db\ActiveRecord
{
    public $phone;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_team}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_member_id', 'team_member_name', 'team_name'], 'required'],
            [['team_member_id', 'live_area_id', 'company_area_id', 'install_shop_number', 'not_install_shop_number', 'not_assign_shop_number', 'phone'], 'integer'],
            [['team_member_name', 'company_name'], 'string', 'max' => 50],
            [['team_name', 'live_address', 'company_address'], 'string', 'max' => 100],
            [['live_area_name', 'company_area_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'team_member_id' => '用户ID',
            'team_member_name' => '组长的姓名',
            'team_name' => '团队名称',
            'live_area_id' => '现住址地区ID',
            'live_area_name' => '现住地址的地区名称',
            'live_address' => '现住址详细地址',
            'company_name' => '公司名称',
            'company_area_name' => '公司地址的地区名称',
            'company_area_id' => '公司所在地区ID',
            'company_address' => '公司详细地址',
            'install_shop_number' => '已安装店铺数量',
            'not_install_shop_number' => '未安装的店铺数量',
            'not_assign_shop_number' => '未指派的店铺数量',
            'create_at' => '创建时间',
            'phone' => '联系电话',
        ];
    }
    /*
     * 获取用户电话
     */
    public function getMobile(){
        $obj = Member::findOne($this->team_member_id);
        return $obj == true ? $obj->mobile : '---';
    }
    /*
     * 关联member
     */
    public function getMemberMobile()
    {
        return $this->hasOne(Member::className(),['id'=>'team_member_id'])->select('id,mobile');
    }
    //获取该团队未安装的店铺数
    public function getInstallNum()
    {
        return Shop::find()->where(['install_team_id'=>$this->id])->andWhere(['in','status',[0,1,2]])->count();
    }

    public function reformExport($column,$value)
    {
        switch ($column) {
            case 'phone':
                return $this->getMobile();
                break;
            default:
                return $value."\t";
                break;
        }
    }
    public function getPhone(){
        if($this->member_id){
            return Member::findOne($this->member_id)->mobile;
        }
        return '---';
    }
    public function getCsvAttributes()
    {
        return [
            'team_name' => '团队名称',
            'team_member_name' => '组长的姓名',
            'phone' => '联系电话',
            'live_area_name' => '现住地址的地区名称',
            'live_address' => '现住址详细地址',
            'company_name' => '公司名称',
            'company_area_name' => '公司地址的地区名称',
            'create_at' => '创建时间',
        ];
    }
}
