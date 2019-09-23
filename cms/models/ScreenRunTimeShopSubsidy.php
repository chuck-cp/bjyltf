<?php

namespace cms\models;

use Yii;
use common\libs\ToolsClass;
/**
 * This is the model class for table "yl_screen_run_time_shop_subsidy".
 *
 * @property string $id
 * @property string $shop_id 店铺ID
 * @property string $shop_name 店铺名称
 * @property string $area_name 店铺所属地区
 * @property string $apply_name 法人姓名
 * @property string $apply_mobile 法人手机号
 * @property int $screen_number 屏幕数量
 * @property string $price 维护费金额(分)
 * @property int $status 状态(1、发放 2、不发放)
 * @property int $grant_status 发放状态(0、未发放 1、已发放)
 * @property string $date 年月
 * @property string $create_at 创建时间
 */
class ScreenRunTimeShopSubsidy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%screen_run_time_shop_subsidy}}';
    }
    /*public static function tableName()
    {
        return '{{%system_account}}';
    }*/

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    /*public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }*/

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'shop_name', 'area_name', 'apply_name', 'apply_mobile'], 'required'],
            [['shop_id', 'screen_number', 'price', 'status', 'grant_status', 'date'], 'integer'],
            [['create_at'], 'safe'],
            [['shop_name'], 'string', 'max' => 255],
            [['area_name', 'apply_name'], 'string', 'max' => 50],
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
            'shop_id' => 'Shop ID',
            'shop_name' => 'Shop Name',
            'area_name' => 'Area Name',
            'apply_name' => 'Apply Name',
            'apply_mobile' => 'Apply Mobile',
            'screen_number' => 'Screen Number',
            'price' => 'Price',
            'status' => 'Status',
            'grant_status' => 'Grant Status',
            'date' => 'Date',
            'create_at' => 'Create At',
        ];
    }

    /**
     * 导出数据处理
     */
    public static function ExportCsv($DataArr){
        foreach($DataArr as $k=>$v){
            $Csv[$k]['id']=$v['id'];
            $Csv[$k]['shop_id']=$v['shop_id'];
            $Csv[$k]['shop_name']=$v['shop_name'];
            $Csv[$k]['area_name']=$v['area_name'];
            $Csv[$k]['apply_id']=$v['apply_id'];
            $Csv[$k]['apply_name']=$v['apply_name'];
            $Csv[$k]['apply_mobile']=$v['apply_mobile'];
            $Csv[$k]['date']=substr($v['date'],0,4).'年'.substr($v['date'],-2).'月';
            $Csv[$k]['screen_number']=$v['screen_number'];
            $Csv[$k]['reduce_price']=ToolsClass::priceConvert($v['reduce_price']);
            $Csv[$k]['price']=ToolsClass::priceConvert($v['price']);
            $Csv[$k]['status']=$v['status']==1?'发放':'不发放';
        }
        return $Csv;
    }
}
