<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "{{%member_shop_count}}".
 *
 * @property int $member_id 用户ID
 * @property int $admin_screen_number 管理屏幕总数
 * @property int $admin_shop_number 管理店铺总数
 */
class MemberShopCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_shop_count}}';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
            'admin_screen_number' => 'Admin Screen Number',
            'admin_shop_number' => 'Admin Shop Number',
        ];
    }


    //
    public static function updateOrCreate($member_id,$screen_number,$shop_number){
        if($countModel = MemberShopCount::findOne(['member_id'=>$member_id])){
            $countModel->admin_screen_number += $screen_number;
            $countModel->admin_shop_number += $shop_number;
            return $countModel->save();
        }
        $countModel = new MemberShopCount();
        $countModel->admin_screen_number = $screen_number;
        $countModel->admin_shop_number = $shop_number;
        $countModel->member_id = $member_id;
        return $countModel->save();
    }

}
