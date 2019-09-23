<?php

namespace cms\modules\account\models;

use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use Yii;

/**
 * This is the model class for table "yl_member_reward_member".
 *
 * @property string $id
 * @property string $b_member_id 此用户在B2B系统中的用户ID
 * @property string $bind_id B2B绑定关系ID
 * @property string $member_id 用户ID
 * @property string $shop_id 店铺ID
 * @property string $shop_name 店铺名称
 * @property string $head_id 总部ID
 * @property string $nickname 昵称
 * @property string $mobile 手机号
 * @property string $software_number 屏幕的软件编码
 * @property string $reward_price 奖励金
 * @property string $create_at 扫描时间
 */
class MemberRewardMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member_reward_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['b_member_id', 'bind_id', 'member_id', 'shop_id', 'head_id', 'reward_price'], 'integer'],
            [['software_number'], 'required'],
            [['create_at'], 'safe'],
            [['shop_name'], 'string', 'max' => 100],
            [['nickname'], 'string', 'max' => 30],
            [['mobile'], 'string', 'max' => 11],
            [['software_number'], 'string', 'max' => 32],
            [['b_member_id'], 'unique'],
            [['bind_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'b_member_id' => 'B Member ID',
            'bind_id' => 'Bind ID',
            'member_id' => 'Member ID',
            'shop_id' => 'Shop ID',
            'shop_name' => 'Shop Name',
            'head_id' => 'Head ID',
            'nickname' => 'Nickname',
            'mobile' => 'Mobile',
            'software_number' => 'Software Number',
            'reward_price' => 'Reward Price',
            'create_at' => 'Create At',
        ];
    }
}
