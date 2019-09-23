<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "yl_member_install_subsidy".
 *
 * @property string $id
 * @property string $install_member_id 安装人用户ID
 * @property int $install_shop_number 今日安装店铺数量
 * @property int $install_screen_number 今日安装屏幕数量
 * @property int $assign_shop_number 指派的店铺数量
 * @property int $assign_screen_number 指派的屏幕数量
 * @property string $income_price 今日的收入(分)
 * @property string $subsidy_price 今日补贴金额(分)
 * @property string $create_at 日期
 */
class MemberInstallSubsidy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member_install_subsidy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['install_member_id', 'install_shop_number', 'install_screen_number', 'assign_shop_number', 'assign_screen_number', 'income_price', 'subsidy_price'], 'integer'],
            [['create_at'], 'required'],
            [['create_at'], 'safe'],
            [['install_member_id', 'create_at'], 'unique', 'targetAttribute' => ['install_member_id', 'create_at']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'install_member_id' => 'Install Member ID',
            'install_shop_number' => 'Install Shop Number',
            'install_screen_number' => 'Install Screen Number',
            'assign_shop_number' => 'Assign Shop Number',
            'assign_screen_number' => 'Assign Screen Number',
            'income_price' => 'Income Price',
            'subsidy_price' => 'Subsidy Price',
            'create_at' => 'Create At',
        ];
    }


    //更新今日补贴金额
    public static function subsidyprice($subsidy_price,$id){
        MemberInstallSubsidy::updateAllCounters(['subsidy_price' => $subsidy_price], ['id' => $id]);
    }

    /**
     * 获得用户常驻地址
     */
    public function getMemberArea(){
        return $this->hasOne(MemberInfo::className(),['member_id'=>'install_member_id'])->select('member_id, live_area_id, live_area_name');
    }
    /**
     * 获取用户信息
     */
    public function getMemberNameMobile(){
        return $this->hasOne(Member::className(),['id'=>'install_member_id'])->select('id,name,mobile');
    }
}
