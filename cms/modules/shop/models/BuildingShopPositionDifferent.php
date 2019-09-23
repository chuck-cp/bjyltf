<?php

namespace cms\modules\shop\models;

use Yii;
use cms\modules\shop\models\BuildingShopScreen;
use cms\modules\shop\models\BuildingPositionConfig;
/**
 * This is the model class for table "yl_building_shop_position_different".
 *
 * @property string $id
 * @property string $shop_position_id building_shop_position表的ID
 * @property int $floor_number 楼层编号
 * @property string $position_number 位置编号(如男1、男2等)
 * @property string $position_config_number 本次提交的具体每个小位置设备数量(多个以逗号分割)
 * @property string $screen_spec 设备规格
 * @property string $screen_start_at 设备开机时间(LED设备专用)
 * @property string $screen_end_at 设备关机时间(LED设备专用)
 * @property string $description 备注
 */
class BuildingShopPositionDifferent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_building_shop_position_different';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_position_id', 'floor_number'], 'integer'],
            [['position_number'], 'required'],
            [['position_number', 'screen_spec', 'screen_start_at', 'screen_end_at'], 'string', 'max' => 10],
            [['position_config_number', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_position_id' => 'Shop Position ID',
            'floor_number' => 'Floor Number',
            'position_number' => 'Position Number',
            'position_config_number' => 'Position Config Number',
            'screen_spec' => 'Screen Spec',
            'screen_start_at' => 'Screen Start At',
            'screen_end_at' => 'Screen End At',
            'description' => 'Description',
        ];
    }

    public static function getDifferentDatas($shop_position_id){
        $datas = self::find()->where(['shop_position_id'=>$shop_position_id])->asArray()->all();
        $InstallDatas =[];
        foreach($datas as $k=>$v){
            $ScreenDatas = BuildingShopScreen::find()->where(['position_different_id'=>$v['id']])->asArray()->all();
            $InstallDatas[$k]['floor_number'] = $v['floor_number'];
            $InstallDatas[$k]['position_name'] = $v['position_name'];
            foreach ($ScreenDatas as $kk=>$vv){
                $InstallDatas[$k][$kk]['position_name'] = $vv['position_name'];
                $InstallDatas[$k][$kk]['image_url'] = $vv['image_url'];
                $InstallDatas[$k][$kk]['device_number'] = $vv['device_number'];
                //$InstallDatas[$k][$kk]['screen_spec'] = $v['screen_spec'];
            }
        }
        return $InstallDatas;
    }


}
