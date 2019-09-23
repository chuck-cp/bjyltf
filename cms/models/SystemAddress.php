<?php

namespace cms\models;
use common\libs\Redis;
use common\libs\ToolsClass;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%system_address}}".
 *
 * @property string $id
 * @property string $name
 * @property string $parent_id
 */
class SystemAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 80],
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
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * 获取某parent_id下的所有地区
     */
    public static function getAreasByPid($parent_id){
        if(empty($parent_id)){
            return [];
        }
        $addressModel = self::find()->where(['parent_id'=>$parent_id])->select('id,name')->asArray()->all();
        $addressModel = ArrayHelper::map($addressModel,'id','name');
        return $addressModel;
    }


    public static function getAdvertById($id){
        return $AdvertModel = self::find()->where(['id'=>$id])->select('id,name')->asArray()->one();
    }

    /**
     * 根据用户的所在区域id长度判断是返回省市县区镇
     */
    public static function getAreaByIdLen($id,$num){
        $len = strlen($id);
        switch ($num){
            case 5:
                if($len >=5){
                    return self::getAreaNameById(substr($id,0,5),'ONE');
                }else{
                    return '---';
                }
                break;
            case 7:
                if($len >=7){
                    return self::getAreaNameById(substr($id,0,7),'ONE');
                }else{
                    return '---';
                }
                break;
            case 9:
                if($len >=9){
                    return self::getAreaNameById(substr($id,0,9),'ONE');
                }else{
                    return '---';
                }
                break;
            case 11:
                if($len >=11){
                    return self::getAreaNameById($id,'ONE');
                }else{
                    return '---';
                }
                break;
            default:
                return self::getAreaNameById($id,'ONE');
        }
    }

    /*
     * 从redis中获取地区缓存
     * @area_id int 地区ID
     * @result string 获取一个地区还是一组地区
     * */
    public static function getAreaNameById($area_id,$result='ALL'){
        if(empty($area_id)){
            return false;
        }
        if(!$areaName = Redis::getInstance(2)->hget("system_address",$area_id)){
            if($systemAddress = SystemAddress::find()->where(['id'=>$area_id])->select('name')->asArray()->one()){
                $areaName = $systemAddress['name'];
                Redis::getInstance(2)->hset("system_address",$area_id,$areaName);
            }
        }
        if(empty($areaName) || $result == 'ONE'){
            return $areaName;
        }
        if($result == 'ALL'){
            $areaLen = strlen($area_id);
            if($areaLen < 5){
                return false;
            }
            if($areaLen == 12){
                $areaLen = $areaLen - 3;
            }else{
                $areaLen = $areaLen - 2;
            }
            return self::getAreaNameById(substr($area_id,0,$areaLen),'ALL') .' '.$areaName;
        }
    }


    /*
     * 获取地区下的街道数量
     * */
    public static function getStreetNumber($area_id_list){
        if(strlen($area_id_list[0]) == 12){
            return count($area_id_list);
        }
        $streetNumber = 0;
        foreach($area_id_list as $area_id){
            $streetNumber += SystemAddress::find()->where(['left(parent_id,'.strlen($area_id).')'=>$area_id,'level'=>6,'is_buy'=>1])->count();
        }
        return $streetNumber;
    }
}
