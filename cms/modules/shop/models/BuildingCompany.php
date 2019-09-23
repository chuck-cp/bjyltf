<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "yl_building_company".
 *
 * @property string $id
 * @property string $member_id 业务人员ID
 * @property string $member_name 业务员姓名
 * @property string $member_mobile 业务员电话
 * @property string $led_member_price 业务员LED设备安装提成
 * @property string $poster_member_price 业务员海报安装提成
 * @property string $apply_name 申请人姓名
 * @property string $apply_mobile 申请人手机号
 * @property string $led_apply_price 法人LED设备安装奖励金
 * @property string $poster_apply_price 法人海报安装奖励金
 * @property string $company_name 公司名称
 * @property string $area_id 地区ID
 * @property string $address 店铺所在的详细地址
 * @property string $street 店铺所在的街道
 * @property string $area 店铺所在的区
 * @property string $city 店铺所在的市
 * @property string $province 店铺所在的省
 * @property string $registration_mark 统一社会信息代码
 * @property string $description 备注
 * @property string $agreement_name 合同地址
 * @property string $identity_card_front 申请人身份证正面
 * @property string $identity_card_back 申请人身份证背面
 * @property string $business_licence 营业执照图片
 * @property string $other_image 其他图片(多个以逗号分割)
 */
class BuildingCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_building_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'led_member_price', 'poster_member_price', 'led_apply_price', 'poster_apply_price', 'area_id'], 'integer'],
            [['member_name', 'member_mobile', 'apply_name', 'apply_mobile', 'company_name', 'address', 'street', 'area', 'city', 'province', 'registration_mark'], 'required'],
            [['other_image'], 'string'],
            [['member_name', 'apply_name', 'street', 'registration_mark'], 'string', 'max' => 50],
            [['member_mobile', 'apply_mobile'], 'string', 'max' => 11],
            [['company_name', 'address'], 'string', 'max' => 100],
            [['area', 'city', 'province'], 'string', 'max' => 20],
            [['description', 'agreement_name', 'identity_card_front', 'identity_card_back', 'business_licence'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'member_name' => 'Member Name',
            'member_mobile' => 'Member Mobile',
            'led_member_price' => 'Led Member Price',
            'poster_member_price' => 'Poster Member Price',
            'apply_name' => 'Apply Name',
            'apply_mobile' => 'Apply Mobile',
            'led_apply_price' => 'Led Apply Price',
            'poster_apply_price' => 'Poster Apply Price',
            'company_name' => 'Company Name',
            'area_id' => 'Area ID',
            'address' => 'Address',
            'street' => 'Street',
            'area' => 'Area',
            'city' => 'City',
            'province' => 'Province',
            'registration_mark' => 'Registration Mark',
            'description' => 'Description',
            'agreement_name' => 'Agreement Name',
            'identity_card_front' => 'Identity Card Front',
            'identity_card_back' => 'Identity Card Back',
            'business_licence' => 'Business Licence',
            'other_image' => 'Other Image',
        ];
    }
}
