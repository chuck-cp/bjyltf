<?php

namespace cms\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%advert_position}}".
 *
 * @property int $id
 * @property int $type 广告形式(1、视频 2、图片)
 * @property string $name 广告位名称
 * @property int $rate 可购买频率
 * @property string $format 可播放格式
 * @property string $size 广告的尺寸
 * @property string $time 可购买广告时长(多个以逗号分割)
 * @property string $update_at 最后修改时间
 * @property int $create_user_id 操作人ID
 * @property string $create_user_name 操作人姓名
 */
class AdvertPosition extends \yii\db\ActiveRecord
{
    public $beishu;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advert_position}}';
    }

    /**
     * @inheritdoc
     */
//    public function rules()
//    {
//        return [
//            [['type', 'create_user_id'], 'integer'],
//            [['type', 'name', 'rate', 'format', 'size', 'spec', 'time'], 'required'],
//            [['update_at'], 'safe'],
//            [['name', 'format', 'size', 'time', 'create_user_name'], 'string', 'max' => 50],
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => '名称',
            'rate' => '频率',
            'format' => '格式',
            'size' => '尺寸',
            'time' => '时长',
            'update_at' => '更新时间',
            'create_user_id' => '操作人ID',
            'create_user_name' => '操作人',
        ];
    }

    /*
     * 获取广告位名称
     */
    public static function getAdvername($id)
    {
        $obj = self::findOne(['id'=>$id]);
        if ($obj) {
            return $obj->name == true ? $obj->name : '未设置';
        }
        return '未设置';
    }

    /*
     * 所有系统广告位
     */
    public static function getAllAdvertPos()
    {
        $prr = self::find()->select('id,name')->asArray()->all();
        if (!empty($prr)) {
            return ArrayHelper::map($prr, 'id', 'name');
        }
        return [];
    }

    //获取广告名称
    public static function getAllAdvertname($param = false){
        $res=self::find()->asArray()->all();
        foreach ($res as $k=>$list){
            $name[$list['name']] = $list['name'];
            $advert[$list['id']] = $list['name'];
        }
        if($param){
            return $advert;
        }else{
            return $name;
        }
    }
    /*
  * 广告位名称
  */
    public static function getAdvertPlace($adverid){
        $advertrarray =self::find()->where(['id'=>$adverid])->select('id,name')->asArray()->all();
        if(!empty($advertrarray)){
            return ArrayHelper::map($advertrarray,'id','name');
        }else{
            return array();
        }
    }

    //屏次倍数
    public static function getbeishu(){
        return [
            '1'=>'1倍',
            '2'=>'2倍',
            '3'=>'3倍',
            '4'=>'4倍',
            '5'=>'5倍',
            '6'=>'6倍',
            '7'=>'7倍',
            '8'=>'8倍',
            '9'=>'9倍',
            '10'=>'10倍',
        ];
    }

    /**
     * 获取所有广告位名称
     */
    public static function getAdventKeyName(){
        $KeyName=self::find()->select('id,key,name')->asArray()->all();
        return $KeyName;
    }
}
