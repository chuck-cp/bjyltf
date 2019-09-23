<?php

namespace cms\modules\config\models;

use cms\models\SystemAddress;
use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_system_address_level".
 *
 * @property string $area_id 地区ID
 * @property int $level 等级
 * @property int $type 地区等级类型(1、买断费和奖励金所用地区 2、广告价格所用地区)
 */
class SystemAddressLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_system_address_level';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id'], 'required'],
            [['area_id', 'level', 'type'], 'integer'],
            [['area_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'area_id' => 'Area ID',
            'level' => 'Level',
            'type' => 'Type',
        ];
    }

    /**
     * 获取某价格下的地区,地区价格设置
     */
    public static function getAreaLevel($level){
        $area = self::find()->where(['level'=>$level])->select('area_id,level')->asArray()->all();
        if(empty($area)){
            return '';
        }
//        foreach($area as $ka=>$va){
//            $newarea[$va['level']][]=$va['area_id'];
//        }
//        foreach($newarea as $karea=>$varea) {
            foreach ($area as $k => $v) {
                $province = substr($v['area_id'], 0, 5);
                $city = substr($v['area_id'], 0, 7);
                $areaAll[$province][$city][] = $v['area_id'];
            }
//        }
        $areas = [];
//        foreach ($areaAll as $knum => $vnum) {
            foreach ($areaAll as $k => $v) {
                foreach ($v as $kt => $vt) {
                    foreach ($vt as $kth => $vth) {
                        $areaName = SystemAddress::getAreaByIdLen($vth, 9);
                        if(!empty($areaName)){
                            $areas[$k][$kt][$vth] = $areaName;
                        }else{
                            $systemarea = SystemAddress::find()->where(['id'=>$vth])->asArray()->one();
                            $areas[$k][$kt][$vth] = $systemarea['name'];
                        }
                    }
                }
            }
//        }
        return $areas;
    }

    //区域等级名称
    public static function getNameByLevel($level){
        $srr = [
            '1'=>'一级区域',
            '2'=>'二级区域',
            '3'=>'三级区域',
        ];
//        return $srr[$level];
        return array_key_exists($level,$srr) ? $srr[$level] : '未设置';
    }

    /**
     * 获取某等级区域下地区名称
     */
    public static function getAreaBylevl($level = false,$type = 'string',$length = 10){
        if($level > 0){
            $area = self::find()->where(['level'=>$level])->select('area_id')->limit(10)->asArray()->all();
            $areas = $type == 'string' ? '' : [];
            if(!empty($area)){
                foreach ($area as $k => $v){
                    $areas .= SystemAddress::getAreaByIdLen($v['area_id'],9).' ';
                    /*if($type == 'string'){
                        if($k < $length){
                            $areas .= SystemAddress::getAreaByIdLen($v['area_id'],9).' ';
                        }
                    }else{
                        $areas[$v['area_id']] = SystemAddress::getAreaByIdLen($v['area_id'],9);
                    }*/
                }
            }else{
                return '暂未设置地区';
            }

            return $areas;
        }else{
            return '---';
        }
    }

    /**
     * 通过区域获取等级
     */
    public static function getlevelById($id){
        $areaid = substr($id,0,9);
        $area = self::findOne(['area_id'=>$areaid]);
        if($area){
            return  $area->getAttribute('level');
        }else{
            return 0;
        }
    }

    /**
     * 获取某价格下的地区,地区价格设置
     * wpw
     * 2018-08-05
     */
    public static function getAreaPrice($id){
//        $priceList = SystemZoneList::findOne($id);
//        if($type==1){
        $area = self::find()->where(['level'=>$id])->select('area_id')->asArray()->all();
//        }else{
//            $area = self::find()->where(['subsidy_id'=>$id])->select('area_id')->asArray()->all();
//        }
        foreach($area as $k=>$v){
            $province=substr($v['area_id'],0,5);
            $city=substr($v['area_id'],0,7);
            $areaAll[$province][$city][]=$v['area_id'];
        }
        $areas = [];
        if(!empty($area)){
            foreach ($areaAll as $k => $v){
                foreach ($v as $kt => $vt) {
                    foreach ($vt as $kth => $vth) {
                        $areaName = SystemAddress::getAreaByIdLen($vth, 9);
                        if(!empty($areaName)){
                            $areas[$k][$kt][$vth] = $areaName;
                        }else{
                            $systemarea = SystemAddress::find()->where(['id'=>$vth])->asArray()->one();
                            $areas[$k][$kt][$vth] = $systemarea['name'];
                        }
                    }
                }
            }
        }
        return $areas;
    }

}
