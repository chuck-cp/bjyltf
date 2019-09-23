<?php

namespace cms\modules\systemstartup\models;

use Yii;

/**
 * This is the model class for table "{{%system_startup}}".
 *
 * @property string $id
 * @property string $version
 * @property integer $visibility
 * @property string $start_at
 * @property string $end_at
 * @property string $start_pic
 * @property string $link
 * @property string $create_user_id
 * @property string $create_user_name
 * @property string $create_at
 */
class SystemStartup extends \yii\db\ActiveRecord
{
    public $single_pic;
    public $haslink;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_startup}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_at', 'end_at', 'link'],'required'],
            [['visibility', 'create_user_id','type','haslink'], 'integer'],
            [['version'], 'string', 'max' => 3],
            [['link'], 'string', 'max' => 255],
            [['link'],'url'],
            //[['start_pic','single_pic'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'version' => '对应版本',
            'visibility' => '用户可见次数',
            'start_at' => '启用时间',
            'end_at' => '结束时间',
            'start_pic' => '图片',
            'single_pic' => '单图图片',
            'link' => '链接',
            'create_user_id' => 'Create User ID',
            'create_user_name' => '上传者',
            'create_at' => '上传时间',
            'type' => '类型',
            'haslink' => '有无链接',
        ];
    }

}
