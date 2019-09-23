<?php

namespace cms\modules\sign\models;

use Yii;

/**
 * This is the model class for table "yl_sign_image".
 *
 * @property string $sign_id 签到表的ID
 * @property int $sign_type 签到类型(1、业务员签到 2、维护人员签到到)
 * @property string $image_url 店铺图片(多个图片以逗号分割)
 */
class SignImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sign_id', 'image_url'], 'required'],
            [['sign_id', 'sign_type'], 'integer'],
            [['image_url'], 'string'],
            [['sign_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sign_id' => 'Sign ID',
            'sign_type' => 'Sign Type',
            'image_url' => 'Image Url',
        ];
    }

    /**
     * 获取拍照图片
     */
    public static function signImg($id,$type){
        $imges=self::find()->where(['sign_id'=>$id,'sign_type'=>$type])->select('image_url')->asArray()->one()['image_url'];
        return explode(',',$imges);
    }
}
