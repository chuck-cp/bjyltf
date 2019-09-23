<?php

namespace cms\modules\withdraw\models;

use Yii;

/**
 * This is the model class for table "{{%member_account_count}}".
 *
 * @property string $id
 * @property string $member_id 用户ID
 * @property string $count_price 账户总收入
 * @property string $withdraw_price 提现金额
 * @property int $screen_number 安装屏幕总台数
 * @property int $shop_number 签约店铺总数
 * @property string $create_at 日期
 */
class MemberAccountCount extends \yii\db\ActiveRecord
{
    const INCOME = 1;
    const PAY = 2;
    const PERSONAL = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_account_count}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'count_price', 'withdraw_price', 'screen_number', 'shop_number'], 'integer'],
            [['create_at'], 'string', 'max' => 7],
            [['member_id', 'create_at'], 'unique', 'targetAttribute' => ['member_id', 'create_at']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'count_price' => 'Count Price',
            'withdraw_price' => 'Withdraw Price',
            'screen_number' => 'Screen Number',
            'shop_number' => 'Shop Number',
            'create_at' => 'Create At',
        ];
    }

    //写入按月统计的金钱信息
    public function loadAccountCount($category,$type,$price,$screen_number=0,$shop_number=0){
        if($type == self::INCOME){
            $this->count_price += $price;
            if($category == 2){
                //联系
                $this->screen_number += $screen_number;
                $this->shop_number += $shop_number;
            }elseif($category == 3){
                //安装
                $this->install_screen_number += $screen_number;
                $this->install_shop_number += $shop_number;
            }
        }
    }

    public static function getOrCreateAccount($member_id,$create_at=''){
        $accountModel = new MemberAccountCount();
        $create_at = empty($create_at) ? date('Y-m') : $create_at;
        if($resultModel = $accountModel->findOne(['member_id'=>$member_id,'create_at'=>$create_at])){
            return $resultModel;
        }
        $accountModel->member_id = $member_id;
        $accountModel->create_at = $create_at;
        if($accountModel->save()){
            return $accountModel;
        }
        return false;
    }


}
