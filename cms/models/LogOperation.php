<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "yl_log_operation".
 *
 * @property string $id
 * @property int $operation_type 操作类型(1、签约失败)
 * @property string $foreign_id 外部ID
 * @property string $content 描述
 * @property string $create_at 创建时间
 */
class LogOperation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_log_operation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['operation_type', 'foreign_id'], 'integer'],
            [['foreign_id', 'content'], 'required'],
            [['create_at'], 'safe'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operation_type' => 'Operation Type',
            'foreign_id' => 'Foreign ID',
            'content' => 'Content',
            'create_at' => 'Create At',
        ];
    }
}
