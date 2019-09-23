<?php

namespace cms\modules\shop\models;

use cms\models\LogExamine;
use Yii;
use cms\modules\examine\models\ShopContract;

/**
 * This is the model class for table "yl_building_shop_park".
 *
 * @property string $id
 * @property string $member_id 业务员用户ID
 * @property string $company_id building_company表的ID
 * @property string $shop_name 公园名称
 * @property string $shop_level 公园等级
 * @property string $contact_name 联系人姓名
 * @property string $contact_mobile 联系人电话
 * @property string $area_id 地区ID
 * @property string $province 店铺所在的省
 * @property string $city 店铺所在的市
 * @property string $area 店铺所在的区
 * @property string $address 店铺所在的详细地址
 * @property string $street 店铺所在的街道
 * @property string $description 备注
 * @property int $led_screen_number LED屏安装数量
 * @property int $poster_screen_number 海报安装数量
 * @property string $shop_image 公园入口照
 * @property string $plan_image 公园平面结构图
 * @property string $other_image 其他图片
 * @property string $poster_create_at 画报申请时间
 * @property string $install_finish_at 安装完成的时间
 * @property string $poster_install_member_id 安装人的ID
 * @property string $poster_install_member_name 安装人姓名
 * @property string $poster_install_mobile 安装人电话
 * @property string $poster_install_price 安装的费用
 * @property string $poster_install_finish_at 店铺安装完成时间
 * @property string $poster_last_examine_user_id 最后审核人ID(用于在双人审核时区分我是否已审核)
 * @property string $poster_examine_user_group 审核人员所在的组
 * @property string $poster_examine_user_name 审核人员姓名
 * @property int $poster_examine_number 审核次数
 * @property int $poster_examine_status 状态(0、申请待审核 1、申请未通过 2、待安装 3、安装待审核 4、安装未通过 5、已安装 6、已关闭)
 * @property string $poster_examine_at 画报审核通过时间
 * @property string $contract_id 合同表ID
 */
class BuildingShopPark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_building_shop_park';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'company_id', 'area_id', 'led_screen_number', 'poster_screen_number', 'poster_install_member_id', 'poster_install_price', 'poster_last_examine_user_id', 'poster_examine_number', 'poster_examine_status', 'contract_id'], 'integer'],
            [['shop_name', 'shop_level', 'contact_name', 'contact_mobile', 'province', 'city', 'area', 'address', 'street', 'description', 'shop_image', 'plan_image'], 'required'],
            [['other_image'], 'string'],
            [['poster_create_at', 'install_finish_at', 'poster_install_finish_at', 'poster_examine_at'], 'safe'],
            [['shop_name', 'address'], 'string', 'max' => 100],
            [['shop_level'], 'string', 'max' => 5],
            [['contact_name', 'province', 'city', 'area', 'poster_install_member_name', 'poster_examine_user_name'], 'string', 'max' => 20],
            [['contact_mobile'], 'string', 'max' => 11],
            [['street'], 'string', 'max' => 50],
            [['description', 'shop_image', 'plan_image'], 'string', 'max' => 255],
            [['poster_install_mobile'], 'string', 'max' => 16],
            [['poster_examine_user_group'], 'string', 'max' => 3],
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
            'company_id' => 'Company ID',
            'shop_name' => 'Shop Name',
            'shop_level' => 'Shop Level',
            'contact_name' => 'Contact Name',
            'contact_mobile' => 'Contact Mobile',
            'area_id' => 'Area ID',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => 'Address',
            'street' => 'Street',
            'description' => 'Description',
            'led_screen_number' => 'Led Screen Number',
            'poster_screen_number' => 'Poster Screen Number',
            'shop_image' => 'Shop Image',
            'plan_image' => 'Plan Image',
            'other_image' => 'Other Image',
            'poster_create_at' => 'Poster Create At',
            'install_finish_at' => 'Install Finish At',
            'poster_install_member_id' => 'Poster Install Member ID',
            'poster_install_member_name' => 'Poster Install Member Name',
            'poster_install_mobile' => 'Poster Install Mobile',
            'poster_install_price' => 'Poster Install Price',
            'poster_install_finish_at' => 'Poster Install Finish At',
            'poster_last_examine_user_id' => 'Poster Last Examine User ID',
            'poster_examine_user_group' => 'Poster Examine User Group',
            'poster_examine_user_name' => 'Poster Examine User Name',
            'poster_examine_number' => 'Poster Examine Number',
            'poster_examine_status' => 'Poster Examine Status',
            'poster_examine_at' => 'Poster Examine At',
            'contract_id' => 'Contract ID',
        ];
    }

    /**
     * 根据状态值获取状态描述
     * 0、待审核 1、审核通过 2、审核未通过 3、安装完成
     */
    public static function getStatusPark($num,$need=false){
        $srr = [
            '0'=>'申请待审核',
            '1'=>'申请未通过',
            '2'=>'待安装',
            '3'=>'安装待审核',
            '4'=>'安装未通过',
            '5'=>'已安装',
            '6'=>'已关闭',
        ];
        if($need){return $srr;}
        return array_key_exists($num,$srr) ? $srr[$num] : '未设置';
    }

    public function getBuildingCompany(){
        return $this->hasOne(BuildingCompany::className(),['id'=>'company_id'])->select('id,company_name,registration_mark,apply_name');
    }

    public function getBuildingShopContract(){
        return $this->hasOne(BuildingShopContract::className(),['id'=>'contract_id'])->select('id,examine_status,examine_at,status');
    }

    //店铺审核
    public static function examinePark($model,$d_type, $status, $desc){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($d_type == 'led'){//LED
                //yl_log_examine审核日志
                $logModel = new LogExamine();
                $logModel->examine_key = 100;
                $logModel->foreign_id = $model->id;
                $logModel->examine_result = $status==2?'1':'2';
                switch ($desc){
                    case '1':
                        $desc = '商家信息有误';
                        break;
                    case '2':
                        $desc = '地址有误';
                        break;
                    default :
                        $desc = $desc;
                }
                $logModel->examine_desc = $desc;
                $logModel->create_user_id = Yii::$app->user->identity->getId();
                $logModel->create_user_name = Yii::$app->user->identity->username;
                $res = $logModel->save();

                //yl_building_shop_floor
                if($status == 2){
                    if($model->led_last_examine_user_id == Yii::$app->user->identity->getId()){
                        $transaction->rollBack();
                        return 4;//审核人重复
                    }
                    $model->led_examine_number+=1;
                    $model->led_last_examine_user_id = Yii::$app->user->identity->getId();
                    if($model->led_examine_number < 2){//一审or二审
                        $model->save();
                        $transaction->commit();
                        return 1;
                    }
                    //添加合同审核
                    if($model->contract_id == 0){
                        $contract = new BuildingShopContract();
                        $contract->shop_id = $model->id;
                        $contract->shop_type = 1;
                        $contract->create_at = date('Y-m-d H:i:s',time());
                        $contract->save(false);
                        //将新生成的合同id写入店铺表
                        $contractid = Yii::$app->db->getLastInsertID();
                        $model->contract_id = $contractid;
                    }
                }
                $model->led_last_examine_user_id = 0;
                $model->led_examine_number = 0;
                $model->led_examine_status = $status;
                $model->led_examine_at = date('Y-m-d H:i:s',time());

                $re = $model->save();
            }elseif($d_type == 'poster'){//海报
                //yl_log_examine审核日志
                $logModel = new LogExamine();
                $logModel->examine_key = 12;
                $logModel->foreign_id = $model->id;
                $logModel->examine_result = $status==2?'1':'2';
                switch ($desc){
                    case '1':
                        $desc = '商家信息有误';
                        break;
                    case '2':
                        $desc = '地址有误';
                        break;
                    default :
                        $desc = $desc;
                }
                $logModel->examine_desc = $desc;
                $logModel->create_user_id = Yii::$app->user->identity->getId();
                $logModel->create_user_name = Yii::$app->user->identity->username;
                $res = $logModel->save();

                //yl_building_shop_floor
                if($status == 2){
                    if($model->poster_last_examine_user_id == Yii::$app->user->identity->getId()){
                        $transaction->rollBack();
                        return 4;//审核人重复
                    }
                    $model->poster_examine_number+=1;
                    $model->poster_last_examine_user_id = Yii::$app->user->identity->getId();
                    if($model->poster_examine_number < 2){//一审or二审
                        $model->save();
                        $transaction->commit();
                        return 1;
                    }
                    //添加合同审核
                    if($model->contract_id == 0){
                        $contract = new BuildingShopContract();
                        $contract->shop_id = $model->id;
                        $contract->shop_type = 1;
                        $contract->create_at = date('Y-m-d H:i:s',time());
                        $contract->save(false);
                        //将新生成的合同id写入店铺表
                        $contractid = Yii::$app->db->getLastInsertID();
                        $model->contract_id = $contractid;
                    }
                }
                $model->poster_last_examine_user_id = 0;
                $model->poster_examine_number = 0;
                $model->poster_examine_status = $status;
                $model->poster_examine_at = date('Y-m-d H:i:s',time());
                $re = $model->save();
            }

            $transaction->commit();
            return 2;
        }catch (Exception $e){
            $transaction->rollBack();
            Yii::error($e->getMessage(),'error');
            return false;
        }
    }
}
