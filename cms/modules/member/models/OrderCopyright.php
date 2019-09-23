<?php

namespace cms\modules\member\models;

use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_order_copyright".
 *
 * @property string $id
 * @property string $order_id 关联yl_order表的id
 * @property string $image_url 图片地址
 */
class OrderCopyright extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_order_copyright';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'image_url'], 'required'],
            [['order_id'], 'integer'],
            [['image_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'image_url' => 'Image Url',
        ];
    }
    /**
     * 获取图片地址
     */
    public static function getImgUrl($id){
        $ImageUrlAray=self::find()->where(['order_id'=>$id])->select('id,image_url')->asArray()->all();
        if($ImageUrlAray){
            return $ImageUrlAray;
        }
        return [];
    }
}
