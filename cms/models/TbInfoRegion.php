<?php

namespace cms\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "tb_info_region".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $parent_id
 * @property integer $zip_code
 * @property integer $status
 */
class TbInfoRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_info_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'type', 'parent_id'], 'required'],
            [['id', 'type', 'parent_id', 'zip_code', 'status'], 'integer'],
            [['name'], 'string', 'max' => 20],
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
            'type' => 'Type',
            'parent_id' => 'Parent ID',
            'zip_code' => 'Zip Code',
            'status' => 'Status',
        ];
    }
    /**
     *获取parents_id下的所有地区
     */
    public static function getAreasByPid($parent_id){
        if(!$parent_id){
            return [];
        }
        if(!Yii::$app->cache->get('tb_address')){
            $address = self::buildAreaCache();
        }else{
            $address = json_decode(Yii::$app->cache->get('tb_address'),true);
        }
        return isset($address[$parent_id]) ? $address[$parent_id] : [];
    }
    /**
     * 生成系统地址缓存（'parent_id'=>所属所有地区）
     */
    public static function buildAreaCache(){
        $adr = self::find()->where('type<7')->select('id,name,parent_id')->asArray()->all();
        $adrList = [];
        foreach ($adr as $k){
            $adrList[$k['parent_id']][$k['id']] = $k['name'];
        }
        Yii::$app->cache->set('tb_address',json_encode($adrList,true));
        return $adrList;
    }
    /**
     * 获取某个id对应的地区名
     */
    public static function getOneArea($id){
        if(!$id){
            return '';
        }
        if(!Yii::$app->cache->get('tb_every_area')){
            $area = self::buildEveryArea()[$id];
        }else{
            $address = json_decode(Yii::$app->cache->get('tb_every_area'),true);
            $area = isset($address[$id]) ? $address[$id] : '';
        }
        return $area;
    }
    /**
     * 根据用户的所在区域id长度判断是返回省市县区镇
     */
    public static function getAreaByIdLen($id,$num){
        $len = strlen($id);
        switch ($num){
            case 5:
                if($len >=5){
                    return self::getOneArea(substr($id,0,5));
                }else{
                    return '---';
                }
                break;
            case 7:
                if($len >=7){
                    return self::getOneArea(substr($id,0,7));
                }else{
                    return '---';
                }
                break;
            case 9:
                if($len >=9){
                    return self::getOneArea(substr($id,0,9));
                }else{
                    return '---';
                }
                break;
            case 11:
                if($len >=11){
                    return self::getOneArea(substr($id,0,11));
                }else{
                    return '---';
                }
                break;
            default:
                return self::getOneArea($id);
        }
    }
    /**
     *生成系统地址缓存（'id'=>地区，1:1）
     */
    public static function buildEveryArea(){
        $adr = self::find()->select('id,name')->asArray()->all();
        $adr = ArrayHelper::map($adr,'id','name');
        Yii::$app->cache->set('tb_every_area',json_encode($adr,true));
        return $adr;
    }
}
