<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "{{%member_account}}".
 *
 * @property string $member_id
 * @property string $price
 * @property string $lower_price
 * @property string $count_price
 * @property integer $screen_number
 * @property integer $shop_number
 */
class MemberAccount extends \yii\db\ActiveRecord
{

    const INCOME = 1;
    const PAY = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'count_price', 'screen_number', 'shop_number', 'withdraw_price'], 'integer'],
            [['member_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
//            'lower_price' => 'Lower Price',
            'count_price' => 'Count Price',
            'screen_number' => 'Screen Number',
            'shop_number' => 'Shop Number',
            'withdraw_price' => '提现金额',
        ];
    }
    /**
     * 获取某人的奖励总额
     */

    public static function getMemTotalPrice($member_id){
        if(!$member_id){
            return ['price'=>0];
        }
        $obj = self::find()->where(['member_id'=>$member_id]);
        return $obj == true ? $obj->select('count_price')->asArray()->one() : ['count_price'=>0];
    }

    /*
   * 写入金钱信息
   * */
    public function loadAccount($category,$type,$price,$screen_number=0,$shop_number=0){
        if($type == self::INCOME){
            $this->count_price += $price;
            $this->balance += $price;
            if($category == 2){
                //联系
                $this->screen_number += $screen_number;
                $this->shop_number += $shop_number;
            }elseif($category == 3){
                //安装
                $this->install_screen_number += $screen_number;
                $this->install_shop_number += $shop_number;
            }
        }elseif($type == self::PAY){
            $this->balance -= $price;
        }
    }

    public static function getOrCreateAccount($member_id){
        $accountModel = new MemberAccount();
        if($resultModel = $accountModel->findOne(['member_id'=>$member_id])){
            return $resultModel;
        }
        $accountModel->member_id = $member_id;
        if($accountModel->save()){
            return $accountModel;
        }
        return false;
    }

    public static function getMemberPrice($memberid){
        $accountModel = MemberAccount::find()->where(['member_id'=>$memberid])->select('balance')->limit(1)->asArray()->one();
        if($accountModel){
            return $accountModel['balance'];
        }
        return false;
    }
}
