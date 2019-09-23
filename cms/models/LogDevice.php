<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "yl_log_device".
 *
 * @property string $id
 * @property string $device_number 设备硬件编码
 * @property string $receiver_name 接收人名称
 * @property string $receiver_id 接收人的ID(个人或仓库)
 * @property int $operation_type 操作类型(1、入库 2、出库)
 * @property string $create_at 创建时间
 */
class LogDevice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_log_device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_number', 'receiver_name'], 'required'],
            [['receiver_id', 'operation_type','create_user_id'], 'integer'],
            [['create_at'], 'safe'],
            [['device_number', 'receiver_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_number' => 'Device Number',
            'receiver_name' => 'Receiver Name',
            'receiver_id' => 'Receiver ID',
            'operation_type' => 'Operation Type',
            'create_at' => 'Create At',
            'create_user_id' => 'create_user_id',
        ];
    }

    //写日志
    public static function addlog($device_number,$member,$type,$status){
        $log_device = new LogDevice();
        $log_device->device_number=$device_number;//设备硬件编码
        if($type == 1){
            //个人
            $log_device->receiver_name=$member['name'];//接收人名称/办事处
            $log_device->receiver_id=$member['id'];//接收人的ID(个人或办事处)
        }else{
            //办事处
            $log_device->receiver_name=$member['office_name'];//接收人名称/办事处
            $log_device->receiver_id=$member['id'];//接收人的ID(个人或办事处)
        }
        $log_device->operation_type=$status;//1入库/2出库
        $log_device->create_user_id = Yii::$app->user->identity->getId();
        $log_device->save();

    }

}
