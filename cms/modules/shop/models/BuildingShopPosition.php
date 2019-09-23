<?php

namespace cms\modules\shop\models;

use Yii;
use cms\modules\shop\models\BuildingShopPositionDifferent;
use common\libs\ToolsClass;
/**
 * This is the model class for table "yl_building_shop_position".
 *
 * @property string $id
 * @property string $member_id 业务员ID
 * @property int $shop_type 场景类型(1、写字楼 2、公园)
 * @property int $screen_type 设备类型(1、LED 2、画框)
 * @property string $shop_id building_shop表的ID
 * @property string $position_id building_position_config表的一级位置ID
 * @property string $position_config_id 本次提交的每个小位置的ID(多个以逗号分割)
 * @property int $screen_number 本次提交的设备总数量
 * @property int $monopoly 是否独占(1、独占 2、非独占)
 */
class BuildingShopPosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_building_shop_position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'shop_type', 'screen_type', 'shop_id', 'position_id', 'screen_number', 'monopoly'], 'integer'],
            [['position_config_id'], 'required'],
            [['position_config_id'], 'string', 'max' => 255],
            [['shop_id', 'shop_type', 'position_id'], 'unique', 'targetAttribute' => ['shop_id', 'shop_type', 'position_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'shop_type' => 'Shop Type',
            'screen_type' => 'Screen Type',
            'shop_id' => 'Shop ID',
            'position_id' => 'Position ID',
            'position_config_id' => 'Position Config ID',
            'screen_number' => 'Screen Number',
            'monopoly' => 'Monopoly',
        ];
    }

    /**
     * 商家信息楼宇的LED安装详情
     */
    public static function getFloorLedView($id,$screen_type){
        $aa = 0;
        $Data = [];
        $shopPositionIds = self::find()->where(['shop_id'=>$id,'screen_type'=>$screen_type])->select('id,position_id,monopoly,screen_number')->asArray()->all();
        if(!empty($shopPositionIds)){
            foreach ($shopPositionIds as $dk=>$dv){
                $Data[$dk]['position_id'] = $dv['position_id'];
                $Data[$dk]['monopoly'] = $dv['monopoly'];
                $DifferentDatas = BuildingShopPositionDifferent::find()->where(['shop_position_id'=>$dv['id']])->select('id,position_name')->asArray()->all();
                foreach($DifferentDatas as $ck=>$cv){
                    $countmap[] = $cv['id'];
                }
                $Data[$dk]['number'] = BuildingShopScreen::find()->where(['in','position_different_id',$countmap])->count();
                if(!empty($DifferentDatas)){
                    foreach ($DifferentDatas as $sk=>$sv){
                        $Data[$dk][$sk]['position_name'] = $sv['position_name'];
                        $ShopScreenData = BuildingShopScreen::find()->where(['position_different_id'=>$sv['id']])->asArray()->all();
                        if(!empty($ShopScreenData)){
                            foreach ($ShopScreenData as $kk=>$vv){
                                $Data[$dk][$sk][$kk]['position_name'] = $vv['position_name'];
                                $Data[$dk][$sk][$kk]['image_url'] = $vv['image_url'];
                                $Data[$dk][$sk][$kk]['device_number'] = $vv['device_number'];
                            }
                        }
                    }
                }
            }
        }
      //  ToolsClass::p($Data);die;
        if(!empty($Data))
            return $Data;
    }

    public static function getPostaionLable($shop_id,$screen_type){
        return $labels = self::find()->where(['shop_id'=>$shop_id,'screen_type'=>$screen_type])->select('id,position_id,monopoly')->asArray()->all();
    }

    public static function getFloorPosterView($position_id){
        $Data = [];
        $DifferentDatas = BuildingShopPositionDifferent::find()->where(['shop_position_id'=>$position_id])->select('id,position_name')->asArray()->all();
        foreach($DifferentDatas as $ck=>$cv){
            $countmap[] = $cv['id'];
        }
        $number = BuildingShopScreen::find()->where(['in','position_different_id',$countmap])->count();
        $model = self::findOne(['id'=>$position_id]);
        if(!empty($DifferentDatas)){
            foreach ($DifferentDatas as $sk=>$sv){
                $Data[$sk]['position_name'] = $sv['position_name'];
                $Data[$sk]['monopoly'] = $model->monopoly;
                $Data[$sk]['number'] =$number;
                $ShopScreenData = BuildingShopScreen::find()->where(['position_different_id'=>$sv['id']])->asArray()->all();
                if(!empty($ShopScreenData)){
                    foreach ($ShopScreenData as $kk=>$vv){
                        $Data[$sk][$kk]['position_name'] = $vv['position_name'];
                        $Data[$sk][$kk]['image_url'] = $vv['image_url'];
                        $Data[$sk][$kk]['device_number'] = $vv['device_number'];
                    }
                }
            }
        }
        return $Data;
    }
}
