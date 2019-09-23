<?php

namespace cms\modules\screen\models;

use Yii;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
/**
 * This is the model class for table "yl_shop_screen_advert_maintain".
 *
 * @property string $id
 * @property string $mongo_id  mongo集合中的ID
 * @property string $shop_id 店铺ID
 * @property string $apply_name 法人姓名
 * @property string $apply_mobile 法人电话
 * @property string $shop_name 店铺名称
 * @property string $shop_image 店铺图片
 * @property string $shop_area_id 店铺所在的地区ID
 * @property string $shop_area_name 店铺所在地区
 * @property string $shop_address 店铺所在地区
 * @property string $screen_number 屏幕数量
 * @property string $create_user_id 申请人ID
 * @property string $create_user_name 申请人姓名
 * @property int $status 状态(0、待指派 1、待维护 2、维护完成)
 * @property string $install_member_id 安装人ID
 * @property string $install_member_name 安装人姓名
 * @property string $install_finish_at 安装完成时间
 * @property string $create_at 创建时间
 * @property string $assign_at 指派时间
 * @property string $assign_time 指派时间(精确人时分秒,用于排序)
 * @property string $problem_description 问题描述
 * @property string $images 维护图片
 */
class ShopScreenAdvertMaintain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_shop_screen_advert_maintain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'shop_area_id', 'screen_number', 'create_user_id', 'status', 'install_member_id'], 'integer'],
            [['apply_name', 'apply_mobile', 'shop_image', 'shop_area_name', 'create_user_id', 'create_user_name'], 'required'],
            [['install_finish_at', 'create_at', 'assign_at', 'assign_time'], 'safe'],
            [['images'], 'string'],
            [['mongo_id'], 'string', 'max' => 24],
            [['apply_name', 'apply_mobile', 'create_user_name', 'install_member_name'], 'string', 'max' => 20],
            [['shop_name', 'shop_image', 'shop_area_name', 'shop_address'], 'string', 'max' => 255],
            [['problem_description'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mongo_id' => 'Mongo ID',
            'shop_id' => '店铺ID',
            'apply_name' => '联系人姓名',
            'apply_mobile' => '联系人电话',
            'shop_name' => '店铺名称',
            'shop_image' => '店铺图片',
            'shop_area_id' => '店铺所在的地区ID',
            'shop_area_name' => '店铺所在地区',
            'shop_address' => '详细地址',
            'screen_number' => '屏幕数量',
            'create_user_id' => '申请人ID',
            'create_user_name' => '申请人',
            'status' => 'Status',
            'install_member_id' => '安装人ID',
            'install_member_name' => '安装人',
            'install_finish_at' => '安装完成时间',
            'create_at' => '创建时间',
            'assign_at' => '指派时间',
            'assign_time' => '指派时间',
            'problem_description' => '问题描述',
            'images' => '维护图片',
        ];
    }

    public function getLowerHair($data){
        /*$url = "http://api.ts-admin.bjyltf.com/front/shop/republish/403";
        $urldata = file_get_contents($url);
        if($urldata['code']!==0){
            return json_encode(['code'=>5,'msg'=>'操作失败！']);
        }*/
        if(empty($data)){
            return json_encode(['code'=>2,'msg'=>'获取参数失败！']);
        }
        $ShopModel = Shop::findOne(['id'=>$data['shop_id']]);
        $ShopApplyModel = ShopApply::findOne(['id'=>$data['shop_id']]);
        if(empty($ShopModel) || empty($ShopApplyModel)){
            return json_encode(['code'=>3,'data'=>'非法数据']);
        }
        $model = new self();
        $model->mongo_id = $data['mongo_id'];
        $model->shop_id = $data['shop_id'];
        if($ShopModel->headquarters_id==0){
            $model->apply_name = $ShopApplyModel->apply_name;
            $model->apply_mobile = $ShopApplyModel->apply_mobile;
        }else{
            $model->apply_name = $ShopApplyModel->contacts_name;
            $model->apply_mobile = $ShopApplyModel->contacts_mobile;
        }
        $model->shop_name = $ShopModel->name;
        $model->shop_image = $ShopApplyModel->panorama_image;
        $model->shop_area_id = $ShopModel->area;
        $model->shop_area_name = $ShopModel->area_name;
        $model->shop_address = $ShopModel->address;
        $model->screen_number = $ShopModel->screen_number;
        $model->create_user_id = Yii::$app->user->identity->getId();
        $model->create_user_name =Yii::$app->user->identity->username;
        if($model->save()){
            $collection = Yii::$app->mongodb->getCollection ('order_arrival_report');
            $collection->update(['shop_id' =>(int)$data['shop_id'],'throw_over'=>0],['maintain_id'=>(float)$model->id]);
        }else{
            return json_encode(['code'=>4,'msg'=>'操作失败']);
        }
        return json_encode(['code'=>1,'msg'=>'操作成功']);
    }

    public function getCancelLowerHair($data){
        if(empty($data)){
            return json_encode(['code'=>'获取参数失败']);
        }
        $model = self::findOne(['id'=>$data['maintain_id']]);
        if($model->status!==0){
            return json_encode(['code'=>2,'msg'=>'已指派维护不能取消下发！']);
        }
        $dbdata = self::deleteAll(['id'=>$data['maintain_id']]);
        $collection = Yii::$app->mongodb->getCollection ('order_arrival_report');
        $mongodata = $collection->update(['maintain_id' =>(int)$data['maintain_id']],['maintain_id'=>(float)0]);
        if($dbdata && $mongodata){
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }

    public function getMaintainAssign($data){
        if(empty($data)){
            return json_encode(['code'=>2,'msg'=>'非法数据']);
        }
        $model = ShopScreenAdvertMaintain::findOne(['id'=>$data['id']]);
        $model->install_member_id = $data['member_id'];
        $model->install_member_name = $data['name'];
        $model->install_member_mobile = $data['mobile'];
        $model->assign_at= date('Y-m-d');
        $model->assign_time= date('Y-m-d H:i:s');
        $model->status=1;
        if($model->save()){
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }else{
            return json_encode(['code'=>3,'msg'=>'操作失败']);
        }
    }

    public function getCancelMaintainAssign($data){
        if(empty($data)){
            return json_encode(['code'=>2,'msg'=>'非法数据']);
        }
        $model = ShopScreenAdvertMaintain::findOne(['id'=>$data['id']]);
        $model->install_member_id = 0;
        $model->install_member_name = '';
        $model->install_member_mobile = '';
        $model->assign_at= '0000-00-00';
        $model->assign_time= '0000-00-00 00:00:00';
        $model->status=0;
        if($model->save()){
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }else{
            return json_encode(['code'=>3,'msg'=>'操作失败']);
        }
    }
}
