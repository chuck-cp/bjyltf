<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "yl_shop_abnormal".
 *
 * @property string $id
 * @property string $shop_id 店铺ID
 * @property string $shop_name 店铺名称
 * @property int $status 状态(0、未处理 1、已处理)
 * @property string $create_at 提交日期
 */
class ShopAbnormal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_shop_abnormal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'shop_name', 'create_at'], 'required'],
            [['shop_id', 'status'], 'integer'],
            [['create_at'], 'safe'],
            [['shop_name'], 'string', 'max' => 100],
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
            'status' => '状态',
            'create_at' => '时间',
        ];
    }
}
