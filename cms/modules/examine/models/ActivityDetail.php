<?php

namespace cms\modules\examine\models;

use Yii;
use cms\modules\member\models\Member;
/**
 * This is the model class for table "yl_activity_detail".
 *
 * @property string $id
 * @property string $activity_id 活动表的ID
 * @property string $custom_member_id 业务人员ID
 * @property string $custom_member_name 业务人员姓名
 * @property string $shop_name 店铺名称
 * @property string $apply_name 联系人姓名
 * @property string $apply_mobile 联系人手机号
 * @property string $area_id 地区ID
 * @property string $area_name 地区名称
 * @property string $address 详细地址
 * @property int $mirror_account 镜面数量
 * @property string $shop_image 店铺门脸照片
 * @property int $status 安装状态(0、未签约 1、已签约 2、签约失败)
 * @property string $create_at 创建时间
 */
class ActivityDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_activity_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'activity_id', 'custom_member_name', 'apply_name', 'apply_mobile', 'area_id', 'area_name', 'address', 'shop_image'], 'required'],
            [['id', 'activity_id', 'custom_member_id', 'area_id', 'mirror_account', 'status'], 'integer'],
            [['create_at'], 'safe'],
            [['custom_member_name', 'apply_name'], 'string', 'max' => 50],
            [['shop_name'], 'string', 'max' => 200],
            [['apply_mobile'], 'string', 'max' => 11],
            [['area_name', 'address', 'shop_image'], 'string', 'max' => 255],
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
            'activity_id' => 'Activity ID',
            'custom_member_id' => 'Custom Member ID',
            'custom_member_name' => '业务人员姓名',
            'shop_name' => '店铺名称',
            'apply_name' => '联系人姓名',
            'apply_mobile' => '联系人手机号',
            'area_id' => 'Area ID',
            'area_name' => '地区名称',
            'address' => '详细地址',
            'mirror_account' => '镜面数量',
            'shop_image' => '店铺门脸照片',
            'status' => '安装状态',
            'create_at' => '创建时间',
        ];
    }

    public function getActivity(){
        return $this->hasOne(Activity::className(),['id'=>'activity_id']);
    }
    public function getMemberMobile(){
        return $this->hasOne(Member::className(),['id'=>'custom_member_id']);
    }

}
