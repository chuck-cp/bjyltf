<?php

namespace cms\modules\examine\models;

use Yii;

/**
 * This is the model class for table "yl_shop_headquarters".
 *
 * @property string $id 总部主键
 * @property string $name 法人代表姓名
 * @property string $mobile 法人代表手机号
 * @property string $member_id 申请人ID
 * @property string $identity_card_num 法人代表身份证号
 * @property string $identity_card_front 法人代表身份证正面图
 * @property string $identity_card_back 法人代表身份证背面图
 * @property string $company_name 公司名称
 * @property string $company_area_id 公司所在地区的ID
 * @property string $company_area_name 公司所在地区名称
 * @property string $company_address 公司的详细地址
 * @property string $registration_mark 营业执照统一社会信用代码
 * @property string $business_licence 营业执照图
 * @property string $agreement_name 协议名称
 * @property string $create_at 创建日期
 */
class ShopHeadquarters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $province;
    public $city;
    public $area;
    public $town;
    public static function tableName()
    {
        return 'yl_shop_headquarters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'mobile', 'identity_card_num', 'company_name', 'company_area_id', 'company_area_name', 'company_address', 'registration_mark', 'business_licence'], 'required'],
            [['member_id', 'company_area_id'], 'integer'],
            [['create_at','province','city','area','town'], 'safe'],
            [['name', 'registration_mark'], 'string', 'max' => 50],
            [['mobile'], 'string', 'max' => 16],
            [['identity_card_num'], 'string', 'max' => 18],
            [['identity_card_front', 'identity_card_back', 'company_area_name', 'company_address', 'business_licence'], 'string', 'max' => 255],
            [['company_name'], 'string', 'max' => 100],
            [['agreement_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '总部编号',
            'name' => '法人代表',
            'mobile' => '法人手机号',
            'member_id' => 'Member ID',
            'identity_card_num' => 'Identity Card Num',
            'identity_card_front' => 'Identity Card Front',
            'identity_card_back' => 'Identity Card Back',
            'company_name' => '公司名称',
            'company_area_id' => 'Company Area ID',
            'company_area_name' => '所属地区',
            'company_address' => 'Company Address',
            'registration_mark' => 'Registration Mark',
            'business_licence' => 'Business Licence',
            'agreement_name' => 'Agreement Name',
            'examine_status' => '状态',
            'create_at' => '申请时间',
        ];
    }

    //审核状态
    public static function getStatusByNum($num)
    {
        $srr = [
            '0'=>'待审核',
            '1'=>'审核通过',
            '2'=>'审核驳回',
        ];
        return array_key_exists($num,$srr) ? $srr[$num] : '未设置';
    }

    //连接分店信息表
    public function getHeadquartersList(){
        return $this->hasMany(ShopHeadquartersList::className(),['headquarters_id'=>'id']);
    }
}
