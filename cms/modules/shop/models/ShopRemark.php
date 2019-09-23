<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "yl_shop_remark".
 *
 * @property string $id
 * @property int $shop_id 店铺ID
 * @property string $content 备注内容
 * @property int $create_user_id 创建人ID
 * @property string $create_user_name 创建人姓名
 * @property int $create_user_type 创建人类别(1、客服 2、管理员)
 * @property string $create_at 创建时间
 */
class ShopRemark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_shop_remark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'content', 'create_user_name'], 'required'],
            [['shop_id', 'create_user_id', 'create_user_type'], 'integer'],
            [['create_at'], 'safe'],
            [['content'], 'string', 'max' => 255],
            [['create_user_name'], 'string', 'max' => 20],
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
            'content' => 'Content',
            'create_user_id' => 'Create User ID',
            'create_user_name' => 'Create User Name',
            'create_user_type' => 'Create User Type',
            'create_at' => 'Create At',
        ];
    }

    /**
     * 根据店铺id获取备注信息
     */
    public static function getRemarkArr($shop_id){
        return ShopRemark::find()->where(['shop_id'=>$shop_id])->orderBy('create_at desc')->asArray()->all();
    }
}
