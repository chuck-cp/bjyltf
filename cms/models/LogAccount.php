<?php

namespace cms\models;

use cms\modules\config\models\SystemConfig;
use cms\modules\config\models\SystemZonePrice;
use cms\modules\member\models\MemberAreaCount;
//use cms\modules\member\member;
use cms\modules\member\models\MemberAccount;
use cms\modules\shop\models\Shop;
use cms\modules\withdraw\models\MemberAccountCount;
use cms\modules\withdraw\models\MemberAccountMessage;
use common\libs\ToolsClass;
use Yii;
use cms\modules\member\models\Member;

/**
 * This is the model class for table "{{%log_account}}".
 *
 * @property string $id 系统流水表
 * @property string $member_id 用户ID
 * @property int $type 类型(1、收入 2、支撑)
 * @property string $before_price 操作之前的金额
 * @property string $price 金额
 * @property string $desc 描述
 * @property int $status 支出状态(1、待提现 2、提现成功)
 * @property string $create_at 创建时间
 */
class LogAccount extends \yii\db\ActiveRecord
{
    const CREATE_SHOP_TO_KEEPER = 20000;
    const CREATE_SHOP_TO_SALESMAN = 15000;
    const CREATE_SHOP_TO_NOSALESMAN = 10000;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log_account}}';
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'type' => 'Type',
            'before_price' => 'Before Price',
            'price' => 'Price',
            'desc' => 'Desc',
            'status' => 'Status',
            'create_at' => 'Create At',
            'account_type' => 'Account Type',
            'title' => 'Title',
        ];
    }

    //获取业务员合作费用
    public static function getPrice($memberid){
        $member = new Member();
        $memberaccount = new MemberAccount();
        $SystemConfig = new SystemConfig();
        $jianlijin = 0;
        $price = LogAccount::CREATE_SHOP_TO_SALESMAN;
        if($member->findOne(['member_id'=>$memberid])->member_type == 1){
            $price = LogAccount::CREATE_SHOP_TO_NOSALESMAN;//100
            $diffprice = LogAccount::CREATE_SHOP_TO_SALESMAN-LogAccount::CREATE_SHOP_TO_NOSALESMAN;
            $shopnum =$SystemConfig->findOne(['id'=>'shop_number'])->content;
            if($memberaccount->findOne(['member_id'=>$memberid])->shop_number >= ($shopnum-1)){
                $jianlijin = $diffprice*$shopnum;
            }
        }
        return [$price,$jianlijin];
    }

    /*
     * $category给钱类型1.独家买断费，2.联系店铺费，3.安装费，4.安装补助费
     * $price给多少钱
     * $type收入/支出
     * $title费用名称
     * $member_id给谁钱
     * $screen_number相关费用的屏幕数
     * $area地区
     * $desc注释
     */
    public static function writeLog($category,$price,$type,$title,$member_id=0,$screen_number=0,$area=0,$desc=''){
        if($price == 0){
            return true;
        }
        if(empty($member_id)){
            return true;
        }
        try{
            $shop_number = in_array($title,['店铺费用','安装人补贴费用'])?0:1;
            $logModel = new LogAccount();
            $logModel->member_id = $member_id;
            $logModel->type = 1;
            $logModel->before_price =(int)MemberAccount::getMemberPrice($member_id);
            $logModel->price = $price;
            $logModel->account_type = 1;
            $logModel->title = $title;
            $logModel->desc = $desc;
            $logModel->save();

            $accountModel = MemberAccount::getOrCreateAccount($member_id);
            $accountModel->loadAccount($category,$type, $price, $screen_number,$shop_number);
            $accountModel->save();//tatol
            $countModel = MemberAccountCount::getOrCreateAccount($member_id);
            $countModel->loadAccountCount($category,$type, $price, $screen_number,$shop_number);
            $countModel->save();//mouth

            $messageModel = new MemberAccountMessage();
            $messageModel->member_id = $member_id;
            $messageModel->title = '收入'.$title.ToolsClass::priceConvert($price).'元';
            $messageModel->save();
            MemberAreaCount::createAreaCount($member_id,$area,$screen_number);
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'db');
            return false;
        }
    }
}
