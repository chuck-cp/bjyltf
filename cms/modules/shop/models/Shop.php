<?php

namespace cms\modules\shop\models;

use cms\models\LogAccount;
use cms\models\SystemAddress;
use cms\modules\config\models\SystemConfig;
use cms\modules\examine\models\ActivityDetail;
use cms\modules\examine\models\ShopContract;
use cms\modules\examine\models\ShopHeadquartersList;
use cms\modules\examine\models\ShopScreenReplace;
use cms\modules\member\models\Member;
use cms\modules\member\models\MemberAccount;
use cms\modules\member\models\MemberInfo;
use cms\modules\member\models\MemberInstallHistory;
use cms\modules\member\models\MemberTeam;
use cms\modules\member\models\MemberTeamList;
use cms\modules\screen\models\Screen;
use common\libs\ToolsClass;
use Yii;
use cms\models\LogExamine;
use yii\base\Exception;
use common\libs\RedisClass;
use cms\modules\examine\models\Activity;
/**
 * This is the model class for table "{{%shop}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $member_name
 * @property string $admin_member_id
 * @property string $shop_image
 * @property string $name
 * @property string $area
 * @property string $area_name
 * @property integer $apply_screen_number
 * @property integer $screen_number
 * @property integer $error_screen_number
 * @property integer $status
 * @property integer $screen_status
 * @property string $create_at
 * @property double $acreage
 * @property integer $apply_client
 */
class Shop extends \yii\db\ActiveRecord
{
    public $way;
    public $province;
    public $city;
    public $town;
    public $apply_code;
    public $dynamic_code;
    public $apply_mobile;
    public $apply_name;
    public $create_at_end;
    public $mobile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'admin_member_id', 'apply_screen_number', 'screen_number', 'error_screen_number', 'status', 'screen_status', 'apply_client','mirror_account','delivery_status','install_member_id','shop_operate_type','headquarters_id','headquarters_list_id'], 'integer'],
            [['create_at','way','province','city', 'area','town','dynamic_code','apply_mobile','apply_name', 'apply_code','examine_user_name','examine_user_group','mobile'], 'safe'],
            [['acreage'], 'number'],
            [['address'],'string','max' => 255],
            [['member_name'], 'string', 'max' => 50],
            [['shop_image', 'area_name'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '店铺ID',
            'member_id' => '用户ID',
            'member_name' => '业务合作人姓名',
            'member_mobile' => '业务合作人手机号',
            'member_price' => '业务员合作费用',
            'member_reward_price' => '业务员奖励金',
            'admin_member_id' => '管理人ID',
            'shop_member_id' => '店铺拥有人的用户ID',
            'wx_member_id' => '微信端的用户ID',
            'shop_image' => '店铺门脸',
            'name' => '店铺名称',
            'area' => '店铺所在地区',
            'area_name' => '地区名称',
            'address' => '详细地址',
            'apply_screen_number' => '申请的屏幕数量',
            'screen_number' => '实际屏幕数量',
            'error_screen_number' => '失联的屏幕数量',
            'status' => '状态',
            'screen_status' => '屏幕状态',
            'install_status' => '店铺安装来源状态',
            'delivery_status' => '设备发货状态',
            'create_at' => '创建时间',
            'acreage' => '店铺面积（平方米）',
            'apply_client' => '申请客户端',
            'mirror_account' => '店铺镜面数量',
            'shop_type' => '店铺类型',
            'install_team_id' => '安装团队ID',
            'install_member_id' => '安装人的ID',
            'install_member_name' => '安装人姓名',
            'install_price' => '安装的费用',
            'install_finish_at' => '店铺安装完成时间',
            'install_assign_at' => '指派时间',
            'last_examine_user_id' => '最后审核人ID',
            'examine_user_group' => '审核人员所在的组',
            'examine_user_name' => '审核人员姓名',
            'examine_number' => '审核次数',
            'agreement_name' => '协议名称',
            'mobile' => 'Mobile',
        ];
    }

    /**
     * 根据状态值获取状态描述
     * 0、待审核 1、审核通过 2、审核未通过 3、安装完成
     */
    public static function getStatusByNum($num,$need=false){
        $srr = [
            '0'=>'待审核',
            '1'=>'审核未通过',
            '2'=>'待安装',
            '3'=>'安装待审核',
            '4'=>'安装未通过',
            '5'=>'安装完成',
            '6'=>'关闭店铺',
        ];
        if($need){return $srr;}
        return array_key_exists($num,$srr) ? $srr[$num] : '未设置';
    }
    /**
     * 二次审核状态
     * 0、待审核 1、一审通过
     */
    public static function getExamineByNum($num){
        $srr = [
            '0'=>'待一审',
            '1'=>'待二审',
            '2'=>'审核完成',
        ];
        return array_key_exists($num,$srr) ? $srr[$num] : '未设置';
    }
    /*
     * 获取安装人手机号
     */
    public function getInstallMobile(){
        if($this->install_member_id){
            return Member::findOne($this->install_member_id)->mobile;
        }
        return '---';
    }
    /**
     * 根据状态值获取状态描述
     * 0、待配货 1、代发货 2、已发货
     */
    public static function getDeliveryByNum($num){
        $srr = [
            '1'=>'待配货',
            '2'=>'待发货',
            '3'=>'已发货',
        ];
        return array_key_exists($num,$srr) ? $srr[$num] : '未设置';
    }
    //
    public function reformExport($column,$value){
        switch ($column){
            case 'status':
                return self::getStatusByNum($value);
                break;
            case 'screen_status':
                return $value == 2 ? '异常' : '正常';
                break;
            case 'apply_client':
                return $value == 0 ? '手机端' : 'PC端';
                break;
            case 'area':
                return SystemAddress::getAreaNameById($value);
                break;
            case 'way':
                return $this->member_id > 0 ? '有推荐人' : '无推荐人';
                break;
            default :
                return $value."\t";
        }
    }
    //csv导出用
    public function checkFieldFormTable($field){
        $fields = [
            'apply_code'=>'apply',
            'dynamic_code' => 'apply',
            'apply_mobile' => 'apply',
            'apply_name' => 'apply',
        ];
        if(isset($fields[$field])){
            return $fields[$field];
        }
        return false;
    }
    /**
     * 店铺审核
     */
    public static function examineShop($model, $status, $desc){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //yl_log_examine审核日志
            $logModel = new LogExamine();
            $logModel->examine_key = 1;
            $logModel->foreign_id = $model->id;
            if($status == 2){
                $logModel->examine_result = 1;
            }else{
                $logModel->examine_result = 2;
            }
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

            //yl_shop
            if($status == 2){
                if($model->last_examine_user_id == Yii::$app->user->identity->getId()){
                    $transaction->rollBack();
                    return 4;//审核人重复
                }
                $model->examine_number+=1;
                $model->last_examine_user_id = Yii::$app->user->identity->getId();
                if($model->examine_number < 2){//一审or二审
                    $model->save();
                    $transaction->commit();
                    return 1;
                }

                if($model->shop_operate_type==3) {
                    $headlistModel = ShopHeadquartersList::findOne(['headquarters_id' => $model->headquarters_id, 'id' => $model->headquarters_list_id]);
                    if($headlistModel->shop_id==0){
                        $headlistModel->shop_id=$model->id;
                        $headlistModel->save();//店铺审核通过后，将店铺id写入总部分店表
                    }else{
                        return 3;//该分店已绑定店铺
                    }
                }
                //修改推荐活动相关数据
                //设备安装活动签约明细表 改变安装状态
                if(!empty($model->introducer_member_mobile)) {
                    //设备安装活动表加钱
                    $Activity = Activity::findOne(['member_mobile' => $model->introducer_member_mobile]);
                    $Activity->price += $model->introducer_member_price;
                    $Activity->save();
                    ActivityDetail::updateAll(['status' => 1],['id' => $model->activity_detail_id, 'status' => 0]);
                }
                //添加合同审核
                if($model->headquarters_id == 0){
                    $contract = new ShopContract();
                    $contract->shop_id = $model->id;
                    $contract->shop_type = 1;
                    $contract->create_at = date('Y-m-d H:i:s',time());
                    $contract->save(false);
                    //将新生成的合同id写入店铺表
                    $contractid = Yii::$app->db->getLastInsertID();
                    $model->contract_id = $contractid;
                }else{
                    $contract = ShopContract::find()->where(['shop_id'=>$model->headquarters_id,'shop_type'=>2])->asArray()->one();
                    if($contract){
                        $model->contract_id = $contract['id'];
                    }
                }
            }
            $model->last_examine_user_id = 0;
            $model->examine_number = 0;
            $model->status = $status;
            $model->shop_examine_at = date('Y-m-d H:i:s',time());
            $re = $model->save();

            //写redis
            //地址转换成坐标
            $addToCoordinate['shop_id'] = $model->id;
            $addToCoordinate['address'] = $model->area_name.$model->address;
            RedisClass::rpush("list_get_shop_coordinate",json_encode($addToCoordinate),1);

            $transaction->commit();
            return 2;
        }catch (Exception $e){
            $transaction->rollBack();
            Yii::error($e->getMessage(),'error');
            return false;
        }
    }

    /**
     *查看店铺审核驳回原因
     */
    public static function getRebutReason($foreign_id){

        $desc = LogExamine::find()->where(['foreign_id'=>$foreign_id,'examine_key'=>1])->orderBy('create_at desc')->select('id,foreign_id,examine_desc,create_at,create_user_name')->asArray()->all();
        return $desc;
    }
    /**
     *查看安装审核驳回原因
     */
    public static function getInstallReason($foreign_id){
        $desc = LogExamine::find()->where(['foreign_id'=>$foreign_id,'examine_result'=>2,'examine_key'=>1])->orderBy('create_at desc')->select('id,examine_desc')->asArray()->one();
        return $desc == true ? $desc['examine_desc'] : '暂无原因';
    }
    /**
     * 获取店铺申请表相关信息
     */
    public function getApply(){
        return $this->hasOne(ShopApply::className(),['id'=>'id']);
    }
    //管理员
    public function getAdmin(){
        return $this->hasOne(Member::className(),['id'=>'admin_member_id'])->select('id,name');
    }
    //安装人员
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'install_member_id'])->select('id,name,mobile');
    }
    //上级人员
    public function getParentmembers(){
        return $this->hasOne(Member::className(),['id'=>'parent_member_id'])->select('id,name,mobile');
    }
    //初始安装数量
    public function getShopreplace(){
        return $this->hasOne(ShopScreenReplace::className(),['shop_id'=>'id'])->where(['maintain_type'=>1]);
    }
    //查看店铺合同状态
    public function getShopContract(){
        return $this->hasOne(ShopContract::className(),['id'=>'contract_id']);
    }

    /**
     * @param $shopid
     * @return bool
     * @throws \yii\db\Exception
     * 关联member 获取上级伙伴信息
     */
    public function getParentMember(){
        return $this->hasOne(Member::className(),['id'=>'parent_member_id'])->select('id,name,mobile');
    }
    /*public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('id,name,mobile');
    }*/


    //确认安装二（联系费：150： 联系150，上级0 或者120，上级）
    public static function checkInstall($shopid)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //审核日志
            $log_examine = new LogExamine();
            $log_examine->examine_key=4;
            $log_examine->foreign_id=$shopid['shopid'];
            $log_examine->examine_result=1;//产品确认安装审核通过
            $log_examine->create_user_id = Yii::$app->user->identity->getId();
            $log_examine->create_user_name = Yii::$app->user->identity->username;
            $log_examine->save();

            $shopmodel = new Shop();//shop对象
            $shopObj = $shopmodel->findOne(['id'=>$shopid['shopid']]);
            $shopObj->examine_number += 1;//审核次数
            $shopObj->last_examine_user_id = Yii::$app->user->identity->getId();
            if($shopObj->examine_number < 2){//一审or二审
                $shopObj->save();
                $transaction->commit();
                return true;
            }

            $shopapplymodel = new ShopApply();//shopapply对象
            $shopappObj = $shopapplymodel->findOne(['id'=>$shopid['shopid']]);
            $roundnum = ToolsClass::str_rand(28-strlen($shopObj->id));//随机子字符串
            $agr = $shopObj->id.$roundnum.".pdf";//随机数协议
            if($shopObj->status != 3){
                throw new Exception("[error]安装状态错误！");
            }
            $shopObj->status=5;//修改安装店铺的状态
            if($shopObj->screen_number<=2){
                $shopObj->shop_type = 1;
            }elseif($shopObj->screen_number>=3 && $shopObj->screen_number<5){
                $shopObj->shop_type = 2;
            }elseif($shopObj->screen_number>=5){
                $shopObj->shop_type = 3;
            }
            $memberinfoModel=MemberInfo::findOne(['member_id'=>$shopObj->install_member_id]);
            if(empty($memberinfoModel)){
                throw new Exception("[error]无安装人员！");
            }
            $installprice = SystemConfig::getAreaInstallPrice($shopObj->area,'system_price_install_'.$memberinfoModel->company_electrician.'_');//查数据库设置的安装费
            $shopObj->install_price = $installprice*$shopObj->screen_number;//安装费=屏幕数*安装屏幕的单价
            $shopObj->install_finish_at = date('Y-m-d H:i:s');
            $shopObj->agreement_name = $agr;
            $shopObj->save();

            //修改安装地区状态
            $times = date('Y-m-d H:i:s');
            $shengid = substr($shopObj->area,0,5);//省
            $shiid = substr($shopObj->area,0,7);//市
            $quid = substr($shopObj->area,0,9);//区
            SystemAddress::updateAll(['is_buy'=>1,'install_at'=>$times],['id'=>$shengid,'is_buy'=>0]);//省
            SystemAddress::updateAll(['is_buy'=>1,'install_at'=>$times],['id'=>$shiid,'is_buy'=>0]);//市
            SystemAddress::updateAll(['is_buy'=>1,'install_at'=>$times],['id'=>$quid,'is_buy'=>0]);//区
            $srt = SystemAddress::updateAll(['is_buy'=>1,'install_at'=>$times],['id'=>$shopObj->area,'is_buy'=>0]);//镇

            //设别编号
            $newvalue = ['system_equipment_area'];
            $screenid = Screen::find()->where(['shop_id'=>$shopObj->id])->select('software_number,number')->asArray()->all();
            foreach($screenid as $ksid=>$vsid){
                $newvalue[]=$vsid['software_number'];
                $newvalue[]=$shopObj->area.",".$shopObj->id;
            }

            //协议所需数据谢redis
            $areaprice=[
                'price' => $shopappObj->apply_brokerage,//买断费
                'month_price' => $shopappObj->apply_brokerage_by_month//每月补助费
                ];

            //店铺买断费
            if(!LogAccount::writeLog(1,$areaprice['price'],1,'店铺费用',$shopObj->shop_member_id,0,0,$shopObj->name)){
                throw new Exception("[error]创建店铺买断费收入日志失败");
            }
            //安装联系费
            if($shopObj->member_id){
                //联系人
                if(!empty($shopObj->introducer_member_mobile)){
                    $price_name='店铺签约奖励金';
                }else{
                    $price_name='安装联系费';
                }
                if($shopObj->headquarters_id>0){
                    //判断总部合同是否通过
                    $contract = ShopContract::findOne(['shop_id'=>$shopObj->headquarters_id,'shop_type'=>2]);
                    if(empty($contract) || $contract->examine_status == 1){
                        if(!LogAccount::writeLog(2,$shopObj->member_price,1,$price_name,$shopObj->member_id,$shopObj->screen_number,$shopObj->area,$shopObj->name)){
                            throw new Exception("[error]创建".$price_name."收入日志失败");
                        }
                        //联系人上级
                        if(!LogAccount::writeLog(4,$shopObj->parent_member_price,1,'邀请人联系奖励金',$shopObj->parent_member_id,$shopObj->screen_number,$shopObj->area,$shopObj->name)){
                            throw new Exception("[error]创建邀请人奖励金收入日志失败");
                        }
                    }
                }elseif($shopObj->headquarters_id == 0){
                    //判断自营/租赁店铺合同是否通过
                    $contract = ShopContract::findOne(['shop_id'=>$shopObj->id,'shop_type'=>1]);
                    if(empty($contract) || $contract->examine_status == 1){
                        if(!LogAccount::writeLog(2,$shopObj->member_price,1,$price_name,$shopObj->member_id,$shopObj->screen_number,$shopObj->area,$shopObj->name)){
                            throw new Exception("[error]创建".$price_name."收入日志失败");
                        }
                        //联系人上级
                        if(!LogAccount::writeLog(4,$shopObj->parent_member_price,1,'邀请人联系奖励金',$shopObj->parent_member_id,$shopObj->screen_number,$shopObj->area,$shopObj->name)){
                            throw new Exception("[error]创建邀请人奖励金收入日志失败");
                        }
                    }
                }
            }
            //安装费用
            $team = new MemberTeam();//安装团队
            $teamObj = $team->findOne(['id'=>$shopObj->install_team_id]);
            $teamlist = new MemberTeamList();//安装团队list
            $teamlistObj = $teamlist->findOne(['member_id'=>$shopObj->install_member_id,'team_id'=>$shopObj->install_team_id,'status'=>1]);
            if(!empty($shopObj->install_team_id)){
                //团队安装
                $install_id = $teamObj->team_member_id;//安装队长,给钱
                //修改团队安装数量
                $teamObj->not_install_shop_number -=1;//未安装的店铺数-1
                $teamObj->install_shop_number +=1;//已安装的店铺数+1
                $teamObj->install_screen_number +=$shopObj->screen_number;//已安装的屏幕数+安装的屏幕数
                $teamObj->save();
                //修改团队成员安装数量
                $teamlistObj->wait_shop_number -= 1;//未安装的店铺数-1
                $teamlistObj->wait_screen_number -= $shopObj->screen_number;//未安装的屏幕数-安装数
                $teamlistObj->install_shop_number += 1;//已安装的店铺数+1
                $teamlistObj->install_screen_number += $shopObj->screen_number;//已安装的屏幕数+安装数
                $teamlistObj->save();
            }else{
                //个人安装
                $install_id = $shopObj->install_member_id;//安装个人，给钱
                //修改个人安装数量
                $member = new MemberInfo();
                $memberlist = $member->findOne(['member_id'=>$shopObj->install_member_id]);
                $memberlist->wait_shop_number -=1;//待安装的店铺数量-1
                $memberlist->wait_screen_number -=$shopObj->screen_number;//待安装的屏幕数量-屏幕数
                $memberlist->save();
            }
            //安装给钱
            $totalprice = $installprice*$shopObj->screen_number;//总的安装费用
            if(!LogAccount::writeLog(3,$totalprice,1,'屏幕拆装费',$install_id,$shopObj->screen_number,$shopObj->area,$shopObj->name)){
                throw new Exception("[error]创建安装费用收入日志失败");
            }

            //业务奖励金
            if(!LogAccount::writeLog(4,$shopObj->introducer_member_price,1,'推荐店铺奖励金',$shopObj->introducer_member_id,$shopObj->screen_number,$shopObj->area,$shopObj->name)){
                throw new Exception("[error]创建推荐店铺奖励金收入日志失败");
            }

            //写安装记录yl_member_install_history
            $hisModel = new MemberInstallHistory();
            $hisModel->member_id = $install_id;
            $hisModel->shop_id = $shopObj->id;
            $hisModel->shop_name = $shopObj->name;
            $hisModel->area_name = $shopObj->area_name;
            $hisModel->address = $shopObj->address;
            $hisModel->screen_number = $shopObj->screen_number;
            $hisModel->type = 1;
            $hisModel->create_at = date('Y-m-d',time());
            $hisModel->shop_image = $shopObj->shop_image;
            $hisModel->save();

            //写屏幕维护记录
            $ShopScreenReplace = new ShopScreenReplace();
            $ShopScreenReplace->maintain_type = 1;
            $ShopScreenReplace->shop_id = $shopObj->id;
            $ShopScreenReplace->shop_member_id = $shopObj->shop_member_id;
            $ShopScreenReplace->apply_name = $shopappObj->apply_name;
            $ShopScreenReplace->apply_mobile = $shopappObj->apply_mobile;
            $ShopScreenReplace->shop_name = $shopObj->name;
            $ShopScreenReplace->shop_image = $shopObj->shop_image;
            $ShopScreenReplace->shop_area_id = $shopObj->area;
            $ShopScreenReplace->shop_area_name = $shopObj->area_name;
            $ShopScreenReplace->shop_address = $shopObj->address;
            $ShopScreenReplace->create_user_id = $shopObj->member_id;
            $ShopScreenReplace->create_user_name = $shopObj->member_name;
            $ShopScreenReplace->replace_screen_number = $shopObj->screen_number;
            $ShopScreenReplace->install_price = 0;
            $ShopScreenReplace->install_device_number = implode(',',array_column($screenid,'number'));
            $ShopScreenReplace->status = 4;
            $ShopScreenReplace->install_member_id = $shopObj->install_member_id;
            $ShopScreenReplace->install_member_name = $shopObj->install_member_name;
            $ShopScreenReplace->install_finish_at = date('Y-m-d H:i:s',time());
            $ShopScreenReplace->create_at = date('Y-m-d H:i:s',time());
            $ShopScreenReplace->assign_at = $shopObj->install_assign_at;
            $ShopScreenReplace->assign_time = $shopObj->install_assign_at.' '.date('H:i:s',time());
            $ShopScreenReplace->examine_user_id = $shopObj->last_examine_user_id;
            $ShopScreenReplace->examine_user_name = $shopObj->examine_user_name;
            $ShopScreenReplace->lable_id = $shopObj->lable_id;;
            $ShopScreenReplace->save();

            //redis变更
            //redis更新地区屏幕数
            //system_screen_number_$shengid:省
            //system_screen_number_$shiid:市
            //system_screen_number_$quid:区
            $redisObj = Yii::$app->redis;
            $redisObj->select(3);
            //省
            $keysheng = "system_screen_number:".$shengid;
            $asheng = $redisObj->GET($keysheng);
            $bsheng = json_decode($asheng,true);
            $csheng = [
                'screen_number' => $bsheng['screen_number'] + $shopObj->screen_number,
                'shop_number' => $bsheng['shop_number'] + 1,
                'mirror_number' => $bsheng['mirror_number'] + $shopObj->mirror_account,
            ];

            $redisObj->SET($keysheng,json_encode($csheng));
            //市
            $keyshi = "system_screen_number:".$shiid;
            $ashi = $redisObj->GET($keyshi);
            $bshi = json_decode($ashi,true);
            $cshi = [
                'screen_number'=>$bshi['screen_number']+$shopObj->screen_number,
                'shop_number'=>$bshi['shop_number']+1,
                'mirror_number'=>$bshi['mirror_number']+$shopObj->mirror_account,
            ];
            $redisObj->SET($keyshi,json_encode($cshi));
            //区
            $keyqu = "system_screen_number:".$quid;
            $aqu = $redisObj->GET($keyqu);
            $bqu = json_decode($aqu,true);
            $cqu = [
                'screen_number'=>$bqu['screen_number']+$shopObj->screen_number,
                'shop_number'=>$bqu['shop_number']+1,
                'mirror_number'=>$bqu['mirror_number']+$shopObj->mirror_account,
            ];
            $redisObj->SET($keyqu,json_encode($cqu));
            //街道
            $keyarea = "system_screen_number:".$shopObj->area;
            $aarea = $redisObj->GET($keyarea);
            $barea = json_decode($aarea,true);
            $carea = [
                'screen_number'=>$barea['screen_number']+$shopObj->screen_number,
                'shop_number'=>$barea['shop_number']+1,
                'mirror_number'=>$barea['mirror_number']+$shopObj->mirror_account,
            ];
            $redisObj->SET($keyarea,json_encode($carea));

            $redisObj = Yii::$app->redis;
            $redisObj->select(3);
            $redisObj->multi();
            $redisObj->executeCommand('sadd',['system_street_id',$shopObj->area]);
            $redisObj->executeCommand('hmset',$newvalue);
            $result = $redisObj->exec();
            if(!$result){
                throw new Exception("[error]redis更新地区屏幕数失败");
            }

            $redisObj = Yii::$app->redis;
            $redisObj->select(4);
            //$redisObj->executeCommand('sadd',['system_push_program_area_list',$shopObj->area]);
            //$redisObj->executeCommand('sadd',['system_push_program_shop_list',$shopObj->id]);
            $xieyi = [
                'shop_id'=>$shopObj->id,
                'agreement_name'=>$agr,
                'shop_type'=>1,//店铺
            ];
            $redisObj->rpush('system_member_agreement_list',json_encode($xieyi));

            //redis更新地区下街道数
            if($srt){

            }else{
                $newarray = ['type'=>'create','data'=>["area_id"=>$shopObj->area,"screen_number"=>$shopObj->screen_number,"mirror_number"=>$shopObj->mirror_account]];
                RedisClass::rpush('system_create_shop_list',json_encode($newarray),4);
            }

            //redis推送节目单
            //写入redis push_shop_list
            $push_shop_list['shop_id']=$shopObj->id;
            $push_shop_list['head_id']=$shopObj->headquarters_id;
            RedisClass::rpush("push_shop_custom_advert_list",json_encode($push_shop_list),5);

            //ride转换坐标
            $coordinate['shop_id'] = $shopObj->id;
            $coordinate['software_number'] = $screenid[0]['software_number'];
            RedisClass::rpush("list_json_get_coordinate_to_mongo",json_encode($coordinate),1);

            //百度麻点数据
            $params['operate'] = 'create';
            $params['title'] = $shopObj->name;
            $params['address'] = $shopObj->area_name.$shopObj->address;
            $params['longitude'] = $shopObj->bd_longitude;
            $params['latitude'] = $shopObj->bd_latitude;
            $params['area_id'] = $shopObj->area;
            $params['screen_number'] = $shopObj->screen_number;
            $params['shop_id'] = $shopObj->id;
            $params['mirror_account'] = $shopObj->mirror_account;
            RedisClass::rpush("shop_data_to_baidu_list",json_encode($params),1);

            $transaction->commit();
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return false;
        }
    }
    //获取某个街道下实时的屏幕数量 屏幕管理人//
    public static function getScreenByAreaTown($area_id){
        $obj = self::find()->where(['area'=>$area_id, 'status'=>5]);
        $screen = $obj->sum('screen_number');
        $admin = $obj->select('admin_member_id')->asArray()->one();
        return [
            'screen'=> $screen ? $screen : 0,
            'online'=> $screen ? $screen : 0,
            'fault'=> $screen ? $screen : 0,
            'admin'=>isset($admin['admin_member_id']) ? $admin['admin_member_id'] : 1,
        ];
    }

    /**
     * 根据状态值获取店铺类型
     * 1、租赁店 2、自营店 3、连锁店 4、总店
     */
    public static function getTypeByNum($num,$need=false){
        $srr = [
           '1'=>'租赁店',
           '2'=>'自营店',
           '3'=>'连锁店',
           '4'=>'总店',
        ];
        if($need){
            return $srr;
        }else {
            return array_key_exists($num, $srr) ? $srr[$num] : '未设置';
        }
    }
}
