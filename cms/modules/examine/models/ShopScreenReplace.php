<?php

namespace cms\modules\examine\models;

use cms\models\LogAccount;
use cms\models\LogExamine;
use cms\modules\config\models\SystemConfig;
use cms\modules\ledmanage\models\SystemDevice;
use cms\modules\member\models\MemberInfo;
use cms\modules\member\models\MemberInstallHistory;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\Shop;
use cms\modules\member\models\Member;
use cms\modules\shop\models\ShopApply;
use Yii;
use yii\base\Exception;
use common\libs\RedisClass;
use yii\db\Expression;

/**
 * This is the model class for table "yl_shop_screen_replace".
 *
 * @property string $id
 * @property string $shop_id 店铺ID
 * @property string $shop_name 店铺名称
 * @property string $shop_area_id 店铺所在的地区ID
 * @property string $shop_address 店铺所在地区
 * @property string $install_member_id 安装人ID
 * @property string $install_member_name 安装人姓名
 * @property string $install_finish_at 安装完成时间
 * @property string $install_price 安装费
 * @property int $replace_screen_number 申请更换的屏幕数量
 * @property string $create_user_id 申请人ID
 * @property string $create_user_name 申请人姓名
 * @property int $status 状态(0.申请更换，1.待安装(指派)，2.待审核，3.审核未通过，4.换屏完成)
 * @property string $create_at 创建时间
 * @property string $assign_at 指派时间
 */
class ShopScreenReplace extends \yii\db\ActiveRecord
{
    public $province;
    public $city;
    public $area;
    public $town;
    public $zhipai_status;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_shop_screen_replace';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'shop_area_id', 'install_member_id', 'install_price', 'replace_screen_number', 'create_user_id', 'status','province','city','area','town'], 'integer'],
            [['install_finish_at', 'create_at', 'assign_at'], 'safe'],
            [['create_user_id', 'create_user_name'], 'required'],
            [['shop_name', 'shop_address'], 'string', 'max' => 255],
            [['install_member_name', 'create_user_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'shop_id' => '商家编号',
            'shop_name' => '商家名称',
            'shop_area_id' => '地区ID',
            'shop_area_name' => '所属地区',
            'shop_address' => '详细地址',
            'install_member_id' => '安装人ID',
            'install_member_name' => '安装人姓名',
            'install_finish_at' => '安装完成时间',
            'install_price' => 'Install Price',
            'replace_screen_number' => '安装屏幕数',
            'create_user_id' => 'Create User ID',
            'create_user_name' => 'Create User Name',
            'status' => '状态',
            'create_at' => '申请更换时间',
            'assign_at' => '指派时间',
            'assign_time' => '指派时间',
        ];
    }

    //状态
    public static function getStatus($num){
        $srr = [
            '0'=>'待指派',
            '1'=>'待更换',
            '2'=>'待审核',
            '3'=>'审核未通过',
            '4'=>'已完成',
        ];
        return array_key_exists($num,$srr) ? $srr[$num] : '---';
    }

    //维护状态
    public static function getMaintainType($type){
        $srr = [
            '1'=>'店铺入驻',
            '2'=>'更换屏幕',
            '3'=>'拆除屏幕',
            '4'=>'新增屏幕',
        ];
        return array_key_exists($type,$srr) ? $srr[$type] : '---';
    }

    //更换屏幕
    public static function replaceScreen($bad){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //修改shop换屏状态
            $shopmodel = Shop::findOne(['id'=>$bad['shop_id']]);
            $shopmodel->replace_screen_status += 1;
            $shopmodel->save();
            $shopApplymodel = ShopApply::findOne(['id'=>$bad['shop_id']]);

            //修改需要更换的屏幕的状态
            //Screen::updateAll(['status'=>3,'offline_time'=>date("Y-m-d H:i:s",time())],['shop_id'=>$bad['shop_id'],'number'=>$bad['number']]);

            //添加换屏申请记录
            $rescreenModel = new ShopScreenReplace();
            $rescreenModel->maintain_type = $bad['maintain_type'];
            $rescreenModel->shop_id = $bad['shop_id'];
            $rescreenModel->shop_member_id = $shopmodel->shop_member_id;
            $rescreenModel->apply_name = $shopApplymodel->apply_name;
            $rescreenModel->apply_mobile = $shopApplymodel->apply_mobile;
            $rescreenModel->shop_name = $shopmodel->name;
            $rescreenModel->shop_image = $shopmodel->shop_image;
            $rescreenModel->shop_area_id = $shopmodel->area;
            $rescreenModel->shop_area_name = $shopmodel->area_name;
            $rescreenModel->shop_address = $shopmodel->address;
            $rescreenModel->create_user_id = Yii::$app->user->identity->getId();
            $rescreenModel->create_user_name = Yii::$app->user->identity->username;
            $rescreenModel->replace_screen_number = $bad['replace_screen_number'];
            $rescreenModel->create_at = date('Y-m-d H:i:s',time());
            $rescreenModel->lable_id = $shopmodel->lable_id;
            $rescreenModel->description = $bad['description'];
            $rescreenModel->save();
            //$insertid = Yii::$app->db->getLastInsertID();
            $transaction->commit();
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return false;
        }
    }

    //店铺所有维护记录
    public static function getReplaceScreenList($shopid){
        $rescreen = self::find()->where(['shop_id'=>$shopid])->asArray()->all();
        return $rescreen;
    }

    /**
     * 店铺审核
     */
    public static function examineResScreen($model, $status, $desc){
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

            //yl_shop_screen_replace
            $shopModel = Shop::findOne(['id'=>$model->shop_id]);//店铺信息
            if($status == 1){
                //安装人待安装记录减
                $memberlist = MemberInfo::findOne(['member_id'=>$model->install_member_id]);
                $memberlist->wait_shop_number -=1;//待安装的店铺数量-1
                $memberlist->wait_screen_number -=$model->replace_screen_number;//待安装的屏幕数量-屏幕数
                $memberlist->save();

                //修改换屏表数据
                $model->install_finish_at = date('Y-m-d H:i:s',time());
                $model->examine_user_id=Yii::$app->user->identity->getId();
                $model->examine_user_name=Yii::$app->user->identity->username;
                $model->status=4;

                if($model->maintain_type == 3){
                    $screenid = explode(',',$model->remove_device_number);
                    $screen = Screen::find()->where(['number'=>$screenid])->asArray()->all();
                    if(count($screen) != count($screenid)){
                        return 3;//拆除屏幕数量不一致，操作失败
                    }
                    Screen::deleteAll(['number'=>$screenid]);//拆除屏幕，审核时删除屏幕表已安装的屏幕
                    Shop::updateAll(['screen_number'=>new Expression("screen_number - {$model->replace_screen_number}")],['id' => $model->shop_id]);//shop的实际屏幕数-拆除屏幕数
                }
                if($model->maintain_type == 4){
                    Shop::updateAll(['screen_number'=>new Expression("screen_number + {$model->replace_screen_number}")],['id' => $model->shop_id]);//shop的实际屏幕数+新增屏幕数
                }

                //给安装钱
                if(!LogAccount::writeLog(3,$model->install_price,1,'屏幕拆装费',$model->install_member_id,$model->replace_screen_number,$model->shop_area_id,$model->shop_name)){
                    throw new Exception("[error]创建屏幕拆装费收入日志失败");
                }

                //写安装记录yl_member_install_history
                $hisModel = new MemberInstallHistory();
                $hisModel->member_id = $model->install_member_id;
                $hisModel->shop_id = $model->shop_id;
                $hisModel->shop_name = $model->shop_name;
                $hisModel->replace_id = $model->id;
                $hisModel->area_name = $model->shop_area_name;
                $hisModel->address = $model->shop_address;
                $hisModel->screen_number = $model->replace_screen_number;
                $hisModel->type = $model->maintain_type;
                $hisModel->create_at = date('Y-m-d',time());
                $hisModel->shop_image = $shopModel->shop_image;
                $hisModel->save();

            }else{
                $model->status=3;
            }
            //新增or拆除or更换屏幕更新redis地区屏幕数
            $shengid = substr($model->shop_area_id,0,5);//省
            $shiid = substr($model->shop_area_id,0,7);//市
            $quid = substr($model->shop_area_id,0,9);//区
            $area = $model->shop_area_id;//街道
            if($model->install_device_number != ''){
                $screen_number_add =count(explode(',',$model->install_device_number));//装屏幕数量
            }else{
                $screen_number_add = 0;
            }
            if($model->remove_device_number != ''){
                $screen_number_del =count(explode(',',$model->remove_device_number));//拆屏幕数量
            }else{
                $screen_number_del = 0;
            }
            $screen_number = $screen_number_add-$screen_number_del;
            if($status == 1){
                $redisObj = Yii::$app->redis;
                $redisObj->select(3);
                //省
                $keysheng = "system_screen_number:".$shengid;
                $asheng = $redisObj->GET($keysheng);
                $bsheng = json_decode($asheng,true);
                if($screen_number_del != $shopModel->screen_number){
                    $csheng = [
                        'screen_number'=>(int)($bsheng['screen_number']+$screen_number),
                        'shop_number'=>$bsheng['shop_number'],
                        'mirror_number'=>$bsheng['mirror_number'],
                    ];
                }else{
                    $csheng = [
                        'screen_number'=>(int)($bsheng['screen_number']+$screen_number),
                        'shop_number'=>$bsheng['shop_number']-1,
                        'mirror_number'=>$bsheng['mirror_number']-$shopModel->mirror_account,
                    ];
                }

                $redisObj->SET($keysheng,json_encode($csheng));
                //市
                $keyshi = "system_screen_number:".$shiid;
                $ashi = $redisObj->GET($keyshi);
                $bshi = json_decode($ashi,true);
                if($screen_number_del != $shopModel->screen_number) {
                    $cshi = [
                        'screen_number' => (int)($bshi['screen_number'] + $screen_number),
                        'shop_number' => $bshi['shop_number'],
                        'mirror_number' => $bshi['mirror_number'],
                    ];
                }else{
                    $cshi = [
                        'screen_number' => (int)($bshi['screen_number'] + $screen_number),
                        'shop_number' => $bshi['shop_number']-1,
                        'mirror_number' => $bshi['mirror_number']-$shopModel->mirror_account,
                    ];
                }
                $redisObj->SET($keyshi,json_encode($cshi));

                //区
                $keyqu = "system_screen_number:".$quid;
                $aqu = $redisObj->GET($keyqu);
                $bqu = json_decode($aqu,true);
                if($screen_number_del != $shopModel->screen_number) {
                    $cqu = [
                        'screen_number' => (int)($bqu['screen_number'] + $screen_number),
                        'shop_number' => $bqu['shop_number'],
                        'mirror_number' => $bqu['mirror_number'],
                    ];
                }else{
                    $cqu = [
                        'screen_number' => (int)($bqu['screen_number'] + $screen_number),
                        'shop_number' => $bqu['shop_number']-1,
                        'mirror_number' => $bqu['mirror_number']-$shopModel->mirror_account,
                    ];
                }
                $redisObj->SET($keyqu,json_encode($cqu));

                //街道
                $keyarea = "system_screen_number:".$area;
                $aarea = $redisObj->GET($keyarea);
                $barea = json_decode($aarea,true);
                if($screen_number_del != $shopModel->screen_number) {
                    $carea = [
                        'screen_number'=>(int)($barea['screen_number']+$screen_number),
                        'shop_number'=>$barea['shop_number'],
                        'mirror_number'=>$barea['mirror_number'],
                    ];
                }else{
                    $carea = [
                        'screen_number'=>(int)($barea['screen_number']+$screen_number),
                        'shop_number'=>$barea['shop_number']-1,
                        'mirror_number'=>$barea['mirror_number']-$shopModel->mirror_account ,
                    ];
                }
                $redisObj->SET($keyarea,json_encode($carea));
            }
            $re = $model->save();

            //更换屏幕写redis
            if($status == 1 && $model->maintain_type == 2){
                if($model->replace_screen_number>0) {
                    foreach (explode(',', $model->remove_device_number) as $ku => $vu) {
                        $rid = SystemDevice::findOne(['device_number' => $vu]);
                        $redisarray = [
                            'shop_id' => $model->shop_id,
                            'software_number' => $rid->software_id,
                            'update_software_number' => explode(',', $model->install_software_number)[$ku],
                            'type' => 'update',
                        ];
                        RedisClass::rpush('screen_update_arrival_report', json_encode($redisarray),1);
                    }
                }
            }

            //拆除的屏幕写redis
            if($status == 1 && $model->maintain_type == 3){
                $redisObj = Yii::$app->redis;
                $redisObj->select(1);
                $delscreen = [
                    'type'=>'delete',
                    'data'=> implode(',',array_column($screen,'software_number')),
                ];
                $redisObj->rpush('system_push_data_to_device_list',json_encode($delscreen));
                if($model->replace_screen_number>0){
                    foreach (explode(',', $model->remove_device_number) as $kd => $vd) {
                        $rid = SystemDevice::findOne(['device_number' => $vd]);
                        $redisarray = [
                            'shop_id' => $model->shop_id,
                            'software_number' => $rid->software_id,
                            'type' => 'delete',
                        ];
                        RedisClass::rpush('screen_update_arrival_report', json_encode($redisarray),1);
                    }
                }

                $newarray = ['type'=>'delete','data'=>["area_id"=>$area,"screen_number"=>$model->replace_screen_number,"mirror_number"=>$shopModel->mirror_account,'space_screen_number'=>$shopModel->screen_number-$model->replace_screen_number]];
                RedisClass::rpush('system_create_shop_list',json_encode($newarray),4);
            }

            if($status == 1 && $model->maintain_type == 4){
                $newarray = ['type'=>'update','data'=>["area_id"=>$shopModel->area,"screen_number"=>$model->replace_screen_number,"mirror_number"=>$shopModel->mirror_account]];
                RedisClass::rpush('system_create_shop_list',json_encode($newarray),4);

                if($model->replace_screen_number>0) {
                    foreach (explode(',', $model->remove_device_number) as $kc => $vc) {
                        $redisarray = [
                            'shop_id' => $model->shop_id,
                            'software_number' => explode(',', $model->install_software_number)[$kc],
                            'type' => 'create',
                        ];
                        RedisClass::rpush('screen_update_arrival_report', json_encode($redisarray),1);
                    }
                }
            }

            //写入redis push_shop_list
            $shopmodel=Shop::findOne(['id'=>$model->shop_id]);
            $push_shop_list['shop_id']=$model->shop_id;
            $push_shop_list['head_id']=$shopmodel->headquarters_id;
            RedisClass::rpush("push_shop_custom_advert_list",json_encode($push_shop_list),5);

            $transaction->commit();
            return 1;
        }catch (Exception $e){
            var_dump($e->getMessage());die;
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return false;
        }
    }

    //更新今日补贴金额
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'install_member_id'])->select('id, mobile');
    }

    //获取店铺详情
    public function getShops(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }

    //获取店铺公司详情
    public function getShopApplys(){
        return $this->hasOne(ShopApply::className(),['id'=>'shop_id']);
    }

    //获取店铺业务员上级人员详情
    public function getmembers(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id'])->joinWith('parentmembers');
    }
}
