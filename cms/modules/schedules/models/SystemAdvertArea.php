<?php

namespace cms\modules\schedules\models;

use Yii;

/**
 * This is the model class for table "yl_system_advert_area".
 *
 * @property string $id
 * @property string $advert_id 系统广告表ID
 * @property string $area_id 地区ID
 */
class SystemAdvertArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_system_advert_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['advert_id', 'area_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'advert_id' => 'Advert ID',
            'area_id' => 'Area ID',
        ];
    }

    public static function AreaIdArr($area_id,$advert_id){
        $AreaCount=self::find()->where(['advert_id'=>$advert_id,'area_id'=>$area_id])->count();
        if($AreaCount>0)
            return 1;
        return 2;
    }
}
