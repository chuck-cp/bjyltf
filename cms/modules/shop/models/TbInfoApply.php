<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "tb_info_apply".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $reference_id
 * @property string $code
 * @property string $introducer
 * @property string $user_name
 * @property string $identity_card_num
 * @property string $mobile
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property string $address
 * @property string $shop_name
 * @property string $registration_mark
 * @property string $company_name
 * @property double $acreage
 * @property integer $led_account
 * @property integer $mirror_account
 * @property string $identity_card_front
 * @property string $identity_card_back
 * @property string $business_licence
 * @property string $shop_image
 * @property string $install_image
 * @property integer $channel
 * @property string $apply_time
 * @property integer $status
 * @property integer $profit
 * @property string $install_name
 * @property string $install_mobile
 * @property string $check_name
 * @property string $check_mobile
 * @property string $install_time
 * @property string $install_position
 * @property string $auditing_user
 * @property string $auditing_time
 * @property integer $mod_count
 * @property string $fail_reason
 */
class TbInfoApply extends \yii\db\ActiveRecord
{
    public $way;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_info_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_name', 'identity_card_num', 'mobile', 'province', 'city', 'area', 'address', 'shop_name', 'registration_mark', 'company_name', 'acreage', 'led_account', 'mirror_account', 'identity_card_front', 'business_licence', 'shop_image', 'install_image', 'channel'], 'required'],
            [['user_id', 'reference_id', 'province', 'city', 'area', 'led_account', 'mirror_account', 'channel', 'status', 'profit', 'mod_count'], 'integer'],
            [['acreage'], 'number'],
            [['install_image', 'install_position'], 'string'],
            [['apply_time', 'install_time', 'auditing_time','way'], 'safe'],
            [['code', 'install_name', 'check_name', 'auditing_user'], 'string', 'max' => 20],
            [['introducer', 'user_name'], 'string', 'max' => 128],
            [['identity_card_num'], 'string', 'max' => 18],
            [['mobile', 'install_mobile', 'check_mobile'], 'string', 'max' => 16],
            [['address', 'shop_name', 'registration_mark', 'company_name'], 'string', 'max' => 50],
            [['identity_card_front', 'identity_card_back', 'business_licence', 'shop_image'], 'string', 'max' => 512],
            [['fail_reason'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'reference_id' => 'Reference ID',
            'code' => 'Code',
            'introducer' => 'Introducer',
            'user_name' => 'User Name',
            'identity_card_num' => 'Identity Card Num',
            'mobile' => 'Mobile',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => '详细地址',
            'shop_name' => '店铺名称',
            'registration_mark' => 'Registration Mark',
            'company_name' => 'Company Name',
            'acreage' => '店铺面积',
            'led_account' => '申请数量',
            'mirror_account' => '镜面数量',
            'identity_card_front' => 'Identity Card Front',
            'identity_card_back' => 'Identity Card Back',
            'business_licence' => 'Business Licence',
            'shop_image' => 'Shop Image',
            'install_image' => 'Install Image',
            'channel' => 'Channel',
            'apply_time' => '申请时间',
            'status' => 'Status',
            'profit' => 'Profit',
            'install_name' => 'Install Name',
            'install_mobile' => 'Install Mobile',
            'check_name' => 'Check Name',
            'check_mobile' => 'Check Mobile',
            'install_time' => 'Install Time',
            'install_position' => 'Install Position',
            'auditing_user' => 'Auditing User',
            'auditing_time' => 'Auditing Time',
            'mod_count' => 'Mod Count',
            'fail_reason' => 'Fail Reason',
        ];
    }
}
