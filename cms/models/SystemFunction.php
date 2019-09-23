<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "yl_system_function".
 *
 * @property integer $id
 * @property string $name
 * @property string $image_url
 * @property string $target
 * @property integer $status
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
            [['name', 'image_url', 'target'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['image_url'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'image_url' => 'Image Url',
            'target' => 'Target',
            'status' => 'Status',
        ];
    }
}
