<?php

namespace cms\modules\account\models;

use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_shop_apply_brokerage".
 *
 * @property string $id
 * @property string $shop_id 店铺ID
 * @property string $shop_name 店铺名称
 * @property string $area_name 店铺所属地区
 * @property string $address 详细地址
 * @property string $area_id 地区ID
 * @property string $apply_id 申请人ID
 * @property string $apply_name 法人姓名
 * @property string $apply_mobile 法人手机号
 * @property int $screen_number 屏幕数量
 * @property int $mirror_number 镜面数量
 * @property string $price 维护费金额(分)
 * @property int $grant_status 发放状态(0、未发放 1、已发放)
 * @property string $date 年月
 * @property string $create_at 创建时间
 * @property string $install_finish_at 安装完成时间
 */
class ShopApplyBrokerage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_shop_apply_brokerage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'shop_name', 'area_name', 'area_id', 'apply_name', 'apply_mobile'], 'required'],
            [['shop_id', 'area_id', 'apply_id', 'screen_number', 'mirror_number', 'price', 'grant_status', 'date'], 'integer'],
            [['create_at', 'install_finish_at'], 'safe'],
            [['shop_name'], 'string', 'max' => 255],
            [['area_name', 'apply_name'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 200],
            [['apply_mobile'], 'string', 'max' => 16],
            [['shop_id', 'date'], 'unique', 'targetAttribute' => ['shop_id', 'date']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => '店铺ID',
            'shop_name' => '店铺名称',
            'area_name' => '店铺所属地区',
            'address' => '详细地址',
            'area_id' => '地区ID',
            'apply_id' => '法人ID',
            'apply_name' => '法人姓名',
            'apply_mobile' => '法人手机号',
            'screen_number' => '屏幕数量',
            'mirror_number' => '镜面数量',
            'price' => '维护费金额',
            'grant_status' => '发放状态',
            'date' => '发放年月',
            'create_at' => '创建时间',
            'install_finish_at' => '安装完成时间',
        ];
    }

    //导出数据处理
    public static function ExportCsv($DataArr){
        foreach($DataArr as $k=>$v){
            $Csv[$k]['id']=$v['id'];//ID
            $Csv[$k]['shop_id']=$v['shop_id'];//店铺ID
            $Csv[$k]['shop_name']=$v['shop_name'];//店铺名称
            $Csv[$k]['area_name']=$v['area_name'];//店铺所属地区
            $Csv[$k]['address']=$v['address'];//详细地址
            $Csv[$k]['apply_id']=$v['apply_id'];//法人ID
            $Csv[$k]['apply_name']=$v['apply_name'];//法人姓名
            $Csv[$k]['apply_mobile']=$v['apply_mobile'];//法人手机号
            $Csv[$k]['date']=substr($v['date'],0,4).'年'.substr($v['date'],-2).'月';//维护费用时间周期
            $Csv[$k]['screen_number']=$v['screen_number'];//屏幕数量
            $Csv[$k]['mirror_number']=$v['mirror_number'];//镜面数量
            $Csv[$k]['install_finish_at']=$v['install_finish_at'];//安装完成时间
            $Csv[$k]['price']=ToolsClass::priceConvert($v['price']);//维护费用
            $Csv[$k]['grant_status']=$v['grant_status']==1?'发放':'未发放';
        }
        return $Csv;
    }
}
