<?php

namespace console\models;

use common\libs\ToolsClass;
use Yii;
use yii\base\Exception;

/**
 * 业务员收入
 */
class MemberAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_account}}';
    }

    public static function getBalanceByMemberId($member_id){
        $accountModel = MemberAccount::find()->where(['member_id'=>$member_id])->select('balance')->asArray()->one();
        if(!empty($accountModel)){
            return $accountModel['balance'];
        }
        return 0;
    }

    public static function addMoney($member_id,$money,$salesman_id,$is_self,$desc = ''){
        if (empty($money)) {
            return true;
        }
        try {
            switch ($is_self){
                case 'member_parent_price':
                    $accountTitle = '介绍业务合作人佣金';
                    $messageTitle = '收入介绍业务合作人佣金';
                    break;
                case 'cooperate':
                    $accountTitle = '区域配合费';
                    $messageTitle = '收入区域配合费';
                    break;
                case 'subsidy':
                    $accountTitle = '设备维护费';
                    $messageTitle = '收入设备维护费';
                    break;
                case 'myself':
                    $accountTitle = '广告佣金';
                    $messageTitle = '收入广告佣金';
            }
            //写日志
            $logAccount = new LogAccount();
            $logAccount->member_id = $member_id;
            $logAccount->type=1;
            $logAccount->before_price = MemberAccount::getBalanceByMemberId($member_id);
            $logAccount->price = $money;
            $logAccount->account_type = 6;
            $logAccount->desc = $desc;
            $logAccount->title=$accountTitle;
            $logAccount->create_at = date("Y-m-d H:i:s");
            $logAccount->save();

            $messageModel = new MemberAccountMessage();
            $messageModel->member_id = $member_id;
            $messageModel->title = $messageTitle.ToolsClass::priceConvert($money).'元';
            $messageModel->save();

            //写支出总额
            SystemAccount::updateAllCounters(["adv_expend"=>$money],['id'=>1]);

            $frozen_price = 0; //冻结金额
            $informal_frozen = 0; //非正式人员冻结金额
            if($salesman_id == $member_id){
                //判断是否需要冻结金额
                $frozen_price = SystemConfig::getConfig('advert_price_reserved')*$money/100;//计算冻结金额
                $money -= $frozen_price;
            }
            /*********************************/
            if($is_self == 'member_parent_price'){
                $member_type = Member::getMemberFieldByWhere(['id'=>$member_id],'member_type');
                //非正式人员
                if($member_type !== 2){
                    $frozen_price = $money;
                    $informal_frozen = $money;
                    $money = 0;
                }
            }
            /*********************************/
            //发放资金
            if($countModel = MemberAccount::find()->where(['member_id'=>$member_id])->count()){
                MemberAccount::updateAllCounters(['count_price'=>$money,'balance'=>$money,'frozen_price'=>$frozen_price, 'informal_frozen'=>$informal_frozen],['member_id'=>$member_id]);
            }else{
                $memberAccountCount =new MemberAccount();
                $memberAccountCount->member_id = $member_id;
                $memberAccountCount->count_price = $money;
                $memberAccountCount->balance = $money;
                $memberAccountCount->frozen_price = $frozen_price;
                $memberAccountCount->informal_frozen = $informal_frozen;
                $memberAccountCount->save();
            }

            //业绩累加
            if($countModel = MemberAccountCount::find()->where(['member_id'=>$member_id,'create_at'=>date("Y-m")])->select('id')->asArray()->one()){
                MemberAccountCount::updateAllCounters(["count_price"=>$money,'frozen_price'=>$frozen_price],['id'=>$countModel['id']]);
            }else{
                $memberAccountCount =new MemberAccountCount();
                $memberAccountCount->count_price = $money;
                $memberAccountCount->frozen_price = $frozen_price;
                $memberAccountCount->member_id = $member_id;
                $memberAccountCount->create_at = date("Y-m");
                $memberAccountCount->save();
            }
            return true;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }

    }
}
