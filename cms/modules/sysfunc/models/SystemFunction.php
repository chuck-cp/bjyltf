<?php

namespace cms\modules\sysfunc\models;

use Yii;

/**
 * This is the model class for table "{{%system_function}}".
 *
 * @property int $id
 * @property string $name 功能名称
 * @property string $image_url 图片地址
 * @property string $target 目标地址
 * @property int $status 状态(1、开启 2、关闭)
 */
class SystemFunction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_function}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['image_url','link_url'], 'string', 'max' => 255],
            [['target'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '模块',
            'image_url' => '图片',
            'link_url' => '链接地址',
            'target' => '目标地址',
            'status' => '状态',
        ];
    }
}
