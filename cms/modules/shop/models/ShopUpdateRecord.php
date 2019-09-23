<?php

namespace cms\modules\shop\models;

use cms\modules\member\models\Member;
use common\libs\ToolsClass;
use Yii;
use cms\models\LogExamine;
use cms\models\SystemAddress;
use cms\modules\screen\models\Screen;
use common\libs\RedisClass;

/**
 * This is the model class for table "yl_shop_update_record".
 *
 * @property string $id
 * @property string $shop_id 店铺ID
 * @property string $shop_name 原店铺名称
 * @property string $apply_name 原法人姓名
 * @property string $apply_mobile 原法人手机号
 * @property string $contacts_name 原联系人姓名
 * @property string $contacts_mobile 原联系人电话
 * @property string $identity_card_num 原法人身份证号
 * @property string $registration_mark 原统一社会信用码
 * @property string $company_name 原公司名称
 * @property string $panorama_image 原店铺全景图
 * @property string $shop_image 原店铺门脸图
 * @property string $identity_card_front 原身份证正面照
 * @property string $identity_card_back 原身份证背面照
 * @property string $agent_identity_card_front 原代理人身份证正面照
 * @property string $agent_identity_card_back 原代理人身份证背面照
 * @property string $area_id 原店铺地区ID
 * @property string $area_name 原店铺地区名称
 * @property string $address 原地区详细地址
 * @property string $update_shop_name 更新后的店铺名称
 * @property string $update_apply_name 更新后的法人姓名
 * @property string $update_apply_mobile 更新后的法人手机号
 * @property string $update_contacts_name 更新后的联系人姓名
 * @property string $update_contacts_mobile 更新后的联系人电话
 * @property string $update_identity_card_num 更新后的法人身份证号码
 * @property string $update_registration_mark 更新后的统一社会信用码
 * @property string $update_company_name 更新后的公司名称
 * @property string $update_identity_card_front 修改后的法人身份证正面照
 * @property string $update_identity_card_back 修改后的身份证背面照
 * @property string $update_agent_identity_card_front 更新后的代理人身份证正面照
 * @property string $update_agent_identity_card_back 更新后的代理人身份证背面照
 * @property string $update_panorama_image 修改后的店铺全景图
 * @property string $business_licence 更新前的营业执照
 * @property string $update_shop_image 修改后的店铺门脸图
 * @property string $update_business_licence 更新后的营业执照
 * @property string $authorize_image 原授权图片
 * @property string $update_authorize_image 修改后的授权图片
 * @property string $other_image 原其他原片
 * @property string $update_other_image 修改后的其他图片
 * @property string $update_area_name 修改后的店铺地区名称
 * @property string $update_area_id 修改后的店铺地区ID
 * @property string $update_address 修改后的详细地址
 * @property int $examine_status 审核状态(0、待审核 1、通过 2、不通过)
 * @property string $examine_at 审核通过时间
 * @property string $create_user_name 发起人姓名
 * @property string $create_at 添加时间
 */
class ShopUpdateRecord extends \yii\db\ActiveRecord
{
    public $province;
    public $city;
    public $town;
    public $area;
    public $update_other_image2;
    public $update_other_image3;
    public $update_other_image4;
    public $update_other_image5;
    public $update_authorize_image2;
    public $update_authorize_image3;
    public $update_authorize_image4;
    public $update_authorize_image5;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_shop_update_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'identity_card_front', 'identity_card_back', 'update_identity_card_front', 'create_user_name'], 'required'],
            [['shop_id', 'area_id', 'update_area_id', 'examine_status'], 'integer'],
            [['authorize_image', 'update_authorize_image', 'other_image', 'update_other_image'], 'string'],
            [['examine_at', 'create_at','province','city','area','town'], 'safe'],
            [['shop_name', 'company_name', 'area_name', 'update_shop_name', 'update_company_name'], 'string', 'max' => 100],
            [['apply_name', 'apply_mobile', 'contacts_name', 'contacts_mobile', 'update_apply_name', 'update_apply_mobile', 'update_contacts_name', 'update_contacts_mobile'], 'string', 'max' => 20],
            [['identity_card_num', 'update_identity_card_num'], 'string', 'max' => 18],
            [['registration_mark', 'update_registration_mark', 'create_user_name'], 'string', 'max' => 50],
            [['panorama_image', 'shop_image', 'identity_card_front', 'identity_card_back', 'agent_identity_card_front', 'agent_identity_card_back', 'address', 'update_identity_card_front', 'update_identity_card_back', 'update_agent_identity_card_front', 'update_agent_identity_card_back', 'update_panorama_image', 'business_licence', 'update_shop_image', 'update_business_licence', 'update_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => '店铺ID',
            'shop_name' => '原店铺名称',
            'apply_name' => '原法人姓名',
            'apply_mobile' => '原法人手机号',
            'contacts_name' => 'Contacts Name',
            'contacts_mobile' => 'Contacts Mobile',
            'identity_card_num' => '原法人身份证号',
            'registration_mark' => '原统一社会信用码',
            'company_name' => '原公司名称',
            'panorama_image' => 'Panorama Image',
            'shop_image' => 'Shop Image',
            'identity_card_front' => '原身份证正面照',
            'identity_card_back' => '原身份证背面照',
            'agent_identity_card_front' => '原代理人身份证正面照',
            'agent_identity_card_back' => '原代理人身份证背面照',
            'area_id' => '地区ID',
            'area_name' => 'Area Name',
            'address' => 'Address',
            'update_shop_name' => '更新后的店铺名称',
            'update_apply_name' => '更新后的法人姓名',
            'update_apply_mobile' => '更新后的法人手机号',
            'update_contacts_name' => 'Update Contacts Name',
            'update_contacts_mobile' => 'Update Contacts Mobile',
            'update_identity_card_num' => '更新后的法人身份证号码',
            'update_registration_mark' => '更新后的统一社会信用码',
            'update_company_name' => '更新后的公司名称',
            'update_identity_card_front' => '修改后的法人身份证正面照',
            'update_identity_card_back' => '修改后的身份证背面照',
            'update_agent_identity_card_front' => '更新后的代理人身份证正面照',
            'update_agent_identity_card_back' => '更新后的代理人身份证背面照',
            'update_panorama_image' => 'Update Panorama Image',
            'business_licence' => 'Business Licence',
            'update_shop_image' => 'Update Shop Image',
            'update_business_licence' => 'Update Business Licence',
            'authorize_image' => '原授权图片',
            'update_authorize_image' => '修改后的授权图片',
            'other_image' => '原其他原片',
            'update_other_image' => '修改后的其他图片',
            'update_area_name' => 'Update Area Name',
            'update_area_id' => 'Update Area ID',
            'update_address' => 'Update Address',
            'examine_status' => '审核状态',
            'examine_at' => '审核通过时间',
            'create_user_name' => '发起人姓名',
            'create_at' => '发起时间',
        ];
    }

    public static  function getStatus($num,$need=false)
    {
        $srr = [
            '0'=>'待审核',
            '1'=>'已通过',
            '2'=>'不通过',
        ];
        if($need){return $srr;}
        return array_key_exists($num,$srr) ? $srr[$num] : '未设置';
    }

    //getChooseList获取变更记录
    public static function getChooseList($shop_id)
    {
        return ShopUpdateRecord::find()->where(['shop_id' => $shop_id, 'examine_status' => 1])->asArray()->all();
    }

    //修改法人内容提交
    public function getAdminMember($datas){
        if(isset($datas['id'])){
            $examine_status = [0];
        }else{
            $examine_status = [0,2];
        }
        $surmodel = ShopUpdateRecord::find()->where(['shop_id'=>$datas['shop_id'],'examine_status'=>$examine_status])->asArray()->all();
        if($surmodel){
            return json_encode(['code'=>9,'msg'=>'该店铺已提交申请，待审核后再次申请！']);
        }
        if(empty($datas['shop_id'])){
            return json_encode(['code'=>2,'msg'=>'非法数据']);
        }
        if(empty($datas['update_apply_name'])){
            return json_encode(['code'=>3,'msg'=>'变更后法人不能为空']);
        }
        if(empty($datas['update_apply_mobile'])){
            return json_encode(['code'=>4,'msg'=>'变更手机号不能为空']);
        }
        $member = Member::findOne(['mobile'=>$datas['update_apply_mobile']]);
        if(empty($member)){
            return json_encode(['code'=>10,'msg'=>'手机号未注册']);
        }
        if(empty($datas['update_identity_card_num'])){
            return json_encode(['code'=>5,'msg'=>'变更身份证号不能为空']);
        }
        if(empty($datas['ShopUpdateRecord']['update_identity_card_front'])){
            return json_encode(['code'=>6,'msg'=>'法人身份证正面照不能为空']);
        }
        if(empty($datas['ShopUpdateRecord']['update_identity_card_back'])){
            return json_encode(['code'=>7,'msg'=>'法人身份证背面照不能为空']);
        }
        if(!empty($datas['ShopUpdateRecord']['province'])&&strlen($datas['ShopUpdateRecord']['town'])!=12){
            return json_encode(['code'=>8,'msg'=>'请选择正确的街道地址']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try{
            //处理授权证明
            $update_authorize_image=implode(',',array_filter( [$datas['ShopUpdateRecord']['update_authorize_image'],$datas['ShopUpdateRecord']['update_authorize_image2'],$datas['ShopUpdateRecord']['update_authorize_image3'],$datas['ShopUpdateRecord']['update_authorize_image4'],$datas['ShopUpdateRecord']['update_authorize_image5']]));
            //处理其他资质
            $update_other_image=implode(',',array_filter( [$datas['ShopUpdateRecord']['update_other_image'],$datas['ShopUpdateRecord']['update_other_image2'],$datas['ShopUpdateRecord']['update_other_image3'],$datas['ShopUpdateRecord']['update_other_image4'],$datas['ShopUpdateRecord']['update_other_image5']]));
            $shopModel = Shop::findOne(['id'=>$datas['shop_id']]);
            $shopApplyModel = ShopApply::findOne(['id'=>$datas['shop_id']]);
            if(isset($datas['id'])){
                $shopUpdateRecordModel = ShopUpdateRecord::findOne(['id'=>$datas['id']]);
            }else{
                $shopUpdateRecordModel = new ShopUpdateRecord();
            }
            $shopUpdateRecordModel->shop_id = trim($datas['shop_id']);
            $shopUpdateRecordModel->shop_name = $shopModel->name;//原店铺的名称
            $shopUpdateRecordModel->apply_name = $shopApplyModel->apply_name;//原法人姓名
            $shopUpdateRecordModel->apply_mobile = $shopApplyModel->apply_mobile;//原法人手机号
            $shopUpdateRecordModel->contacts_name = $shopApplyModel->contacts_name;//原联系人
            $shopUpdateRecordModel->contacts_mobile = $shopApplyModel->contacts_mobile;//原联系人手机号
            $shopUpdateRecordModel->identity_card_num = $shopApplyModel->identity_card_num;//原法人身份证号
            $shopUpdateRecordModel->registration_mark = $shopApplyModel->registration_mark;//原营业执照号码
            $shopUpdateRecordModel->company_name = $shopApplyModel->company_name;//原公司名称
            $shopUpdateRecordModel->panorama_image = $shopApplyModel->panorama_image;//原全景图
            $shopUpdateRecordModel->shop_image = $shopModel->shop_image;//原门脸图
            $shopUpdateRecordModel->identity_card_front = $shopApplyModel->identity_card_front;//原身份证正面照
            $shopUpdateRecordModel->identity_card_back = $shopApplyModel->identity_card_back;//原身份证背面照
            $shopUpdateRecordModel->agent_identity_card_front = $shopApplyModel->agent_identity_card_front;//原代理人身份证背面照
            $shopUpdateRecordModel->agent_identity_card_back = $shopApplyModel->agent_identity_card_back;//原代理人身份证背面照
            $shopUpdateRecordModel->area_id = $shopModel->area;//原地区的id
            $shopUpdateRecordModel->area_name = $shopModel->area_name;//原地区的名称
            $shopUpdateRecordModel->address = $shopModel->address;//原详细地址

            $shopUpdateRecordModel->update_shop_name = trim($datas['update_shop_name']);//更新后的店铺名称
            $shopUpdateRecordModel->update_apply_name = trim($datas['update_apply_name']);//更新后的法人姓名
            $shopUpdateRecordModel->update_apply_mobile = trim($datas['update_apply_mobile']);//更新后的法人手机
            $shopUpdateRecordModel->update_contacts_name = trim($datas['update_contacts_name']);//更新后的联系人
            $shopUpdateRecordModel->update_contacts_mobile = trim($datas['update_contacts_mobile']);//更新后的联系人手机
            $shopUpdateRecordModel->update_identity_card_num = trim($datas['update_identity_card_num']);//更新后的法人身份证号码
            $shopUpdateRecordModel->update_registration_mark = trim($datas['update_registration_mark']);//更新后的统一社会信用码

            $shopUpdateRecordModel->update_company_name = trim($datas['update_company_name']);//更新后的公司名称
            $shopUpdateRecordModel->update_identity_card_front =$datas['ShopUpdateRecord']['update_identity_card_front'];//修改后的法人身份证正面照
            $shopUpdateRecordModel->update_identity_card_back =$datas['ShopUpdateRecord']['update_identity_card_back'];//修改后的法人身份证正面照
            $shopUpdateRecordModel->update_agent_identity_card_front =$datas['ShopUpdateRecord']['update_agent_identity_card_front'];//修改后的代理人身份证正面照
            $shopUpdateRecordModel->update_agent_identity_card_back =$datas['ShopUpdateRecord']['update_agent_identity_card_back'];//修改后的代理人身份证正面照
            $shopUpdateRecordModel->update_panorama_image = $datas['ShopUpdateRecord']['update_panorama_image'];//更新后全景图
            $shopUpdateRecordModel->business_licence =$shopApplyModel->business_licence;;//更新前的营业执照
            $shopUpdateRecordModel->update_business_licence =$datas['ShopUpdateRecord']['update_business_licence'];//更新后的营业执照
            $shopUpdateRecordModel->authorize_image =$shopApplyModel->authorize_image;//原授权图片
            $shopUpdateRecordModel->update_authorize_image =$update_authorize_image;//更新后授权图片
            $shopUpdateRecordModel->other_image =$shopApplyModel->other_image;//原其他图片
            $shopUpdateRecordModel->update_other_image =$update_other_image;//更新后其他图片

            $shopUpdateRecordModel->update_shop_image = $datas['ShopUpdateRecord']['update_shop_image'];//更新后门脸图

            $shopUpdateRecordModel->update_area_id = $datas['ShopUpdateRecord']['town'] ? $datas['ShopUpdateRecord']['town'] :0;//修改后的店铺地区ID
            if(isset($datas['ShopUpdateRecord']['town']) != 0){
                $shopUpdateRecordModel->update_area_name = str_replace(' ','',SystemAddress::getAreaNameById($datas['ShopUpdateRecord']['town']));//修改后的店铺地区名称
            }
            $shopUpdateRecordModel->update_address = trim($datas['update_address']);//更新后详细地址

            $shopUpdateRecordModel->examine_status =0;//审核状态
            $shopUpdateRecordModel->create_user_name =Yii::$app->user->identity->username;//发起人姓名
            $shopUpdateRecordModel->save();

            if(isset($datas['id'])){
                $shopUpdateRecordId = $datas['id'];
            }else{
                $shopUpdateRecordId = Yii::$app->db->getLastInsertID();
            }

            //审核日志
            $LogExamineModel = new LogExamine();
            $LogExamineModel->examine_key = 8;
            $LogExamineModel->foreign_id  = $shopUpdateRecordId;
            $LogExamineModel->create_user_id = $user_id = Yii::$app->user->identity->getId();
            $LogExamineModel->create_user_name = Yii::$app->user->identity->username;
            $LogExamineModel->save(false);

            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>8,'msg'=>'操作失败']);
        }
    }

    //更换法人审核
    public static function examinerecord($model, $status, $desc,$member=''){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //yl_log_examine审核日志
            $logModel = new LogExamine();
            $logModel->examine_key = 8;
            $logModel->foreign_id = $model->id;
            $logModel->examine_result = $status;
            $logModel->examine_desc = $desc;
            $logModel->create_user_id = Yii::$app->user->identity->getId();
            $logModel->create_user_name = Yii::$app->user->identity->username;
            $res = $logModel->save();

            ShopUpdateRecord::updateAll(['examine_status'=>$status],['id'=>$model->id]);//修改审核状态
            if($status == 1){
                ShopUpdateRecord::updateAll(['examine_at'=>date('Y-m-d H:i:s')],['id'=>$model->id]);//添加审核通过时间
                //审核通过以后修改shop
                $smodel = Shop::findOne(['id'=>$model->shop_id]);
                $smodel->shop_member_id = $member->id;
                if(!empty($model->update_shop_name)){
                    $smodel->name = $model->update_shop_name;
                }
                if(!empty($model->update_shop_image)){
                    $smodel->shop_image = $model->update_shop_image;
                }
                if(!empty($model->update_area_id)){
                    $smodel->area = $model->update_area_id;
                }
                if(!empty($model->update_area_name)){
                    $smodel->area_name = $model->update_area_name;
                    $area=explode(' ',trim(SystemAddress::getAreaNameById($model->update_area_id)));
                    $smodel->shop_province = $area[0];
                    $smodel->shop_city = $area[1];
                    $smodel->shop_area = $area[2];
                    $smodel->shop_street = $area[3];
                }
                if(!empty($model->update_address)){
                    $smodel->address = $model->update_address;
                    //获取坐标
                    $addarray = ToolsClass::getLngLat(str_replace(" ","",$model->update_area_name.$model->update_address));
                    $smodel->longitude = $addarray['lng'];//'经度(国标)',
                    $smodel->latitude = $addarray['lat'];//'纬度(国标)',
                    $smodel->bd_longitude = $addarray['bd_lng'];//'经度(百度标准)',
                    $smodel->bd_latitude = $addarray['bd_lat'];//'维度(百度标准)',
                }

                $smodel->save();
                //审核通过以后修改shop_apply
                $amodel = ShopApply::findOne(['id'=>$model->shop_id]);
                $amodel->apply_name = $model->update_apply_name;//法人姓名
                $amodel->apply_mobile = $model->update_apply_mobile;//法人电话
                $amodel->identity_card_num = $model->update_identity_card_num;//法人身份证号码
                if(!empty($model->update_registration_mark)){//更新后的统一社会信用码
                    $amodel->registration_mark = $model->update_registration_mark;
                }
                if(!empty($model->update_company_name)){//更新后的公司名称
                    $amodel->company_name = $model->update_company_name;
                }
                if(!empty($model->update_contacts_name)){//联系人姓名
                    $amodel->contacts_name = $model->update_contacts_name;
                }
                if(!empty($model->update_contacts_mobile)){//联系人电话
                    $amodel->contacts_mobile = $model->update_contacts_mobile;
                }
                if(!empty($model->update_identity_card_front)){//法人身份证正面照
                    $amodel->identity_card_front = $model->update_identity_card_front;
                }
                if(!empty($model->update_identity_card_back)){//法人身份证背面照
                    $amodel->identity_card_back = $model->update_identity_card_back;
                }
                if(!empty($model->update_agent_identity_card_front)){//代理人身份证正面照
                    $amodel->agent_identity_card_front = $model->update_agent_identity_card_front;
                }
                if(!empty($model->update_agent_identity_card_back)){//代理人身份证背面照
                    $amodel->agent_identity_card_back = $model->update_agent_identity_card_back;
                }
                if(!empty($model->update_business_licence)){//营业执照
                    $amodel->business_licence = $model->update_business_licence;
                }
                if(!empty($model->update_authorize_image)){//原授权图片
                    $amodel->authorize_image = $model->update_authorize_image;
                }
                if(!empty($model->update_other_image)){//原其他原片
                    $amodel->other_image = $model->update_other_image;
                }
                if(!empty($model->update_panorama_image)){//店铺全景图
                    $amodel->panorama_image = $model->update_panorama_image;
                }
                $amodel->save();
                $screenid = Screen::find()->where(['shop_id'=>$model->shop_id])->select('id,shop_id,software_number')->asArray()->one();
                RedisClass::rpush('list_json_get_coordinate_to_mongo',json_encode(['shop_id'=>$model->shop_id,'software_number'=>$screenid['software_number']]),1);
            }
            $transaction->commit();
            return 1;
        }catch (Exception $e){
            var_dump($e->getMessage());die;
            $transaction->rollBack();
            Yii::error($e->getMessage(),'error');
            return false;
        }
    }
}
