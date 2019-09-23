<?php

namespace cms\modules\screen\models;

use Yii;

/**
 * This is the model class for table "{{%member_shop_area}}".
 *
 * @property string $id
 * @property int $member_id 用户ID
 * @property int $type 类型(1、安装业务 2、业务查询 3、屏幕管理)
 * @property string $area 地区ID
 */
class MemberShopArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_shop_area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'type', 'area'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'type' => 'Type',
            'area' => 'Area',
        ];
    }
}
