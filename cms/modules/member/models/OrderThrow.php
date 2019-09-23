<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "yl_order_throw".
 *
 * @property string $serial_number 流水号
 * @property string $area 地区ID
 * @property string $start_at 开始投放时间
 * @property string $end_at 投放结束时间
 * @property int $number 资源数量
 * @property int $status 状态(0、待处理 1、已上传  2、投放中、3、投放完毕)
 * @property string $resource 资源内容
 * @property string $video_id 腾讯云的视频ID
 */
class OrderThrow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_order_throw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
//        return [
//            [['area', 'number', 'status'], 'integer'],
//            [['start_at', 'end_at'], 'required'],
//            [['start_at', 'end_at'], 'safe'],
//            [['serial_number'], 'string', 'max' => 18],
//            [['resource'], 'string', 'max' => 255],
//            [['video_id'], 'string', 'max' => 50],
//        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'serial_number' => 'Serial Number',
            'area' => 'Area',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'number' => 'Number',
            'status' => 'Status',
            'resource' => 'Resource',
            'video_id' => 'Video ID',
        ];
    }
}
