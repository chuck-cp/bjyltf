<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "{{%shop_apply}}".
 *
 * @property string $id
 * @property string $code
 * @property string $identity_card_num
 * @property string $mobile
 * @property string $registration_mark
 * @property string $company_name
 * @property string $identity_card_front
 * @property string $identity_card_back
 * @property string $business_licence
 * @property string $install_image
 * @property string $profit
 * @property string $install_name
 * @property string $install_mobile
 * @property string $check_name
 * @property string $check_mobile
 * @property string $install_time
 * @property string $install_position
 * @property string $auditing_user
 * @property string $auditing_time
 * @property string $fail_reason
 */
class ShopApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_apply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'identity_card_num', 'registration_mark', 'company_name', 'identity_card_front','contacts_name','contacts_mobile'], 'required'],
            [['id'], 'integer'],
            [['install_time', 'auditing_time'], 'safe'],
            [['install_name'], 'string', 'max' => 20],
            [['identity_card_num'], 'string', 'max' => 18],
            [['install_mobile'], 'string', 'max' => 16],
            [['apply_code'],'string','max' => 20],
            [['dynamic_code'],'string','max' => 4],
            [['registration_mark', 'company_name'], 'string', 'max' => 50],
            [['identity_card_front', 'business_licence'], 'string', 'max' => 512],
//            [['fail_reason'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apply_code' => '订单号',
            'dynamic_code' => '动态码',
            'identity_card_num' => 'Identity Card Num',
//            'mobile' => 'Mobile',
            'registration_mark' => 'Registration Mark',
            'company_name' => 'Company Name',
            'identity_card_front' => 'Identity Card Front',
//            'identity_card_back' => 'Identity Card Back',
            'business_licence' => 'Business Licence',
//            'install_image' => 'Install Image',
//            'profit' => 'Profit',
            'install_name' => 'Install Name',
            'install_mobile' => 'Install Mobile',
//            'check_name' => 'Check Name',
//            'check_mobile' => 'Check Mobile',
//            'install_time' => 'Install Time',
//            'install_position' => 'Install Position',
//            'auditing_user' => 'Auditing User',
            'auditing_time' => 'Auditing Time',
//            'fail_reason' => 'Fail Reason',
        ];
    }
    /**
     * 获取公司名称
     */
    public static function getCompanyById($id){
        $shopObj = self::findOne(['id'=>$id]);
        if($shopObj){
            return $shopObj;
        }
    }

    //获取店铺入住信息
    public static function getShopApplyInfo($shopid)
    {
        $post = self::find()->where(['id'=>$shopid])->select('company_name,apply_code,dynamic_code,apply_name,apply_mobile,identity_card_front,business_licence,install_name,install_mobile,identity_card_back,agent_identity_card_front,agent_identity_card_back,panorama_image,apply_brokerage,registration_mark,authorize_image,contacts_name,contacts_mobile,other_image')->asArray()->one();
        return $post;
    }
}
