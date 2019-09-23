<?php

namespace cms\modules\config\models;

use common\libs\ToolsClass;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%system_config}}".
 *
 * @property string $id
 * @property string $content
 */
class SystemConfig extends \yii\db\ActiveRecord
{
    public $sales_money;
    public $shop_number;
    public $service_phone;
    public $proportions_sixth;
    public $proportions_fifth;
    public $proportions_fourth;
    public $proportions_third;
    public $proportions_second;
    public $proportions_first;
    public $proportions;
    public $proportions_part_time_business;
    public $system_receiver_address;
    public $system_receiver_bank_name;
    public $system_receiver_bank_number;
    public $system_receiver_name;
    public $advert_price_reserved;
    public $cooperation;
    public $e_mail;
    public $led_spec;
    public $storehouse;
    public $manufactory;
    public $express;
    public $programmer_phone;
    public $config_pay;
    public $subsidy_date;
    public $system_price_install_1_1;
    public $system_price_install_1_2;
    public $system_price_install_1_3;
    public $system_price_install_2_1;
    public $system_price_install_2_2;
    public $system_price_install_2_3;
    public $system_price_remove_1_1;
    public $system_price_remove_1_2;
    public $system_price_remove_1_3;
    public $system_price_remove_2_1;
    public $system_price_remove_2_2;
    public $system_price_remove_2_3;
    public $system_price_replace_1_1;
    public $system_price_replace_1_2;
    public $system_price_replace_1_3;
    public $system_price_replace_2_1;
    public $system_price_replace_2_2;
    public $system_price_replace_2_3;
    public $send_number_in_black;
    public $advert_advance_upload_time;
    public $advert_advance_upload_time_set;
    public $advert_timing_push_time;
    public $advert_timing_push_time_set;
    public $salesman_trimming_distance;
    public $salesman_first_check_time;
    public $salesman_check_interval_time;
    public $salesman_day_sign_number;
    public $salesman_earliest_closing_time;//业务签到--最早下班时间
    public $maintain_trimming_distance;
    public $maintain_first_check_time;
    public $maintain_check_interval_time;
    public $maintain_day_sign_number;
    public $maintain_earliest_closing_time;//维护签到--最早下班时间
    public $shop_contact_price_inside_self;
    public $shop_contact_price_inside_parent;
    public $shop_contact_price_outside_self;
    public $shop_contact_price_outside_parent;
    public $just_allow_inside_member_invite;
    public $small_shop_price_first_install_apply;
    public $small_shop_price_first_install_salesman;
    public $small_shop_price_first_install_salesman_parent;
    public $small_shop_subsidy_price;
    public $frame_device_level;
    public $frame_device_material;
    public $frame_device_size;
    public $frame_device_manufactor;
    public $filename;
    public $upload_img_url;

    public $order_maximum_discount;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_number', 'service_phone', 'sales_money','proportions','proportions_part_time_business','proportions_first','proportions_second','proportions_third','proportions_fourth','proportions_fifth','proportions_sixth','system_receiver_address','system_receiver_bank_name','system_receiver_name', 'cooperation', 'e_mail', 'manufactory', 'led_spec', 'storehouse', 'express','programmer_phone', 'service_phone', 'system_price_install_1_1','system_price_install_1_2','system_price_install_1_3','system_price_install_2_1','system_price_install_2_2','system_price_install_2_3','system_price_remove_1_1','system_price_remove_1_2','system_price_remove_1_3','system_price_remove_2_1','system_price_remove_2_2','system_price_remove_2_3','system_price_replace_1_1','system_price_replace_1_2','system_price_replace_1_3','system_price_replace_2_1','system_price_replace_2_2','system_price_replace_2_3','advert_advance_upload_time','advert_advance_upload_time_set','advert_timing_push_time','advert_timing_push_time_set','salesman_trimming_distance','salesman_first_check_time','salesman_check_interval_time','salesman_day_sign_number','maintain_trimming_distance','maintain_first_check_time','maintain_check_interval_time','maintain_day_sign_number','shop_contact_price_inside_self','shop_contact_price_inside_parent','shop_contact_price_outside_self','shop_contact_price_outside_parent','just_allow_inside_member_invite','salesman_earliest_closing_time','maintain_earliest_closing_time','small_shop_price_first_install_apply','small_shop_price_first_install_salesman','small_shop_price_first_install_salesman_parent','small_shop_subsidy_price','order_maximum_discount','frame_device_level','frame_device_material','frame_device_size','frame_device_manufactor'], 'safe'],
            [ 'e_mail', 'email'],
            [['shop_number', 'sales_money','proportions','proportions_first','proportions_second','proportions_third','proportions_fourth','proportions_fifth','proportions_sixth','system_receiver_bank_number','advert_price_reserved', 'cooperation','subsidy_date','send_number_in_black'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advert_price_reserved' => 'advert_price_reserved',
            'cooperation' => 'cooperation',
            'express' => 'express',
            'e_mail' => '邮箱',
            'led_spec' => 'led_spec',
            'manufactory' => 'manufactory',
            'programmer_phone' => 'programmer_phone',
            'proportions' => 'proportions',
            'proportions_part_time_business' => 'proportions_part_time_business',
            'proportions_fifth' => 'proportions_fifth',
            'proportions_first' => 'proportions_first',
            'proportions_fourth' => 'proportions_fourth',
            'proportions_second' => 'proportions_second',
            'proportions_sixth' => 'proportions_sixth',
            'proportions_third' => 'proportions_third',
            'sales_money' => 'sales_money',
            'service_phone' => '客服电话',
            'shop_number' => 'shop_number',
            'storehouse' => 'storehouse',
            'system_receiver_address' => 'system_receiver_address',
            'system_receiver_bank_name' => 'system_receiver_bank_name',
            'system_receiver_bank_number' => 'system_receiver_bank_number',
            'system_receiver_name' => 'system_receiver_name',
            'subsidy_date' => 'subsidy_date',
            'system_price_install_1_1' => 'system_price_install_1_1',
            'system_price_install_1_2' => 'system_price_install_1_2',
            'system_price_install_1_3' => 'system_price_install_1_3',
            'system_price_install_2_1' => 'system_price_install_2_1',
            'system_price_install_2_2' => 'system_price_install_2_2',
            'system_price_install_2_3' => 'system_price_install_2_3',
            'system_price_remove_1_1' => 'system_price_remove_1_1',
            'system_price_remove_1_2' => 'system_price_remove_1_2',
            'system_price_remove_1_3' => 'system_price_remove_1_3',
            'system_price_remove_2_1' => 'system_price_remove_2_1',
            'system_price_remove_2_2' => 'system_price_remove_2_2',
            'system_price_remove_2_3' => 'system_price_remove_2_3',
            'system_price_replace_1_1' => 'system_price_replace_1_1',
            'system_price_replace_1_2' => 'system_price_replace_1_2',
            'system_price_replace_1_3' => 'system_price_replace_1_3',
            'system_price_replace_2_1' => 'system_price_replace_2_1',
            'system_price_replace_2_2' => 'system_price_replace_2_2',
            'system_price_replace_2_3' => 'system_price_replace_2_3',
            'send_number_in_black' => 'send_number_in_black',
            'advert_advance_upload_time' => 'advert_advance_upload_time',
            'advert_advance_upload_time_set' => 'advert_advance_upload_time_set',
            'advert_timing_push_time' => 'advert_timing_push_time',
            'advert_timing_push_time_set' => 'advert_timing_push_time_set',
            'salesman_trimming_distance' => 'salesman_trimming_distance',
            'salesman_first_check_time' => 'salesman_first_check_time',
            'salesman_check_interval_time' => 'salesman_check_interval_time',
            'salesman_day_sign_number' => 'salesman_day_sign_number',
            'salesman_earliest_closing_time' => 'salesman_earliest_closing_time',

            'maintain_trimming_distance' => 'maintain_trimming_distance',
            'maintain_first_check_time' => 'maintain_first_check_time',
            'maintain_check_interval_time' => 'maintain_check_interval_time',
            'maintain_day_sign_number' => 'maintain_day_sign_number',
            'maintain_earliest_closing_time' => 'maintain_earliest_closing_time',

            'shop_contact_price_inside_self' => 'shop_contact_price_inside_self',
            'shop_contact_price_inside_parent' => 'shop_contact_price_inside_parent',
            'shop_contact_price_outside_self' => 'shop_contact_price_outside_self',
            'shop_contact_price_outside_parent' => 'shop_contact_price_outside_parent',
            'just_allow_inside_member_invite' => 'just_allow_inside_member_invite',
            'small_shop_price_first_install_apply' => 'small_shop_price_first_install_apply',
            'small_shop_price_first_install_salesman' => 'small_shop_price_first_install_salesman',
            'small_shop_price_first_install_salesman_parent' => 'small_shop_price_first_install_salesman_parent',
            'small_shop_subsidy_price' => 'small_shop_subsidy_price',
            'upload_img_url'=>'选择图片',
            'order_maximum_discount'=>'order_maximum_discount',
            'frame_device_level' =>'frame_device_level',
            'frame_device_material' =>'frame_device_material',
            'frame_device_size' =>'frame_device_size',
            'frame_device_manufactor' =>'frame_device_manufactor',
        ];
    }

    public function saveConfig(){
        $configAttr = array_keys(self::attributeLabels());
        foreach($configAttr as $config){
            if($this->$config || $this->$config === '0' || $this->$config === 0) {
                $configModel = self::findOne(['id'=>$config]);
                if ($configModel) {
                    if(is_array($this->$config)){
                        foreach ($this->$config as $kk => $vv){
                            $this->$config[$kk] = str_replace([',',' '],'', $vv);
                        }
                        $this->$config = implode(',', array_filter($this->$config));
                        $configModel->content = $this->$config;
                    }else{
                        $configModel->content = str_replace(',', '', $this->$config);
                    }
                    $configModel->save();
                } else {
                    $configModel = new self();
                    $configModel->id = $config;
                    if(is_array($this->$config)){
                        foreach ($this->$config as $kk => $vv){
                            $this->$config[$kk] = str_replace([',',' '],'',$vv);
                        }
                        $this->$config = implode(',', array_filter($this->$config));
                        $configModel->content = $this->$config;
                    }else{
                        $configModel->content = str_replace(',', '', $this->$config);
                    }
                    $configModel->save();
                }
            }
        }
    }
    public function loadConfigData($type){
        if($type == 'salesman'){
            $configAttr = array_keys(self::attributeLabels());
        }elseif($type == 'installprice'){
            $configAttr = [
                'system_price_install_1_1',//内部电工安装屏幕费用(一级地区)
                'system_price_install_1_2',//内部电工安装屏幕费用(二级地区)
                'system_price_install_1_3',//内部电工安装屏幕费用(三级地区)
                'system_price_install_2_1',//外部电工安装屏幕费用(一级地区)
                'system_price_install_2_2',//外部电工安装屏幕费用(二级地区)
                'system_price_install_2_3',//外部电工安装屏幕费用(三级地区)
                'system_price_remove_1_1',//内部电工拆除屏幕费用(一级地区)
                'system_price_remove_1_2',//内部电工拆除屏幕费用(二级地区)
                'system_price_remove_1_3',//内部电工拆除屏幕费用(三级地区)
                'system_price_remove_2_1',//外部电工拆除屏幕费用(一级地区)
                'system_price_remove_2_2',//外部电工拆除屏幕费用(二级地区)
                'system_price_remove_2_3',//外部电工拆除屏幕费用(三级地区)
                'system_price_replace_1_1',//内部电工更换屏幕费用(一级地区)
                'system_price_replace_1_2',//内部电工更换屏幕费用(二级地区)
                'system_price_replace_1_3',//内部电工更换屏幕费用(三级地区)
                'system_price_replace_2_1',//外部电工更换屏幕费用(一级地区)
                'system_price_replace_2_2',//外部电工更换屏幕费用(二级地区)
                'system_price_replace_2_3',//外部电工更换屏幕费用(三级地区)
            ];
        }elseif ($type == 'service_phone'){
            $configAttr = [
                'service_phone',
                'e_mail',
            ];
        }elseif ($type == 'money') {
            $configAttr = 'sales_money';
        }elseif ($type == 'yuliu') {
            $configAttr = 'advert_price_reserved';
        }elseif ($type == 'huikuan'){
            $configAttr = [
                'system_receiver_address',
                'system_receiver_bank_name',
                'system_receiver_bank_number',
                'system_receiver_name',
            ];
        }elseif ($type == 'proportions'){
            $configAttr = [
                'proportions',
                'proportions_part_time_business',
                'proportions_first',
                'proportions_second',
                'proportions_third',
                'proportions_fourth',
                'proportions_fifth',
                'proportions_sixth',
                'cooperation'
            ];
        }elseif ($type == 'screen'){
            $configAttr = [
                'storehouse',
                'led_spec',
                'manufactory',
                //'express',
                'frame_device_level',
                'frame_device_material',
                'frame_device_size',
                'frame_device_manufactor',
            ];
        }elseif ($type == 'programmer_phone'){
            $configAttr = 'programmer_phone';
        }elseif ($type == 'blacklist'){
            $configAttr = 'send_number_in_black';
        }elseif($type == 'advert'){
            $configAttr = [
                'advert_advance_upload_time',
                'advert_advance_upload_time_set',
                'advert_timing_push_time',
                'advert_timing_push_time_set',
            ];
        }elseif($type == 'salesman-sign'){
            $configAttr = [
                'salesman_trimming_distance',
                'salesman_first_check_time',
                'salesman_check_interval_time',
                'salesman_day_sign_number',
                'salesman_earliest_closing_time',
            ];
        }elseif($type == 'maintain'){
            $configAttr = [
                'maintain_trimming_distance',
                'maintain_first_check_time',
                'maintain_check_interval_time',
                'maintain_day_sign_number',
                'maintain_earliest_closing_time',
            ];
        }elseif($type="contact-shop-bonus"){
            $configAttr = [
                'shop_contact_price_inside_self',//联系店铺业务合作费（内部）
                'shop_contact_price_inside_parent',//联系店铺上级提成金额（内部）
                'shop_contact_price_outside_self',//联系店铺业务合作费（外部）
                'shop_contact_price_outside_parent',//联系店铺上级提成金额（外部）
                'just_allow_inside_member_invite',//只能通过内部人员邀请码进行邀请 1、是

                'small_shop_price_first_install_apply',//两快镜面的店铺首次安装给申请人的价格
                'small_shop_price_first_install_salesman',//两快镜面的店铺首次安装给业务员的价格
                'small_shop_price_first_install_salesman_parent',//两快镜面的店铺给上级的价格
                'small_shop_subsidy_price',//两快镜面的店铺第二年的每月维护费
            ];
        }
        $configList = self::find()->select('id,content')->where(['id'=>$configAttr])->asArray()->all();
        if(empty($configList)){
            return false;
        }
        foreach($configList as $config){
            $id = $config['id'];
            $this->$id = $config['content'];
        }
    }

    public static function getConfig($id){
        $configModel = SystemConfig::find()->where(['id'=>$id])->select('content')->asArray()->one();
        if(empty($configModel)){
            return false;
        }
        return $configModel['content'];
    }

    public static function getAllConfig($id){
        $config = SystemConfig::find()->where(['id'=>$id])->select('id,content')->asArray()->all();
        if(empty($config)){
            return false;
        }
        return ArrayHelper::map($config,'id','content');
    }

    public static function getAreaInstallPrice($area, $money_type)
    {
        $defaultLevel = 3;
        if(!$area){
            return self::findOne($money_type.$defaultLevel)->content;
        }
        $areas = substr($area,0,9);
        $level = SystemAddressLevel::findOne(['area_id'=>$areas]);
        if(empty($level)){
            $level = $defaultLevel;
        }else{
            $level = $level->level;
            if(!in_array($level,[1,2,3])){
                $level = $defaultLevel;
            }
        }
        return self::findOne($money_type.$level)->content;
    }

    /**
     * 区域价格
     * wpw
     * 2018-08-05
     */
    public static function Regionalprice(){
        //获取区域价格
        $Regionalprice=self::find()->where(['in','id',['system_price_first_install_1','system_price_first_install_2','system_price_first_install_3']])->asArray()->all();

        //获取每月补助
        $subsidyprice=self::find()->where(['in','id',['system_price_subsidy_1','system_price_subsidy_2','system_price_subsidy_3']])->asArray()->all();

        foreach($Regionalprice as $k=>$v){
            $Regionalpriceall[$k]['id']=$k+1;
            $Regionalpriceall[$k]['regionalprice']=$v['content'];
            $Regionalpriceall[$k]['subsidyprice']=$subsidyprice[$k]['content'];
        }
        return $Regionalpriceall;
    }
}


