<?php

namespace cms\modules\member\models;

use cms\modules\config\models\MemberShopApplyRank;
use Yii;
use cms\models\SystemAddress;
/**
 * This is the model class for table "yl_member_shop_apply_count".
 *
 * @property string $id
 * @property string $member_id 用户ID
 * @property int $shop_number 联系的店铺数量
 * @property string $screen_number 联系屏幕的数量
 * @property string $create_at 统计日期
 */
class MemberShopApplyCount extends \yii\db\ActiveRecord
{
    public $totalshop;
    public $totalscreen;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member_shop_apply_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'shop_number', 'screen_number'], 'integer'],
            [['create_at'], 'required'],
            [['create_at'], 'safe'],
            [['member_id', 'create_at'], 'unique', 'targetAttribute' => ['member_id', 'create_at']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户ID',
            'shop_number' => 'Shop Number',
            'screen_number' => 'Screen Number',
            'create_at' => 'Create At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * 导出数据处理
     */
    public static function ExportCsv($Data){
        foreach ($Data['data'] as $k=>$v){
            $Csv[$k]['id']=$v['id'];
            $Csv[$k]['name']=$v['memInfo']['name'];
            $Csv[$k]['mobile']=$v['memInfo']['mobile'];
            $Csv[$k]['area_name']=$v['memInfo']['area_name'];
            $Csv[$k]['admin_area']=SystemAddress::getAreaByIdLen($v['memInfo']['admin_area']);
            $Csv[$k]['shop_number']=$v['sum(shop_number)'];
            $Csv[$k]['screen_number']=$v['sum(screen_number)'];
        }
        return $Csv;
    }

    //获取人员信息
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('id,name,mobile,admin_area,area,area_name');
    }
    //获取人员信息
    public function getMemberShopApplyRank(){
        return $this->hasOne(MemberShopApplyRank::className(),['member_id'=>'member_id'])->select('id,member_id,wait_install_shop_number,wait_install_screen_number');
    }
}
