<?php

namespace cms\modules\shop\models;

use cms\models\LogExamine;
use cms\modules\examine\models\ShopContract;
use cms\modules\shop\models\BuildingShopContract;
use Yii;
/**
 * This is the model class for table "yl_building_shop_floor".
 *
 * @property string $id
 * @property string $company_id building_company表的ID
 * @property string $member_id 业务员用户ID
 * @property string $shop_name 楼宇名称
 * @property int $shop_level 楼宇等级
 * @property int $floor_type 楼宇类型(1、写字楼 2、商住两用)
 * @property string $contact_name 联系人姓名
 * @property string $contact_mobile 联系人电话
 * @property int $floor_number 地上楼层数量
 * @property int $low_floor_number 地下楼层数量
 * @property string $area_id 地区ID
 * @property string $province 店铺所在的省
 * @property string $city 店铺所在的市
 * @property string $area 店铺所在的区
 * @property string $address 店铺所在的详细地址
 * @property string $street 店铺所在的街道
 * @property string $description 备注
 * @property int $led_screen_number LED屏安装数量
 * @property int $poster_screen_number 海报安装数量
 * @property string $shop_image 楼宇外观照
 * @property string $plan_image 楼宇平面结构图
 * @property string $floor_image 楼宇层数图
 * @property string $other_image 其他图片
 * @property string $screen_start_at 设备开机时间
 * @property string $screen_end_at 设备关机时间
 * @property string $led_create_at LED设备申请时间
 * @property string $poster_create_at 画报申请时间
 * @property string $install_finish_at 安装完成的时间
 * @property string $led_install_member_id 安装人的ID
 * @property string $led_install_member_name 安装人姓名
 * @property string $led_install_mobile 安装人电话
 * @property string $led_install_price 安装的费用
 * @property string $led_install_finish_at 店铺安装完成时间
 * @property string $led_last_examine_user_id 最后审核人ID(用于在双人审核时区分我是否已审核)
 * @property string $led_examine_user_group 审核人员所在的组
 * @property string $led_examine_user_name 审核人员姓名
 * @property int $led_examine_number 审核次数
 * @property int $led_examine_status 状态(0、申请待审核 1、申请未通过 2、待安装 3、安装待审核 4、安装未通过 5、已安装 6、已关闭)
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
 */
class BuildingShopFloor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_building_shop_floor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'member_id', 'floor_type', 'floor_number', 'low_floor_number', 'area_id', 'led_screen_number', 'poster_screen_number', 'led_install_member_id', 'led_install_price', 'led_last_examine_user_id', 'led_examine_number', 'led_examine_status', 'poster_install_member_id', 'poster_install_price', 'poster_last_examine_user_id', 'poster_examine_number', 'poster_examine_status'], 'integer'],
            [['shop_name', 'shop_level', 'contact_name', 'contact_mobile', 'province', 'city', 'area', 'address', 'street', 'description', 'shop_image', 'plan_image', 'floor_image'], 'required'],
            [['other_image'], 'string'],
            [['led_create_at', 'poster_create_at', 'led_install_finish_at', 'poster_install_finish_at'], 'safe'],
            [['shop_name', 'address'], 'string', 'max' => 100],
            [['contact_name', 'province', 'city', 'area', 'led_install_member_name', 'led_examine_user_name', 'poster_install_member_name', 'poster_examine_user_name'], 'string', 'max' => 20],
            [['contact_mobile'], 'string', 'max' => 11],
            [['street'], 'string', 'max' => 50],
            [['description', 'shop_image', 'plan_image', 'floor_image'], 'string', 'max' => 255],
            [['screen_start_at', 'screen_end_at'], 'string', 'max' => 10],
            [['led_install_mobile', 'poster_install_mobile'], 'string', 'max' => 16],
            [['led_examine_user_group', 'poster_examine_user_group'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'member_id' => 'Member ID',
            'shop_name' => 'Shop Name',
            'shop_level' => 'Shop Level',
            'floor_type' => 'Floor Type',
            'contact_name' => 'Contact Name',
            'contact_mobile' => 'Contact Mobile',
            'floor_number' => 'Floor Number',
            'low_floor_number' => 'Low Floor Number',
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
            'floor_image' => 'Floor Image',
            'other_image' => 'Other Image',
            'screen_start_at' => 'Screen Start At',
            'screen_end_at' => 'Screen End At',
            'led_create_at' => 'Led Create At',
            'poster_create_at' => 'Poster Create At',
            'led_install_member_id' => 'Led Install Member ID',
            'led_install_member_name' => 'Led Install Member Name',
            'led_install_mobile' => 'Led Install Mobile',
            'led_install_price' => 'Led Install Price',
            'led_install_finish_at' => 'Led Install Finish At',
            'led_last_examine_user_id' => 'Led Last Examine User ID',
            'led_examine_user_group' => 'Led Examine User Group',
            'led_examine_user_name' => 'Led Examine User Name',
            'led_examine_number' => 'Led Examine Number',
            'led_examine_status' => 'Led Examine Status',
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
        ];
    }


    /**
     * 根据状态值获取状态描述
     * 0、待审核 1、审核通过 2、审核未通过 3、安装完成
     */
    public static function getStatusfloor($num,$need=false){
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

    /**
     * @return \yii\db\ActiveQuery
     *
     */
    public function getBuildingCompany(){
        return $this->hasOne(BuildingCompany::className(),['id'=>'company_id'])->select('id,company_name,registration_mark,apply_name');
    }
    public function getBuildingShopContract(){
        return $this->hasOne(BuildingShopContract::className(),['id'=>'contract_id'])->select('id,examine_status,examine_at,status');
    }

    //店铺审核
    public static function examineFloor($model,$d_type, $status, $desc){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($d_type == 'led'){//LED
                //yl_log_examine审核日志
                $logModel = new LogExamine();
                $logModel->examine_key = 10;
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
                $logModel->examine_key = 11;
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
