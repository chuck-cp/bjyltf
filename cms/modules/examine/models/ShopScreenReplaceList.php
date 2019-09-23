<?php

namespace cms\modules\examine\models;

use Yii;

/**
 * This is the model class for table "yl_shop_screen_replace_list".
 *
 * @property string $id
 * @property string $replace_id screen_replace表的主键
 * @property string $device_number 设备编号
 * @property string $replace_device_number 替换后的设备编号
 * @property string $replace_desc 更换理由
 */
class ShopScreenReplaceList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_shop_screen_replace_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['replace_id'], 'integer'],
            //[['device_number', 'replace_device_number'], 'string', 'max' => 30],
            //[['replace_desc'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'replace_id' => 'Replace ID',
            'device_number' => 'Device Number',
            'replace_device_number' => 'Replace Device Number',
            'replace_desc' => 'Replace Desc',
        ];
    }

    //获取更换详情
    public function getReplace(){
        return $this->hasOne(ShopScreenReplace::className(),['id'=>'replace_id']);
    }
}
