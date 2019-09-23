<?php

namespace console\controllers;
use cms\models\SystemAddress;
use cms\modules\examine\models\ShopHeadquarters;
use cms\modules\member\models\MemberInfo;
use common\libs\ToolsClass;
use console\models\Member;
use console\models\MemberAccount;
use console\models\MemberInstallCount;
use console\models\MemberInstallHistory;
use console\models\MemberInstallSubsidy;
use console\models\ScreenRunTimeByMonth;
use console\models\ScreenRunTimeByShop;
use console\models\Shop;
use console\models\ShopContract;
use console\models\ShopScreenReplace;
use console\models\SystemConfig;
use Yii;
use yii\base\Exception;
use yii\console\Controller;


class ShopController extends Controller
{
    /*
     * 统计每日按安装的店铺
     * */
    public function actionCountInstallNumber(){
        $shopModel = new Shop();
        ToolsClass::printLog("count_install_number","开始执行");
        $shopModel->countInstallNumber();
        ToolsClass::printLog("count_install_number","执行结束");
    }

    /*
     * 统计每日联系的店铺
     * */
    public function actionCountApplyNumber(){
        $shopModel = new Shop();
        ToolsClass::printLog("count_apply_number","开始执行");
        $shopModel->countApplyNumber();
        ToolsClass::printLog("count_apply_number","执行结束");
    }

    /*
     * 计算每月给店主发放奖励金和维护费
     */
    public function actionReduceMonthSubsidy(){
        $shopModel = new Shop();
        ToolsClass::printLog("reduce_month_subsidy","开始执行");
        $shopModel->reduceMonthSubsidy();
        ToolsClass::printLog("reduce_month_subsidy","执行结束");
    }

    /*
     * 发放每月给店主发放奖励金和维护费
     */
    public function actionGrantMonthSubsidy(){
        $shopModel = new Shop();
        ToolsClass::printLog("grant_month_subsidy","开始执行");
        $shopModel->grantMonthSubsidy();
        ToolsClass::printLog("grant_month_subsidy","执行结束");
    }

    /*
     * 获取店铺下的设备所在的坐标,转换成国标后存到mongo中
     * */
    public function actionGetCoordinateToMongo() {
        $shopModel = new Shop();
        ToolsClass::printLog("get_coordinate_to_mongo","开始执行");
        $shopModel->getCoordinateToMongo();
        ToolsClass::printLog("get_coordinate_to_mongo","执行结束");
    }

    /**
     * 每天统计屏幕数量，并发送发送邮件
     */
    public function actionGetMailDateShop(){
        $shopModel= new Shop();
        ToolsClass::printLog("get_mail_date_shop","开始执行");
        $shopModel->getMailDateShop();
        ToolsClass::printLog("get_mail_date_shop","执行结束");
    }

    //更新shop的合同id

    public function actionUpShopContract()
    {
        ToolsClass::printLog("up_shop_contract","开始执行");
        $contract = ShopContract::find()->asArray()->all();
        foreach ($contract as $key=>$value){
            if($value['shop_type']==1){
                Shop::updateAll(['contract_id'=>$value['id']],['id'=>$value['shop_id']]);
                echo "{$value['id']}\n";
            }elseif($value['shop_type']==2){
                Shop::updateAll(['contract_id'=>$value['id']],['headquarters_id'=>$value['shop_id']]);
                echo "{$value['id']}\n";
            }
        }
        ToolsClass::printLog("up_shop_contract","执行结束");
    }

    //增加之前未生成的合同
    public function actionAddOldShopContract(){
        ToolsClass::printLog("add_old_shop_contract","开始执行");
        echo "商家\n";
        $shoplist = Shop::find()->where(['contract_id'=>0,'headquarters_id'=>0,'status'=>[2,3,4,5]])->asArray()->all();
        foreach ($shoplist as $key=>$value){
            $contract = ShopContract::findOne(['shop_id'=>$value['id'],'shop_type'=>1]);
            echo "{$value['id']}\n";
            if($contract){
                Shop::updateAll(['contract_id'=>$contract->id],['id'=>$value['id']]);
            }else{
                $contractnew = new ShopContract();
                $contractnew->shop_id = $value['id'];
                $contractnew->shop_type = 1;
                $contractnew->create_at = date('Y-m-d H:i:s',time());
                $contractnew->save();

                $contractid = Yii::$app->db->getLastInsertID();
                Shop::updateAll(['contract_id'=>$contractid],['id'=>$value['id']]);
            }
        }

        echo "总部\n";
        $shopHlist = ShopHeadquarters::find()->where(['examine_status'=>1])->asArray()->all();
        foreach ($shopHlist as $kh=>$valh){
            $contract = ShopContract::findOne(['shop_id'=>$valh['id'],'shop_type'=>2]);
            echo "{$valh['id']}\n";
            if($contract){
                Shop::updateAll(['contract_id'=>$contract->id],['headquarters_id'=>$valh['id']]);
            }else{
                $contractnew = new ShopContract();
                $contractnew->shop_id = $valh['id'];
                $contractnew->shop_type = 2;
                $contractnew->create_at = date('Y-m-d H:i:s',time());
                $contractnew->save();

                $contractid = Yii::$app->db->getLastInsertID();
                Shop::updateAll(['contract_id'=>$contractid],['contract_id'=>0,'headquarters_id'=>$valh['id'],'status'=>[2,3,4,5]]);
            }
        }
        ToolsClass::printLog("add_old_shop_contract","执行结束");
    }


    //更新电工指派数量
    public function actionUpAssignNum(){
        ToolsClass::printLog("update_memberinfo_wait","执行开始");
        $member = MemberInfo::find()->where(['electrician_examine_status'=>1])->asArray()->all();
        foreach ($member as $key=>$value){
            var_dump($value['member_id']);
            $shops = Shop::find()->where(['status'=>[2,3,4],'install_member_id'=>$value['member_id']])->asArray()->all();
            $wait_shop_number = count($shops);
            $wait_screen_number = array_sum(array_column($shops,'screen_number'));
            MemberInfo::updateAll(['wait_shop_number'=>$wait_shop_number,'wait_screen_number'=>$wait_screen_number],['member_id'=>$value['member_id']]);
        }
        ToolsClass::printLog("update_memberinfo_wait","执行结束");
    }
    
    //新增地址字段分省市区
    public function actionUpdateShopAddress()
    {
        ToolsClass::printLog("yl_shop","执行开始");
        $shops = Shop::find()->select('area,id')->asArray()->all();
        foreach ($shops as $key=>$value){
            var_dump($value['id']);
            $AreaArr=explode(' ',trim(SystemAddress::getAreaNameById($value['area'])));
            $csv['province']=$AreaArr[0];//省
            $csv['city']=$AreaArr[1];//市
            $csv['area']=$AreaArr[2];//區
            $csv['street']=$AreaArr[3];//街道
            Shop::updateAll(['shop_province'=>$csv['province'],'shop_city'=>$csv['city'],'shop_area'=>$csv['area'],'shop_street'=>$csv['street']],['id'=>$value['id']]);
        }
        ToolsClass::printLog("yl_shop","执行结束");
    }
    
    //更新合同审核功能前，未生成的合同
    public function actionUpOldShopContract()
    {
        $model = Shop::find()->where(['status'=>5,'contract_id'=>0])->select('id,contract_id,headquarters_id,status');
//        echo $model->createCommand()->getRawSql();
//        die;
        ToolsClass::printLog("yl_shop_contract","执行开始");
        foreach ($model as $key=>$value){
            if($value['headquarters_id'] == 0){
                $contract = new ShopContract();
                $contract->shop_id = $value['id'];
                $contract->shop_type = 1;
                $contract->examine_status = 1;
                $contract->description = '系统添加';
                $contract->examine_at = date('Y-m-d H:i:s',time());
                $contract->create_at = date('Y-m-d H:i:s',time());
                $contract->save(false);
                //将新生成的合同id写入店铺表
                $contractid = Yii::$app->db->getLastInsertID();
                Shop::updateAll(['contract_id'=>$contractid],['id'=>$value['id']]);
            }else{
                $heads = ShopContract::findOne(['shop_id'=>$value['headquarters_id'],'shop_type'=>2]);
                if(empty($heads)) {
                    $contract = new ShopContract();
                    $contract->shop_id = $model->headquarters_id;
                    $contract->shop_type = 2;
                    $contract->examine_status = 1;
                    $contract->description = '系统添加';
                    $contract->examine_at = date('Y-m-d H:i:s', time());
                    $contract->create_at = date('Y-m-d H:i:s', time());
                    $contract->save(false);
                    //将新生成的合同id写入店铺表
                    $contractid = Yii::$app->db->getLastInsertID();
                    Shop::updateAll(['contract_id' => $contractid], ['headquarters_id' => $value['headquarters_id'],'status'=>5,'contract_id'=>0]);
                }else{
                    Shop::updateAll(['contract_id' => $heads->id], ['headquarters_id' => $value['headquarters_id'],'status'=>5,'contract_id'=>0]);
                }
            }
        }
        ToolsClass::printLog("yl_shop_contract","执行结束");
    }
    
}
