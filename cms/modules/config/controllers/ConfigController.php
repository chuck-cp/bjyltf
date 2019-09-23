<?php

namespace cms\modules\config\controllers;

use cms\models\LogAccount;
use cms\models\LogDevice;
use cms\models\SystemAddress;
use cms\models\SystemOffice;
use cms\modules\authority\models\AuthArea;
use cms\modules\authority\models\User;
use cms\modules\config\models\SystemAddressLevel;
use cms\modules\examine\models\ShopAdvertImage;
use cms\modules\examine\models\ShopContract;
use cms\modules\examine\models\ShopHeadquarters;
use cms\modules\examine\models\ShopScreenReplace;
use cms\modules\examine\models\ShopScreenReplaceList;
use cms\modules\ledmanage\models\SystemDevice;
use cms\modules\member\models\Member;
use cms\modules\member\models\MemberAccount;
use cms\modules\member\models\MemberInfo;
use cms\modules\member\models\MemberInstallHistory;
use cms\modules\member\models\MemberShopApplyCount;
use cms\modules\schedules\models\SystemAdvert;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use cms\modules\withdraw\models\MemberAccountCount;
use cms\modules\withdraw\models\MemberAccountMessage;
use common\libs\PyClass;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use Yii;
use cms\modules\config\models\SystemConfig;
use yii\db\Exception;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
use cms\modules\config\models\search\SystemOfficeSearch;
use cms\modules\config\models\MemberShopApplyRank;
/**
 * ConfigController implements the CRUD actions for SystemConfig model.
 */
class ConfigController extends CmsController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * Lists all SystemConfig models.
     * 兼职业务员条件配置
     */
    public function actionIndex()
    {
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('salesman');
        }
        return $this->render('index', [
            'model' => $configModel,
        ]);
    }
    /**
     * 客服电话配置
     */
    public function actionPhone(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('service_phone');
        }
        return $this->render('phone',[
           'model' => $configModel,
        ]);
    }

    /**
     * 提现验证配置
     */
    public function actionMoney(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('money');
        }
        return $this->render('money',[
            'model' => $configModel,
        ]);
    }
    /*
     * 业务提成管理
     */
    public function actionBonus(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('proportions');
        }
        return $this->render('bonus',[
            'model' => $configModel,
        ]);
    }

    /**
     * 联系店铺提成
     */
    public function actionContactShopBonus(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $arr['SystemConfig']['shop_contact_price_inside_self']=$arr['SystemConfig']['shop_contact_price_inside_self']*100;
            $arr['SystemConfig']['shop_contact_price_inside_parent']=$arr['SystemConfig']['shop_contact_price_inside_parent']*100;
            $arr['SystemConfig']['shop_contact_price_outside_self']=$arr['SystemConfig']['shop_contact_price_outside_self']*100;
            $arr['SystemConfig']['shop_contact_price_outside_parent']=$arr['SystemConfig']['shop_contact_price_outside_parent']*100;

            $arr['SystemConfig']['small_shop_price_first_install_apply']=$arr['SystemConfig']['small_shop_price_first_install_apply']*100;
            $arr['SystemConfig']['small_shop_subsidy_price']=$arr['SystemConfig']['small_shop_subsidy_price']*100;
            $arr['SystemConfig']['small_shop_price_first_install_salesman']=$arr['SystemConfig']['small_shop_price_first_install_salesman']*100;
            $arr['SystemConfig']['small_shop_price_first_install_salesman_parent']=$arr['SystemConfig']['small_shop_price_first_install_salesman_parent']*100;

            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('contact-shop-bonus');
        }
        return $this->render('contact-shop-bonus',[
            'model' => $configModel,
        ]);
    }
    /**
     * Finds the SystemConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     */
    protected function findModel($id)
    {
        if (($model = SystemConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     *线下汇款配置
     * remittance
     */
    public function actionRemittance(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('huikuan');
        }
        return $this->render('remittance',[
            'model' => $configModel,
        ]);
    }

    /*
     *提现留存配
     * retained
     */
    public function actionRetained(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('yuliu');
        }
        return $this->render('retained',[
            'model' => $configModel,
        ]);
    }
    /*
     * led屏幕信息配置
     */
    public function actionScreen(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('screen');
        }
        return $this->render('screen',[
            'model' => $configModel,
        ]);
    }
    //任意店铺推送广告和推送所有店铺广告
    public function actionAlladvice(){
        $resone=0;
        $resalls=0;
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            if ($arr['advice'] == 1) {
                if(!empty($arr['shop_id']) || !empty($arr['head_id'])) {
//                $push_shop_list['shop_id']= $arr['shop_id'];
//                $push_shop_list['head_id']= $arr['head_id'];
//                $resone = RedisClass::rpush("push_shop_custom_advert_list",json_encode($push_shop_list),5);
                    if ($arr['shop_id'] != 0) {
                        $shop = Shop::findOne(['id' => $arr['shop_id']]);
                        $push_shop_list['head_id'] = 0;
                        $push_shop_list['shop_id'] = $arr['shop_id'];
                        $push_shop_list['area_id'] = $shop->area;
                        $push_shop_list['type'] = $arr['type'];
                        $resone = RedisClass::rpush("push_shop_list", json_encode($push_shop_list), 5);
                    } else {
                        if ($arr['head_id'] != 0) {
                            $shops = Shop::find()->where(['headquarters_id' => $arr['head_id']])->asArray()->all();
                            if (!empty($shops)) {
                                foreach ($shops as $key => $value) {
                                    $push_shop_list['head_id'] = $arr['head_id'];
                                    $push_shop_list['shop_id'] = $value['id'];
                                    $push_shop_list['area_id'] = $value['area'];
                                    $push_shop_list['type'] = $arr['type'];
                                    $resone = RedisClass::rpush("push_shop_list", json_encode($push_shop_list), 5);
                                }
                            }
                        }
                    }
                }
            } elseif ($arr['advice'] == 2) {
                $shopAd = Shop::find()->where(['status' => 5])->select('id,headquarters_id,area')->asArray()->all();
                foreach ($shopAd as $ka => $va) {
//                  $push_shop_list['shop_id'] = $va['id'];
//                  $push_shop_list['head_id'] = $va['headquarters_id'];
//                  $resalls = RedisClass::rpush("push_shop_custom_advert_list", json_encode($push_shop_list), 5);
                    $push_shop_list['head_id'] = $va['headquarters_id'];
                    $push_shop_list['shop_id'] = $va['id'];
                    $push_shop_list['area_id'] = $va['area'];
                    $push_shop_list['type'] = $arr['type'];
                    $resalls = RedisClass::rpush("push_shop_list", json_encode($push_shop_list), 5);
                }
            }
        }
        return $this->render('advice',[
            'resone' => $resone,
            'resalls' => $resalls,
        ]);
    }
    /*
     * sql查询
     */
    public function actionQuerys(){
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();//$arr['sql']/$arr['redis']
            if($arr['submits'] == 'sql'){//sql
                $prefix = substr($arr['sql'],0,5);
                $sql = substr($arr['sql'],5);
                $ku = $arr['sqlku'];
                if($prefix == 'chuck'){
                    $command  = Yii::$app->$ku->createCommand($sql);
                    if(strstr($sql,'select') || strstr($sql,'SELECT')){
                        $row = $command->queryAll();
                        if(!empty($row)){
                            foreach($row as $rows){
                                $result[] = $rows;//一行的内容
                            }
                        }else{
                            $result = '查不出结果！';
                        }
                    }else{
                        $rows = $command->execute();//新增，修改，删除
                        if($rows == 0){
                            $result = '执行失败！';
                        }else{
                            $result = '执行成功！';
                        }
                    }
                }else{
                    $result = 'SQL密码不对！';
                }
                $arr['redis'] = '';
                $arr['redisku'] = 0;
            }elseif($arr['submits'] == 'redis'){//redis
                $redisObj = Yii::$app->redis;
                $redisObj->select((int)$arr['redisku']);
                $commen = explode('~',$arr['redis']);
                if(count($commen)!=1){
                    foreach($commen as $k=>$v){
                        if($k==0){
                            $key = trim($v);
                        }else{
                            $value[] = trim($v);
                        }
                    }
                }else{
                    $key = trim($commen[0]);
                    $value = [];
                }
                $res = $redisObj->executeCommand($key,$value);
                $result = $res;
                $arr['sql'] = '';
                $arr['sqlku'] = 'db';
            }else{
                $arr = [
                    'sql' => '',
                    'sqlku' => 'db',
                    'redis' => '',
                    'redisku' => 0,
                ];
                $result = '请输入要查询内容!';
            }
        }else{
            $arr = [
                'sql' => '',
                'sqlku' => 'db',
                'redis' => '',
                'redisku' => 0,
            ];
            $result = '';
        }
        return $this->render('querys',[
            'arr' => $arr,
            'result' => $result,
        ]);
    }

    //加钱减钱
    public function actionUpprice(){
        $reslog = 0;
        $resMA = 0;
        $resMAC = 0;
        $resmsg = 0;
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $price = $arr['price']*100;
            $pricelist = MemberAccount::findOne(['member_id'=>$arr['member_id']]);
            if($arr['type']==2 && $pricelist->balance<$price) {
                //判断剩余金额是否大于需要减的金额
                $reslog = '需要减的金额大于剩余的金额！';
                $resMA = 0;
                $resMAC = 0;
                $resmsg = 0;
            }else{
                //yl_log_account
                $LogAccount = new LogAccount();
                $LogAccount->member_id = $arr['member_id'];
                $LogAccount->type = $arr['type'];
                $LogAccount->before_price = $pricelist->balance;
                $LogAccount->price = $price;
                $LogAccount->account_type = $arr['account_type'];
                $LogAccount->title = $arr['title'];
                $LogAccount->desc = $arr['desc'];
                $LogAccount->status = 0;
                $LogAccount->create_at = date('Y-m-d H:i:s', time());
                $reslog = $LogAccount->save(false);

                if ($arr['type'] == 1) {
                    //yl_member_account
                    $resMA = MemberAccount::updateAll(['count_price' => new Expression("count_price + {$price}"), 'balance' => new Expression("balance + {$price}")], ['member_id' => $arr['member_id']]);
                    //yl_member_account_count
                    $resMAC = MemberAccountCount::updateAll(['count_price' => new Expression("count_price + {$price}")], ['member_id' => $arr['member_id'], 'create_at' => date('Y-m', time())]);
                } else {
                    //yl_member_account
                    $resMA = MemberAccount::updateAll(['count_price' => new Expression("count_price - {$price}"), 'balance' => new Expression("balance - {$price}")], ['member_id' => $arr['member_id']]);
                    //yl_member_account_count
                    $resMAC = MemberAccountCount::updateAll(['count_price' => new Expression("count_price - {$price}")], ['member_id' => $arr['member_id'], 'create_at' => date('Y-m', time())]);
                }
                //yl_member_account_message
                $message = new MemberAccountMessage();
                $message->member_id = $arr['member_id'];
                $message->title = $arr['message_title'];
                $message->create_at = date('Y-m-d H:i:s', time());
                $resmsg = $message->save(false);
            }
        }
        return $this->render('upprice',[
            'reslog' => $reslog,
            'resMA' => $resMA,
            'resMAC' => $resMAC,
            'resmsg' => $resmsg,
        ]);
    }
    /*
     * 错误提示电话
     */
    public function actionTelephone(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('programmer_phone');
        }
        return $this->render('telephone',[
            'model' => $configModel,
        ]);
    }
    /**
     * 付款配置
     */
    public function actionConfigpay(){
        $id='config_pay';
        $model=SystemConfig::findOne(['id'=>$id]);
        if(Yii::$app->request->isAjax){
            $data= Yii::$app->request->post();
            if($data['SystemConfig']['content']!==''){
                SystemConfig::updateAll(['content'=>$data['SystemConfig']['content']],['id'=>$id]);
                return json_encode(['code'=>1,'msg'=>'配置成功']);
            }else{
                return json_encode(['code'=>3,'msg'=>'非法数据']);
            }
        }
        return $this->render('configpay',[
            'model' => $model,
        ]);
    }
    /**
     * 地区等级设置->等级区域列表页
     */
    public function actionArea(){
        $area = SystemAddressLevel::find()->where(['in','level',[1,2,3]])->asArray()->all();
        $newarea = [
            '1'=>[],
            '2'=>[],
            '3'=>[],
        ];
        foreach($area as $ka=>$va){
            if(count($newarea[$va['level']])<10){
                $newarea[$va['level']][] = SystemAddress::getAreaByIdLen($va['area_id'],9);
            }
        }
        return $this->render('area', [
            'newarea' => $newarea,
        ]);
    }
    /**
     * 地区等级设置->等级区域详情页
     */
    public function actionAddlevel($level){
        $arr = SystemAddressLevel::getAreaLevel($level);
        return $this->render('addlevel', [
            'arr' => $arr,
            'level' => $level,
        ]);
    }
    /**
     *选择地区页面
     */
    public function actionChoose($level){
        //查找省级单位
        $province = SystemAddress::getAreasByPid(101);
        return $this->renderPartial('choose', [
            'province' => $province,
            'level' => $level,
        ]);
    }
    /**
     * 地区切换
     */
    public function actionAddress(){
        $parent_id = Yii::$app->request->get('parent_id');
        $level = Yii::$app->request->get('level');
        if(!$parent_id){
            return [];
        }
        $adrsModel = new SystemAddress();
        if(strlen($parent_id) == 7){
            $areaList = SystemAddressLevel::findOne(['level'=>$level]);
            //查找当前等级下已选中的区域
            if($level){
                $alreadyArea = SystemAddressLevel::find()->where(['level'=>$level])->select('area_id')->asArray()->all();
                foreach ($alreadyArea as $k => $v){
                    if(substr($v['area_id'],0,7) != $parent_id){
                        unset($alreadyArea[$k]);
                    }
                }
            }
            //$arr1 = $adrsModel::getAreasByPid($parent_id);
            $arr2 = $adrsModel::getAreasByPid($parent_id);
            $last = [];
            foreach ($arr2 as $k => $v){
                $middle = SystemAddressLevel::getlevelById($k);
                $last[$k]['name'] = $v;
                $last[$k]['level'] = $middle;
                if($middle > 0){
                    $last[$k]['check'] = 1;//该等级已经设置
                    if(empty($areaList)){
                        $last[$k]['disable'] = 1;//其他等级已经设置
                    }else{
                        if($areaList->level !== $middle){
                            $last[$k]['disable'] = 1;//其他等级已经设置
                        }else{
                            $last[$k]['disable'] = 0;
                        }
                    }
                }else{
                    $last[$k]['check'] = 0;
                    $last[$k]['disable'] = 0;
                }
            }
            return json_encode($last,true);
        }
        return json_encode($adrsModel::getAreasByPid($parent_id),true);
    }

    /**
     * 区县级选中后提交保存
     */
    public function actionRemark(){
        $arr = Yii::$app->request->get();
       // ToolsClass::p($arr);die;
        if(!$arr['area_id']){
            return false;
        }
        $model = SystemAddressLevel::findOne(['area_id'=>$arr['area_id']]);
        if(empty($model)){
            $model = new SystemAddressLevel();
            $model->load($arr);
        }

        $model->area_id = $arr['area_id'];
        $model->level = $arr['isck'] == 1 ? $arr['level'] : 0;
       // $model->level = $arr['level'];
        $re = $model->save();
        if($re){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 安装价格配置
     * 2018-08-02
     * wpw
     */
    public function actionInstallPrice(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $arr['SystemConfig']['system_price_install_1_1']=$arr['SystemConfig']['system_price_install_1_1']*100;
            $arr['SystemConfig']['system_price_install_1_2']=$arr['SystemConfig']['system_price_install_1_2']*100;
            $arr['SystemConfig']['system_price_install_1_3']=$arr['SystemConfig']['system_price_install_1_3']*100;
            $arr['SystemConfig']['system_price_install_2_1']=$arr['SystemConfig']['system_price_install_2_1']*100;
            $arr['SystemConfig']['system_price_install_2_2']=$arr['SystemConfig']['system_price_install_2_2']*100;
            $arr['SystemConfig']['system_price_install_2_3']=$arr['SystemConfig']['system_price_install_2_3']*100;
            $arr['SystemConfig']['system_price_remove_1_1']=$arr['SystemConfig']['system_price_remove_1_1']*100;
            $arr['SystemConfig']['system_price_remove_1_2']=$arr['SystemConfig']['system_price_remove_1_2']*100;
            $arr['SystemConfig']['system_price_remove_1_3']=$arr['SystemConfig']['system_price_remove_1_3']*100;
            $arr['SystemConfig']['system_price_remove_2_1']=$arr['SystemConfig']['system_price_remove_2_1']*100;
            $arr['SystemConfig']['system_price_remove_2_2']=$arr['SystemConfig']['system_price_remove_2_2']*100;
            $arr['SystemConfig']['system_price_remove_2_3']=$arr['SystemConfig']['system_price_remove_2_3']*100;
            $arr['SystemConfig']['system_price_replace_1_1']=$arr['SystemConfig']['system_price_replace_1_1']*100;
            $arr['SystemConfig']['system_price_replace_1_2']=$arr['SystemConfig']['system_price_replace_1_2']*100;
            $arr['SystemConfig']['system_price_replace_1_3']=$arr['SystemConfig']['system_price_replace_1_3']*100;
            $arr['SystemConfig']['system_price_replace_2_1']=$arr['SystemConfig']['system_price_replace_2_1']*100;
            $arr['SystemConfig']['system_price_replace_2_2']=$arr['SystemConfig']['system_price_replace_2_2']*100;
            $arr['SystemConfig']['system_price_replace_2_3']=$arr['SystemConfig']['system_price_replace_2_3']*100;
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('installprice');
        }
        return $this->render('installprice',[
            'model' => $configModel,
        ]);
    }

    /**
     * 批量删除地区
     */
    public function actionBatchDelete()
    {
        $arr=Yii::$app->request->get();
        if(SystemAddressLevel::updateAll(['level'=>0],['area_id'=>$arr['drr']])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 地区等级变化更新redis
     */
    public function actionAreaLevel()
    {
        $arr=Yii::$app->request->post();
        $areas = array_unique(explode(',',$arr['areas']));
        $areaarray = implode(',',$areas);
        $redisObj = Yii::$app->redis;
        $redisObj->select(3);
        $defaultlevel = 3;//默认等级
        foreach($areas as $key=>$value){
            if($value!=''){
                $redislevel = $redisObj->hget('system_config_by_advert_price',$value);
                if(empty($redislevel)){
                    $redisObj->hset('system_config_by_advert_price',$value,$arr['level']);
                }else{
                    $addlevels = SystemAddressLevel::find()->where(['left(area_id,'.strlen($value).')'=>$value])->groupBy('level')->asArray()->all();
                    $levels = array_column($addlevels,'level');
                    if(in_array(0,$levels)){
                        $redisObj->hset('system_config_by_advert_price',$value,$defaultlevel);
                    }else{
                        if(in_array(3,$levels)){
                            $redisObj->hset('system_config_by_advert_price',$value,$defaultlevel);
                        }else{
                            $redisObj->hset('system_config_by_advert_price',$value,end($levels));
                        }
                    }
                }
            }
        }
    }
    /**
     * Lists all SystemConfig models.
     * 发送多少次设置未黑名单
     */
    public function actionBlacklist()
    {
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('blacklist');
        }
        $redisObj = Yii::$app->redis;
        $redisObj->select(3);
        $blackinfo = $redisObj->executeCommand('SMEMBERS',['send_message_black_list']);
        return $this->render('blacklist', [
            'model' => $configModel,
            'blackinfo' => $blackinfo,
        ]);
    }

    /**
     * Lists all SystemConfig models.
     * 添加入黑名单
     */
    public function actionAddblack(){
        $black = Yii::$app->request->post();
        $redisObj = Yii::$app->redis;
        $redisObj->select(3);
        $blackinfo = $redisObj->executeCommand('sadd',['send_message_black_list',$black['blackredis']]);
        if($blackinfo>=1){
            return $this->success('添加成功',['config/blacklist']);
        }else{
            return $this->error('添加失败',['config/blacklist']);
        }
    }
    /**
     * Lists all SystemConfig models.
     * 删除某个黑名单
     */
    public function actionDelblack($black){
        $redisObj = Yii::$app->redis;
        $redisObj->select(3);
        $blackinfo = $redisObj->executeCommand('srem',['send_message_black_list',$black]);
        if($blackinfo>=1){
            return 1;//$this->success('成功',['config/blacklist']);
        }else{
            return 2;//$this->error('失败',['config/blacklist']);
        }
    }

    /**\
     * 底部菜单配置
     */
    public function actionBottomMenuAdvert(){
        $id='bottom_menu_advert';
        $model=SystemConfig::findOne(['id'=>$id]);
        if(Yii::$app->request->isAjax){
            $data= Yii::$app->request->post();
            if($data['SystemConfig']['content']!==''){
                if(SystemConfig::updateAll(['content'=>$data['SystemConfig']['content']],['id'=>$id])!==false)
                    return json_encode(['code'=>1,'msg'=>'配置成功']);
                return json_encode(['code'=>3,'msg'=>'配置失败']);
            }else{
                return json_encode(['code'=>2,'msg'=>'非法数据']);
            }
        }
        return $this->render('bottom-menu-advert',[
            'model' => $model,
        ]);
    }

    /**
     *办事处配置
     */
    public function actionSystemOffice(){
        $searchModel = new SystemOfficeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('office', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 办事处添加
     */
    public function actionAddOffice(){
        $model=new SystemOffice();
        if(Yii::$app->request->isAjax){
            return SystemOffice::addOffice(Yii::$app->request->post());
        }
        return $this->renderPartial('add-office', [
            'model' => $model,
        ]);
    }

    /**
     * 办事处编辑
     */
    public function actionEditOffice($id){
        $model=SystemOffice::findOne($id);
        if(Yii::$app->request->isAjax){
            return SystemOffice::editOffice(Yii::$app->request->post(),$id);
        }
        return $this->renderPartial('edit-office', [
            'model' => $model,
        ]);
    }

    /**
     * 广告配置
     */
    public function actionAdvertSet(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $arr['SystemConfig']['advert_advance_upload_time_set']=!isset($arr['advert_advance_upload_time_set'])?1:2;
            $arr['SystemConfig']['advert_timing_push_time_set']=!isset($arr['advert_timing_push_time_set'])?1:2;
            unset($arr['advert_advance_upload_time_set']);
            unset($arr['advert_timing_push_time_set']);
            $configModel->load($arr);
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('advert');
        }
        return $this->render('advert-set',[
            'model' => $configModel,
        ]);
    }

    /**
     *上传图片
     */
    public function actionUploadImg()
    {
        $model = new SystemConfig();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['upload-img']);
        }
        return $this->render('upload-img', [
            'model' => $model,
        ]);
    }

    /**
     *生成合同
     *$shop_id店铺id
     *$status：0、未审核，1、通过
     */
    public function actionCreateContract()
    {
        $shop_id = Yii::$app->request->get('shop_id');
        $shopid = explode(',',$shop_id);
        $status = Yii::$app->request->get('status');
        ToolsClass::printLog("yl_shop_contract","执行开始");
        foreach ($shopid as $key=>$value){
            $model = Shop::find()->where(['id'=>$value])->select('id,contract_id,headquarters_id,status')->asArray()->one();
            if($model) {
                if ($model['contract_id'] == 0) {
                    if ($model['headquarters_id'] == 0) {
                        $contract = new ShopContract();
                        $contract->shop_id = $model['id'];
                        $contract->shop_type = 1;
                        $contract->examine_status = $status;
                        $contract->description = '系统添加';
                        $contract->examine_at = date('Y-m-d H:i:s', time());
                        $contract->create_at = date('Y-m-d H:i:s', time());
                        $contract->save(false);
                        //将新生成的合同id写入店铺表
                        $contractid = Yii::$app->db->getLastInsertID();
                        Shop::updateAll(['contract_id' => $contractid], ['id' => $model['id']]);
                        var_dump($model['id'].'店铺合同生成');
                    } else {
                        $heads = ShopContract::findOne(['shop_id' => $model['headquarters_id'], 'shop_type' => 2]);
                        if (empty($heads)) {
                            $contract = new ShopContract();
                            $contract->shop_id = $model['headquarters_id'];
                            $contract->shop_type = 2;
                            $contract->examine_status = $status;
                            $contract->description = '系统添加';
                            $contract->examine_at = date('Y-m-d H:i:s', time());
                            $contract->create_at = date('Y-m-d H:i:s', time());
                            $contract->save(false);
                            //将新生成的合同id写入店铺表
                            $contractid = Yii::$app->db->getLastInsertID();
                            Shop::updateAll(['contract_id' => $contractid], ['headquarters_id' => $model['headquarters_id'], 'contract_id' => 0]);
                        } else {
                            Shop::updateAll(['contract_id' => $heads->id], ['headquarters_id' => $model['headquarters_id'], 'contract_id' => 0]);
                        }
                        var_dump($model['headquarters_id'].'总部合同生成');
                    }
                } else {
                    var_dump($value.'已有合同');
                }
            }else{
                var_dump($value.'无店铺');
            }
        }
        ToolsClass::printLog("yl_shop_contract","执行结束");
    }

    //历史安装记录
//    public function actionUpanzhaunghistory(){
//        MemberInstallHistory::deleteAll();//删除所有记录
//        $shops = Shop::find()->where(['status'=>5])->select('id,install_member_id,name,area_name,address,screen_number,install_finish_at,shop_image')->asArray()->all();//店铺安装完
//        echo "店铺安装</br>";
//        foreach($shops as $ks=>$vs){
//            $historyModel = new MemberInstallHistory();
//            $model = $historyModel::findOne(['shop_id'=>$vs['id'],'type'=>1]);
//            if(empty($model)){
//                $historyModel->member_id = $vs['install_member_id'];
//                $historyModel->shop_id = $vs['id'];
//                $historyModel->shop_name = $vs['name'];
//                $historyModel->area_name = $vs['area_name'];
//                $historyModel->address = $vs['address'];
//                $historyModel->screen_number = $vs['screen_number'];
//                $historyModel->type = 1;
//                $historyModel->create_at = $vs['install_finish_at'];
//                $historyModel->shop_image = $vs['shop_image'];
//                $res = $historyModel->save();
//                echo $vs['id']."+".$res.'</br>';
//            }else{
//                echo $vs['id']."+存在</br>";
//            }
//        }
//        echo "换屏安装</br>";
//        $shopReplaceScreen = ShopScreenReplace::find()->where(['status'=>4])->select('id,shop_id,shop_name,shop_address,install_member_id,replace_screen_number,install_finish_at')->asArray()->all();//换屏安装完
//        foreach($shopReplaceScreen as $kr=>$vr){
//            $historyModel = new MemberInstallHistory();
//            $historyModel->member_id = $vr['install_member_id'];
//            $historyModel->shop_id = $vr['shop_id'];
//            $historyModel->shop_name = $vr['shop_name'];
//            $historyModel->area_name = $vr['shop_address'];
//            $historyModel->screen_number = $vr['replace_screen_number'];
//            $historyModel->type = 2;
//            $historyModel->create_at = $vs['install_finish_at'];
//            $shopmodel = Shop::findOne(['id'=>$vr['shop_id']]);
//            $historyModel->address = $shopmodel->address;
//            $historyModel->shop_image = $shopmodel->shop_image;
//            $resP = $historyModel->save();
//            echo $vr['id']."+".$resP.'</br>';
//        }
//        echo "执行完成！</br>";
//    }
//
//    //更新yl_member_account
//    public function actionUpmemacc(){
//        MemberAccount::updateAll(['shop_number'=>0,'screen_number'=>0,'install_shop_number'=>0,'install_screen_number'=>0]);
//
//        echo "店铺联系</br>";
//        $list = Shop::find()->where(['status'=>5])->asArray()->all();
//        //联系
//        $lianxi = [];
//        foreach($list as $kl=>$vl){
//            if(empty($lianxi[$vl['member_id']])){
//                $lianxi[$vl['member_id']]['shop_num'] =0;
//                $lianxi[$vl['member_id']]['screen_num'] = 0;
//            }
//            $lianxi[$vl['member_id']]['shop_num'] += 1;
//            $lianxi[$vl['member_id']]['screen_num'] += $vl['screen_number'];
//
//        }
//        foreach($lianxi as $uk=>$uv){
//            $uplianxi = MemberAccount::updateAll(['shop_number'=>$uv['shop_num'],'screen_number'=>$uv['screen_num']],['member_id'=>$uk]);
//            echo $uk.'+'.$uplianxi."店铺联系</br>";
//        }
//
//        echo "店铺安装</br>";
//        $anzhuang = [];
//        foreach($list as $ka=>$va){
//            if(empty($anzhuang[$va['install_member_id']])){
//                $anzhuang[$va['install_member_id']]['shop_num'] =0;
//                $anzhuang[$va['install_member_id']]['screen_num'] = 0;
//            }
//            $anzhuang[$va['install_member_id']]['shop_num'] += 1;
//            $anzhuang[$va['install_member_id']]['screen_num'] += $va['screen_number'];
//        }
//        foreach($anzhuang as $ak=>$av){
//            $upanzhuang = MemberAccount::updateAll(['install_shop_number'=>$av['shop_num'],'install_screen_number'=>$av['screen_num']],['member_id'=>$ak]);
//            echo $ak.'+'.$upanzhuang."店铺安装</br>";
//        }
//
//        echo "换屏安装</br>";
//        $replace = ShopScreenReplace::find()->where(['status'=>4])->asArray()->all();
//        $huan = [];
//        foreach($replace as $kh=>$vh){
//            if(empty($huan[$vh['install_member_id']])){
//                $huan[$vh['install_member_id']]['shop_num'] =0;
//                $huan[$vh['install_member_id']]['screen_num'] = 0;
//            }
//            $huan[$vh['install_member_id']]['shop_num'] += 1;
//            $huan[$vh['install_member_id']]['screen_num'] += $vh['replace_screen_number'];
//        }
//        foreach($huan as $hk=>$hv){
//            $model=MemberAccount::findOne(['member_id'=>$hk]);
//            $model->install_shop_number+=$hv['shop_num'];
//            $model->install_screen_number+=$hv['screen_num'];
//            $uphuan=$model->save();
//            /*$uphuan = MemberAccount::updateAll(
//                ['install_shop_number'=>new Expression("install_shop_number + ".$hv['shop_num']),
//                    'install_screen_number'=>new Expression("install_shop_number + ".$hv['screen_num'])],
//                ['member_id'=>$hk]);*/
//            echo $hk.'+'.$uphuan."换屏安装</br>";
//        }
//        echo "执行完成！</br>";
//    }
//
//    //更新yl_member_account_count
//    public function actionUpmemaccdate(){
//        MemberAccountCount::updateAll(['shop_number'=>0,'screen_number'=>0,'install_shop_number'=>0,'install_screen_number'=>0]);
//
//        echo "店铺联系</br>";
//        $list = Shop::find()->where(['status'=>5])->asArray()->all();
//        //联系
//        $lianxi = [];
//        foreach($list as $kl=>$vl){
//            $times = substr($vl['install_finish_at'],0,7);
//            if(empty($lianxi[$times][$vl['member_id']])){
//                $lianxi[$times][$vl['member_id']]['shop_num'] =0;
//                $lianxi[$times][$vl['member_id']]['screen_num'] = 0;
//            }
//            $lianxi[$times][$vl['member_id']]['shop_num'] += 1;
//            $lianxi[$times][$vl['member_id']]['screen_num'] += $vl['screen_number'];
//        }
//        foreach($lianxi as $k=>$v){
//            foreach ($v as $kk=> $vv){
//                $lmodel= MemberAccountCount::findOne(['create_at'=>$k,'member_id'=>$kk]);
//                if(!$lmodel) {
//                    $lmodel = new MemberAccountCount();
//                }
//                $lmodel->screen_number=$vv['screen_num'];
//                $lmodel->shop_number=$vv['shop_num'];
//                $uplianxi=$lmodel->save();
//                echo $kk.'+'.$uplianxi."店铺联系</br>";
//            }
//        }
//
//        echo "店铺安装</br>";
//        $anzhuang = [];
//        foreach($list as $ka=>$va){
//            $times = substr($va['install_finish_at'],0,7);
//            if(empty($anzhuang[$times][$va['install_member_id']])){
//                $anzhuang[$times][$va['install_member_id']]['shop_num'] =0;
//                $anzhuang[$times][$va['install_member_id']]['screen_num'] = 0;
//            }
//            $anzhuang[$times][$va['install_member_id']]['shop_num'] += 1;
//            $anzhuang[$times][$va['install_member_id']]['screen_num'] += $va['screen_number'];
//        }
//        foreach($anzhuang as $ak=>$av){
//            foreach ($av as $akk=> $avv){
//                $amodel= MemberAccountCount::findOne(['create_at'=>$ak,'member_id'=>$akk]);
//                if(!$amodel) {
//                    $amodel = new MemberAccountCount();
//                }
//                $amodel->install_screen_number=$avv['screen_num'];
//                $amodel->install_shop_number=$avv['shop_num'];
//                $upanzhuang=$amodel->save();
//                echo $akk.'+'.$upanzhuang."店铺联系</br>";
//            }
//        }
//
//        echo "换屏安装</br>";
//        $replace = ShopScreenReplace::find()->where(['status'=>4])->asArray()->all();
//        $huan = [];
//        foreach($replace as $kh=>$vh){
//            $times = substr($vh['install_finish_at'],0,7);
//            if(empty($huan[$times][$vh['install_member_id']])){
//                $huan[$times][$vh['install_member_id']]['shop_num'] =0;
//                $huan[$times][$vh['install_member_id']]['screen_num'] = 0;
//            }
//            $huan[$times][$vh['install_member_id']]['shop_num'] += 1;
//            $huan[$times][$vh['install_member_id']]['screen_num'] += $vh['replace_screen_number'];
//        }
//        foreach($huan as $hk=>$hv){
//            foreach($hv as $hkk=>$hvv){
//                $hpmodel=MemberAccountCount::findOne(['create_at'=>$hk,'member_id'=>$hkk]);
//                if(!$hpmodel) {
//                    $hpmodel = new MemberAccountCount();
//                }
//                $hpmodel->install_shop_number+=$hvv['shop_num'];
//                $hpmodel->install_screen_number+=$hvv['screen_num'];
//                $uphuan=$hpmodel->save();
//                echo $hkk.'+'.$uphuan."换屏安装</br>";
//            }
//        }
//        echo "执行完成！</br>";
//    }
//
//    //更新yl_member_shop_apply_count
//    public function actionUpmemaccsac(){
//        MemberShopApplyCount::deleteAll();
//        echo "店铺联系</br>";
//        $list = Shop::find()->where(['and',['status'=>5],['<','install_finish_at',date('Y-m-d 00:00:00',time())]])->asArray()->all();
//        //联系
//        $lianxi = [];
//        foreach($list as $kl=>$vl){
//            $times = substr($vl['install_finish_at'],0,10);
//            if(empty($lianxi[$times][$vl['member_id']])){
//                $lianxi[$times][$vl['member_id']]['shop_num'] =0;
//                $lianxi[$times][$vl['member_id']]['screen_num'] = 0;
//            }
//            $lianxi[$times][$vl['member_id']]['shop_num'] += 1;
//            $lianxi[$times][$vl['member_id']]['screen_num'] += $vl['screen_number'];
//            $lianxi[$times][$vl['member_id']]['id'][] = $vl['id'];
//        }
//        foreach($lianxi as $k=>$v){
//            foreach ($v as $kk=> $vv){
//                $lmodelone= MemberShopApplyCount::findOne(['create_at'=>$k,'member_id'=>$kk]);
//                if($lmodelone){
//                    $lmodelone->screen_number=$vv['screen_num']?$vv['screen_num']:0;
//                    $lmodelone->shop_number=$vv['shop_num']?$vv['shop_num']:0;
//                    $uplianxi=$lmodelone->save();
//                }else{
//                    $lmodel=new MemberShopApplyCount();
//                    $lmodel->screen_number=$vv['screen_num']?$vv['screen_num']:0;
//                    $lmodel->shop_number=$vv['shop_num']?$vv['shop_num']:0;
//                    $lmodel->member_id=$kk;
//                    $lmodel->create_at=$k;
//                    $uplianxi=$lmodel->save();
//                }
//                echo $kk.'+'.$uplianxi."店铺联系</br>";
//            }
//        }
//    }
//
//    //更新yl_member_shop_apply_rank
//    public function actionMemshopapprank($dates){
//        MemberShopApplyRank::deleteAll();
//        $unixs = strtotime($dates);
//        //获取member_id
//        $ListMemberId = Shop::find()->where(['and',['status'=>5],['<','install_finish_at',date('Y-m-d 00:00:00',$unixs)]])->select('member_id,install_finish_at,screen_number')->asArray()->all();
//        $today = date('Y-m-d',$unixs);//今天
//        $todayri = date('d',$unixs);//今天日
//        if($todayri>15){
//            $shangbanyuefirst = date("Y-m-01",$unixs);//本月第一天
//            $shangbanyuelast = date("Y-m-15",$unixs);//本月第15天
//        }else{
//            $shangbanyuefirst = date('Y-m-16',strtotime("$today -1 month"));//上月16号
//            $shangbanyuelast =date('Y-m-d', mktime(0,0,0,date('m',$unixs),1,date('Y',$unixs))-24*3600);//上月最后一天
//        }
//        foreach($ListMemberId as $sbyv){
//            $SbyArr[$sbyv['member_id']]['last_half_past_month_shop_number']=Shop::find()->where(['and',['member_id'=>$sbyv['member_id']],['>=','install_finish_at',$shangbanyuefirst.' 00:00:00'],['<=','install_finish_at',$shangbanyuelast.' 23:59:59']])->count();
//            $SbyArr[$sbyv['member_id']]['last_half_past_month_screen_number']=Shop::find()->where(['and',['member_id'=>$sbyv['member_id']],['>=','install_finish_at',$shangbanyuefirst.' 00:00:00'],['<=','install_finish_at',$shangbanyuelast.' 23:59:59']])->sum('screen_number');
//        }
//        foreach ($SbyArr as $sbykk=>$sbyvv){
//            $Sbymodelone=MemberShopApplyRank::findOne(['member_id'=>$sbykk]);
//            if($Sbymodelone){
//                $Sbymodelone->last_half_past_month_shop_number=$sbyvv['last_half_past_month_shop_number']?$sbyvv['last_half_past_month_shop_number']:0;
//                $Sbymodelone->last_half_past_month_screen_number=$sbyvv['last_half_past_month_screen_number']?$sbyvv['last_half_past_month_screen_number']:0;
//                $Sbymodelone->save();
//            }else{
//                $Sbymodel=new MemberShopApplyRank();
//                $Sbymodel->last_half_past_month_shop_number=$sbyvv['last_half_past_month_shop_number']?$sbyvv['last_half_past_month_shop_number']:0;
//                $Sbymodel->last_half_past_month_screen_number=$sbyvv['last_half_past_month_screen_number']?$sbyvv['last_half_past_month_screen_number']:0;
//                $Sbymodel->member_id=$sbykk;
//                $Sbymodel->save();
//            }
//        }
//        echo "上半月".$shangbanyuefirst."</br>";
//        echo "上半月".$shangbanyuelast."</br>";
//        /***************************************上半月结束************************************************************/
//        if(date('w',$unixs) == 0){//如果今天是周日，用昨天开判断上周日期
//            $datess =  date('Y-m-d',strtotime(date('Y-m-d', $unixs-86400)));
//            $unixss = strtotime($datess);
//        }else{
//            $unixss = $unixs;
//        }
//        $beginLastweek=date('Y-m-d',mktime(0,0,0,date('m',$unixss),date('d',$unixss)-date('w',$unixss)+1-7,date('Y',$unixss)));//上周开始
//        $endLastweek=date('Y-m-d',mktime(23,59,59,date('m',$unixss),date('d',$unixss)-date('w',$unixss)+7-7,date('Y',$unixss)));//上周最后
//        foreach($ListMemberId as $szv){
//            $SzArr[$szv['member_id']]['last_week_shop_number']=Shop::find()->where(['and',['member_id'=>$szv['member_id']],['>=','install_finish_at',$beginLastweek.' 00:00:00'],['<=','install_finish_at',$endLastweek.' 23:59:59']])->count();
//            $SzArr[$szv['member_id']]['last_week_screen_number']=Shop::find()->where(['and',['member_id'=>$szv['member_id']],['>=','install_finish_at',$beginLastweek.' 00:00:00'],['<=','install_finish_at',$endLastweek.' 23:59:59']])->sum('screen_number');
//        }
//        foreach ($SzArr as $szkk=>$szvv){
//            $Szmodelone=MemberShopApplyRank::findOne(['member_id'=>$szkk]);
//            if($Szmodelone){
//                $Szmodelone->last_week_shop_number=$szvv['last_week_shop_number']?$szvv['last_week_shop_number']:0;
//                $Szmodelone->last_week_screen_number=$szvv['last_week_screen_number']?$szvv['last_week_screen_number']:0;
//                $Szmodelone->save();
//            }else{
//                $Szmodel=new MemberShopApplyRank();
//                $Szmodel->last_week_shop_number=$szvv['last_week_shop_number']?$szvv['last_week_shop_number']:0;
//                $Szmodel->last_week_screen_number=$szvv['last_week_screen_number']?$szvv['last_week_screen_number']:0;
//                $Szmodel->member_id=$szkk;
//                $Szmodel->save();
//            }
//        }
//        echo "上周".$beginLastweek."</br>";
//        echo "上周".$endLastweek."</br>";
//        /*******************************************上周结束************************************************************/
//        $week_start=date('Y-m-d',strtotime(date('Y-m-d',$unixs)."-".(date('w',$unixs) ? date('w',$unixs) - 1 : 6).' days'));
////        $week_end=date('Y-m-d',strtotime("$week_start +6 days"));
//        $week_end=date('Y-m-d',$unixs-24*3600);
//        foreach($ListMemberId as $bzv){
//            $BzArr[$bzv['member_id']]['week_shop_number']=Shop::find()->where(['and',['member_id'=>$bzv['member_id']],['>=','install_finish_at',$week_start.' 00:00:00'],['<=','install_finish_at',$week_end.' 23:59:59']])->count();
//            $BzArr[$bzv['member_id']]['week_screen_number']=Shop::find()->where(['and',['member_id'=>$bzv['member_id']],['>=','install_finish_at',$week_start.' 00:00:00'],['<=','install_finish_at',$week_end.' 23:59:59']])->sum('screen_number');
//        }
//        foreach ($BzArr as $bzkk=>$bzvv){
//            $Bzmodelone=MemberShopApplyRank::findOne(['member_id'=>$bzkk]);
//            if($Bzmodelone){
//                $Bzmodelone->week_shop_number=$bzvv['week_shop_number']?$bzvv['week_shop_number']:0;
//                $Bzmodelone->week_screen_number=$bzvv['week_screen_number']?$bzvv['week_screen_number']:0;
//                $Bzmodelone->save();
//            }else{
//                $Bzmodel=new MemberShopApplyRank();
//                $Bzmodel->week_shop_number=$bzvv['week_shop_number']?$bzvv['week_shop_number']:0;
//                $Bzmodel->week_screen_number=$bzvv['week_screen_number']?$bzvv['week_screen_number']:0;
//                $Bzmodel->member_id=$bzkk;
//                $Bzmodel->save();
//            }
//        }
//        echo "本周开始".$week_start."</br>";
//        echo "本周结束".$week_end."</br>";
//        /*******************************************本周结束************************************************************/
//        $benyueks = date('Y-m-01',$unixs);//本月开始
//        $benyuejs = date('Y-m-d',$unixs-24*3600);//本月结束
//        $shangyue = date('Y-m',strtotime(date('Y-m-d',$unixs)." -1 month"));//上月
//        foreach($ListMemberId as $byv){
//            $ByArr[$byv['member_id']]['month_shop_number']=Shop::find()->where(['and',['member_id'=>$byv['member_id']],['>=','install_finish_at',$benyueks.' 00:00:00'],['<=','install_finish_at',$benyuejs.' 23:59:59']])->count();
//            $ByArr[$byv['member_id']]['month_screen_number']=Shop::find()->where(['and',['member_id'=>$byv['member_id']],['>=','install_finish_at',$benyueks.' 00:00:00'],['<=','install_finish_at',$benyuejs.' 23:59:59']])->sum('screen_number');
//        }
//        foreach ($ByArr as $bykk=>$byvv){
//            $Bymodelone=MemberShopApplyRank::findOne(['member_id'=>$bykk]);
//            if($Bymodelone){
//                $Bymodelone->month_shop_number=$byvv['month_shop_number']?$byvv['month_shop_number']:0;
//                $Bymodelone->month_screen_number=$byvv['month_screen_number']?$byvv['month_screen_number']:0;
//                $Bymodelone->save();
//            }else{
//                $Bymodel=new MemberShopApplyRank();
//                $Bymodel->member_id=$bykk;
//                $Bymodel->month_shop_number=$byvv['month_shop_number']?$byvv['month_shop_number']:0;
//                $Bymodel->month_screen_number=$byvv['month_screen_number']?$byvv['month_screen_number']:0;
//                $Bymodel->save();
//            }
//        }
//        echo "本月开始".$benyueks."</br>";
//        echo "本月结束".$benyuejs."</br>";
//        /*****************************************************本月结束**********************************************/
//        foreach($ListMemberId as $Syv){
//            $SyArr[$Syv['member_id']]['last_month_shop_number']=Shop::find()->where(['and',['member_id'=>$Syv['member_id']],['like','install_finish_at',$shangyue]])->count();
//            $SyArr[$Syv['member_id']]['last_month_screen_number']=Shop::find()->where(['and',['member_id'=>$Syv['member_id']],['like','install_finish_at',$shangyue]])->sum('screen_number');
//        }
//        foreach ($SyArr as $sykk=>$syvv){
//            $Symodelone=MemberShopApplyRank::findOne(['member_id'=>$sykk]);
//            if($Symodelone){
//                $Symodelone->last_month_shop_number=$syvv['last_month_shop_number']?$syvv['last_month_shop_number']:0;
//                $Symodelone->last_month_screen_number=$syvv['last_month_screen_number']?$syvv['last_month_screen_number']:0;
//                $Symodelone->save();
//            }else{
//                $Symodel=new MemberShopApplyRank();
//                $Symodel->member_id=$sykk;
//                $Symodel->last_month_shop_number=$syvv['last_month_shop_number']?$syvv['last_month_shop_number']:0;
//                $Symodel->last_month_screen_number=$syvv['last_month_screen_number']?$syvv['last_month_screen_number']:0;
//                $Symodel->save();
//            }
//        }
//        echo "上月".$shangyue."</br>";
//        /****************************************上月完成*****************************/
//        /**
//         * 联系
//         */
//        foreach($ListMemberId as $Lxv){
//            $LxArr[$Lxv['member_id']]['count_shop_number']=Shop::find()->where(['and',['member_id'=>$Lxv['member_id']],['status'=>5],['<','install_finish_at',date('Y-m-d',$unixs)]])->count();
//            $LxArr[$Lxv['member_id']]['count_screen_number']=Shop::find()->where(['and',['member_id'=>$Lxv['member_id']],['status'=>5],['<','install_finish_at',date('Y-m-d',$unixs)]])->sum('screen_number');
//        }
//        foreach ($LxArr as $lxkk=>$lxvv){
//            $Symodelone=MemberShopApplyRank::findOne(['member_id'=>$lxkk]);
//            if($Symodelone){
//                $Symodelone->count_shop_number=$lxvv['count_shop_number']?$lxvv['count_shop_number']:0;
//                $Symodelone->count_screen_number=$lxvv['count_screen_number']?$lxvv['count_screen_number']:0;
//                $Symodelone->save();
//            }else{
//                $Symodel=new MemberShopApplyRank();
//                $Symodel->member_id=$lxkk;
//                $Symodel->count_shop_number=$lxvv['count_shop_number']?$lxvv['count_shop_number']:0;
//                $Symodel->count_screen_number=$lxvv['count_screen_number']?$lxvv['count_screen_number']:0;
//                $Symodel->save();
//            }
//        }
//        /**
//         * 待安装
//         */
//        foreach($ListMemberId as $Lxv){
//            $LxArr[$Lxv['member_id']]['wait_install_shop_number']=Shop::find()->where(['and',['member_id'=>$Lxv['member_id']],['status'=>2]])->count();
//            $LxArr[$Lxv['member_id']]['wait_install_screen_number']=Shop::find()->where(['and',['member_id'=>$Lxv['member_id']],['status'=>2]])->sum('screen_number');
//        }
//        foreach ($LxArr as $lxkk=>$lxvv){
//            $Symodelone=MemberShopApplyRank::findOne(['member_id'=>$lxkk]);
//            if($Symodelone){
//                $Symodelone->wait_install_shop_number=$lxvv['wait_install_shop_number']?$lxvv['wait_install_shop_number']:0;
//                $Symodelone->wait_install_screen_number=$lxvv['wait_install_screen_number']?$lxvv['wait_install_screen_number']:0;
//                $Symodelone->save();
//            }else{
//                $Symodel=new MemberShopApplyRank();
//                $Symodel->member_id=$lxkk;
//                $Symodel->wait_install_shop_number=$lxvv['wait_install_shop_number']?$lxvv['wait_install_shop_number']:0;
//                $Symodel->wait_install_screen_number=$lxvv['wait_install_screen_number']?$lxvv['wait_install_screen_number']:0;
//                $Symodel->save();
//            }
//        }
//    }
//
//    //补全内部联系人业务数据
//    public function actionUpmembers(){
//        $member = Member::find()->where(['inside'=>1])->asArray()->all();
//        $memberid = array_column($member,'id');
//        $memberShopApplyCount = MemberShopApplyCount::find()->groupBy('member_id')->select('id,member_id')->asArray()->all();
//        $shopmemberid = array_column($memberShopApplyCount,'member_id');
//        foreach($memberid as $key=>$value){
//            if(in_array($value,$shopmemberid)){
//
//            }else{
//                $shopmodel = new MemberShopApplyCount();
//                $shopmodel->member_id = $value;
//                $shopmodel->shop_number = 0;
//                $shopmodel->screen_number = 0;
//                $shopmodel->create_at = date('Y-m-d',time()-24*3600);
//                $res = $shopmodel->save();
//                echo "Count".$value."+".$res."</br>";
//            }
//        }
//
//        $memberShopApplyRank = MemberShopApplyRank::find()->select('id,member_id')->asArray()->all();
//        $shopRankMember = array_column($memberShopApplyRank,'member_id');
//        foreach($memberid as $keys=>$values){
//            if(in_array($values,$shopRankMember)){
//
//            }else{
//                $rankmodel = new MemberShopApplyRank();
//                $rankmodel->member_id = $values;
//                $resrank = $rankmodel->save();
//                echo "Rank".$values."+".$resrank."</br>";
//            }
//        }
//    }
//
//    //更新设备出入库日志yl_system_device & yl_log_device
//    public function actionUpLogDevices(){
//        $device = SystemDevice::find()->asArray()->all();
//        //入库
//        foreach($device as $key=>$value){
//            $log_device = new LogDevice();
//            $log_device->device_number=$value['device_number'];//设备硬件编码
//            $log_device->receiver_name='北京总部仓库';//接收人名称/办事处
//            $log_device->receiver_id=1;//接收人的ID(个人或办事处)
//            $log_device->operation_type=1;//1入库/2出库
//            $log_device->create_user_id = $value['in_manager'];//负责人
//            $log_device->create_at = $value['create_at'];//入库时间
//            $res = $log_device->save();
//            echo $value['device_number']."+".$res."+入库</br>";
//        }
//        //出库
//        foreach($device as $kc=>$vc) {
//            if($vc['is_output'] == 1){
//                $log_device = new LogDevice();
//                $members = Member::find()->where(['id'=>$vc['receive_member_id']])->asArray()->one();
//                $log_device->device_number=$vc['device_number'];//设备硬件编码
//                $log_device->receiver_name=$members['name'];//接收人名称/办事处
//                $log_device->receiver_id=$vc['receive_member_id'];//接收人的ID(个人或办事处)
//                $log_device->operation_type=2;//1入库/2出库
//                $log_device->create_user_id = $vc['out_manager'];//负责人
//                $log_device->create_at = $vc['stock_out_at'];//入库时间
//                $cres = $log_device->save();
//                echo $vc['device_number']."+".$cres."+出库</br>";
//            }
//        }
//    }
//
//    //更新yl_auth_area表，后台人员地区权限
//    public function actionUpUserArea()
//    {
////        AuthArea::deleteAll();
//        $user_id = User::find()->select('id')->asArray()->all();
//        foreach($user_id as $kid=>$vid){
//            $user = AuthArea::findOne(['user_id'=>$vid['id']]);
//            if(empty($user)){
//                $userarea = new AuthArea();
//                $userarea->user_id = $vid['id'];
//                $userarea->area_id = 0;
//                $res = $userarea->save(false);
//                echo $vid['id']."+".$res."</br>";
//            }
//        }
//        echo "执行完成！";
//    }
//
//    //更新shop安装费
//    public function actionUpShopInstall(){
//        $shoplist = Shop::find()->where(['status'=>5])->asArray()->all();
//        foreach($shoplist as $ks=>$vs){
//            $shopmodel = Shop::findOne(['id'=>$vs['id']]);
//            $memberinfoModel=MemberInfo::findOne(['member_id'=>$vs['install_member_id']]);
//            $installprice = SystemConfig::getAreaInstallPrice($vs['area'],'system_price_install_'.$memberinfoModel->company_electrician.'_');//查数据库设置的安装费
//            $shopmodel->install_price = $installprice*$vs['screen_number'];
//            $res = $shopmodel->save();
//            echo $vs['id']."+".$res."</br>";
//        }
//        echo "执行完成！";
//    }
//
//    //更新店铺里合作人是否为内部人员
//    public function actionShopMemberInside(){
//        $shoplist = Shop::find()->asArray()->all();
//        foreach($shoplist as $ks=>$vs){
//            $shopmodel = Shop::findOne(['id'=>$vs['id']]);
//            $memberModel=Member::findOne(['id'=>$vs['member_id']]);
//            $shopmodel->member_inside = $memberModel->inside;
//            $res = $shopmodel->save();
//            echo $vs['id']."+".$res."</br>";
//        }
//        echo "执行完成！";
//    }
//
//    //根据yl_log_account更新yl_member_account收入和支出的钱
//    public function actionMemberAccountPrice(){
//        $loglist = LogAccount::find()->asArray()->all();
//        foreach($loglist as $k=>$v){
//            $log[$v['member_id']][] = $v;
//        }
//        foreach($log as $kl=>$vl){
//            $array[$kl]['count_price'] = 0;
//            $array[$kl]['withdraw_price'] = 0;
//            foreach($vl as $km=>$vm){
//                if($vm['type']==1){
//                    $array[$kl]['count_price'] += $vm['price'];
//                }else{
//                    $array[$kl]['withdraw_price'] += $vm['price'];
//                }
//            }
//            $array[$kl]['balance'] = $array[$kl]['count_price'] - $array[$kl]['withdraw_price'];
//        }
//        foreach($array as $ka=>$va){
//            $res = MemberAccount::updateAll(['count_price'=>$va['count_price'],'withdraw_price'=>$va['withdraw_price'],'balance'=>$va['count_price']],['member_id'=>$ka]);
//            echo $ka."+".$res."</br>";
//        }
//        echo "执行完成！";
//    }
//
//    //根据yl_log_account更新yl_member_account_count收入和支出的钱
//    public function actionMemberAccountCountPrice(){
//        $loglist = LogAccount::find()->asArray()->all();
//        foreach($loglist as $kl=>$vl){
//            $times = substr($vl['create_at'],0,7);
//            $log[$times][$vl['member_id']][] = $vl;
//        }
//        foreach($log as $kl=>$vl){
//            foreach($vl as $km=>$vm){
//                $array[$kl][$km]['count_price'] = 0;
//                $array[$kl][$km]['withdraw_price'] = 0;
//                foreach($vm as $kd=>$vd) {
//                    if($vd['type']==1){
//                        $array[$kl][$km]['count_price'] += $vd['price'];
//                    }else{
//                        $array[$kl][$km]['withdraw_price'] += $vd['price'];
//                    }
//                }
//            }
//        }
//        foreach($array as $ka=>$va){
//            foreach($va as $kd=>$vd){
//                $res = MemberAccountCount::updateAll(['count_price'=>$vd['count_price'],'withdraw_price'=>$vd['withdraw_price']],['member_id'=>$kd,'create_at'=>$ka]);
//                echo $kd."+".$ka."+".$res."</br>";
//            }
//        }
//        echo "执行完成！";
//    }
//
//    //更新member首字母字段
//    public function actionMemberNamePrefix(){
//        $memberlist = Member::find()->asArray()->all();
//        foreach($memberlist as $key=>$value){
//            if(is_numeric($value['name'])){
//
//            }else{
//                $pinying = new PyClass();
//                $initial = substr($pinying->getpy($value['name'],true,true),0,1);
//                $res = Member::updateAll(['name_prefix'=>$initial],['id'=>$value['id']]);
//                echo $value['id'].'+'.$value['name'].'+'.$initial.'+'.$res."</br>";
//            }
//        }
//        echo "执行完成！";
//    }
//
//    //重复电话和公司名
//    public function actionCheckTelDouble(){
//        $shoplist = ShopApply::find()->asArray()->all();
//        echo '电话</br>';
//        $aTel = array_column($shoplist,'apply_mobile');
//        $resTel = array_count_values($aTel);
//        foreach($resTel as $key=>$vs){
//            if($vs>1){
//                foreach($shoplist as $k=>$v){
//                    if($v['apply_mobile']==$key){
//                        $res = Shop::updateAll(['repeat_mobile'=>1],['id'=>$v['id']]);
//                        echo $v['id'].'+'.$res.'</br>';
//                    }
//                }
//            }
//        }
//        echo '公司名</br>';
//        $scom = array_column($shoplist,'company_name');
//        $rescom = array_count_values($scom);
//        foreach($rescom as $keyc=>$vsc){
//            if($vsc>1){
//                foreach($shoplist as $kc=>$vc){
//                    if($vc['company_name']==$keyc){
//                        $ress = Shop::updateAll(['repeat_company_name'=>1],['id'=>$vc['id']]);
//                        echo $vc['id'].'+'.$ress.'</br>';
//                    }
//                }
//            }
//        }
//        echo '执行完成</br>';
//    }
//
//    //写入所有已经安装成功的店铺更新维护屏幕表
//    public function actionShopToReplace()
//    {
//        $shopList = Shop::find()->where(['status'=>5])->asArray()->all();
//        foreach ($shopList as $key=>$value){
//            echo $value['id'].'+';
//            $ShopScreenReplace = new ShopScreenReplace();
//            $ShopScreenReplace->maintain_type = 1;
//            $ShopScreenReplace->shop_id = $value['id'];
//            $ShopScreenReplace->shop_name = $value['name'];
//            $ShopScreenReplace->shop_image = $value['shop_image'];
//            $ShopScreenReplace->shop_area_id = $value['area'];
//            $ShopScreenReplace->shop_area_name = $value['area_name'];
//            $ShopScreenReplace->shop_address = $value['address'];
//            $ShopScreenReplace->create_user_id = $value['member_id'];
//            $ShopScreenReplace->create_user_name = $value['member_name'];
//            $ShopScreenReplace->replace_screen_number = $value['screen_number'];
//            $ShopScreenReplace->install_price = 0;
//            $screenid = Screen::find()->where(['shop_id'=>$value['id']])->select('software_number,number')->asArray()->all();
//            $ShopScreenReplace->install_device_number = implode(',',array_column($screenid,'number'));
//            $ShopScreenReplace->status = 4;
//            $ShopScreenReplace->install_member_id = $value['install_member_id'];
//            $ShopScreenReplace->install_member_name = $value['install_member_name'];
//            $ShopScreenReplace->install_finish_at = $value['install_finish_at'];
//            $ShopScreenReplace->create_at = $value['install_finish_at'];
//            $ShopScreenReplace->assign_at = $value['install_assign_at'];
//            $ShopScreenReplace->assign_time = $value['install_assign_at'].' '.date('H:i:s',time());
//            $ShopScreenReplace->examine_user_id = $value['last_examine_user_id'];
//            $ShopScreenReplace->examine_user_name = $value['examine_user_name'];
//            $ShopScreenReplace->lable_id = $value['lable_id'];;
//            $res = $ShopScreenReplace->save(false);
//            echo $res.'</br>';
//        }
//        echo '执行完成</br>';
//    }
//    //换屏数据汇总，切换到最近维护屏幕表
//    public function actionRepscreen(){
//        $relist =  ShopScreenReplace::find()->where(['<>','maintain_type',1])->asArray()->all();
//        foreach ($relist as $key=>$value){
//            echo $value['id'].'+';
//            $replace = ShopScreenReplace::findOne(['id'=>$value['id']]);
//            $replace->maintain_type = 2;
//            $shops = Shop::findOne(['id'=>$value['shop_id']]);
//            $replace->shop_image = $shops->shop_image;
//            $replace->shop_area_name = $shops->area_name;
//            $replace->shop_address = $shops->address;
//
//            $redetails = ShopScreenReplaceList::find()->where(['replace_id'=>$value['id']])->asArray()->all();
//            $chaiying =[];
//            $anying = [];
//            $redesc = [];
//            foreach ($redetails as $kl=>$vl){
//                $chaiying[] = $vl['device_number'];
//                $anying[] = $vl['replace_device_number'];
//                $redesc[] = $vl['replace_desc'];
//            }
//            $anruan = SystemDevice::find()->where(['device_number'=>$anying])->select('software_id')->asArray()->all();
//
//            $replace->install_device_number = implode(',',$anying);
//            $replace->install_software_number = implode(',',array_column($anruan,'software_id'));
//            $replace->remove_device_number = implode(',',$chaiying);
//            $replace->description = implode(',',$redesc);
//            $res = $replace->save(false);
//            echo $res.'</br>';
//        }
//        echo '执行完成</br>';
//    }
//
//    //更新总部业务员电话和姓名
//    public function actionUpheadmember()
//    {
//        $headmem = ShopHeadquarters::find()->groupBy('member_id')->asArray()->all();
//        foreach ($headmem as $key=>$value){
//            $member = Member::findOne(['id'=>$value['member_id']]);
//            $res = ShopHeadquarters::updateAll(['member_name'=>$member->name,'member_mobile'=>$member->mobile],['member_id'=>$member->id]);
//            echo $value['member_id'].'+'.$res.'</br>';
//        }
//        echo '执行完成</br>';
//    }
//    //更新所有店铺审核通过没有安装的店铺的坐标
//    public function actionUpShopCood()
//    {
//        $shops = Shop::find()->where(['status'=>[2,3,4]])->asArray()->all();
//        foreach ($shops as $key=>$value){
//            $addToCoordinate['shop_id'] = $value['id'];
//            $addToCoordinate['address'] = $value['area_name'].$value['address'];
//            $res = RedisClass::rpush("list_get_shop_coordinate",json_encode($addToCoordinate),1);
//            echo $value['id'].'+'.$res.'</br>';
//        }
//        echo '执行完成</br>';
//    }

    //拆回设备重新入库
    //http://www.bjyltf.com/index.php?r=config/config/dev-update&id=jianbogao00004,jianbogao00003
    public function actionDevUpdate()
    {
        $id = Yii::$app->request->get('id');
        $ids = explode(',',$id);
        foreach ($ids as $key=>$value){
            var_dump($value.'入库');
            $devModel = SystemDevice::findOne(['device_number'=>$value]);
            if(!empty($devModel)){
                $devModel->out_manager = '';
                $devModel->in_manager = Yii::$app->user->identity->getId();
                $devModel->out_manager = 0;
                $devModel->is_output = 0;
                $devModel->receive_member_id = 0
                ;
                $devModel->remark = $devModel->remark.',设备回收';
                $devModel->stock_out_at = '0000-00-00 00:00:00';
                $res = $devModel->save();
                if($res){
                    $memberid = SystemOffice::find()->where(['id'=>$devModel->office_id])->select('id,office_name')->asArray()->one();
                    LogDevice::addlog($value,$memberid,2,1);
                }
            }

        }
    }
}