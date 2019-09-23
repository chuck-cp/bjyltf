<?php

namespace cms\modules\config\models;

use common\libs\ToolsClass;
use Yii;
use cms\models\SystemAddress;

/**
 * This is the model class for table "yl_system_zone_price".
 *
 * @property string $area_id 表主键
 * @property string $price_id 区域价格ID
 * @property string $price 区域费用（分）
 * @property string $subsidy_id 补助价格ID
 * @property string $subsidy_price 补助费用（分）
 */
class SystemZonePrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_system_zone_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id'], 'required'],
            [['area_id', 'price_id'], 'integer'],//'price', 'subsidy_id', 'subsidy_price'
            [['area_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'area_id' => '地区ID',
            'price_id' => '地区价格ID',
//            'price' => '地区价格',
//            'subsidy_id' => '补助价格ID',
//            'subsidy_price' => '补助价格',
        ];
    }

    /**
     * 获取某价格下的地区
     */
    public static function getAreaByPrice($priceid = false,$type = 'string',$length = 10){
        if($priceid > 0){
            $area = self::find()->where(['price_id'=>$priceid])->select('area_id')->asArray()->all();
            $areas = $type == 'string' ? '' : [];
            if(!empty($area)){
                foreach ($area as $k => $v){
                    if($type == 'string'){
                        if($k < $length){
                            $areas .= SystemAddress::getAreaByIdLen($v['area_id'],9).' ';
                        }
                    }else{
                        $areas[$v['area_id']] = SystemAddress::getAreaByIdLen($v['area_id'],9);
                    }
                }
            }else{
                return '暂未设置地区';
//                return '<span font-color="green">暂未设置地区</span>';
            }
            return $areas;
        }else{
            return '---';
        }
    }

    /**
     * 获取某价格下的地区,地区价格设置
     */
    public static function getAreaPrice($id){
//        $priceList = SystemZoneList::findOne($id);
//        if($type==1){
            $area = self::find()->where(['price_id'=>$id])->select('area_id')->asArray()->all();
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

    /**
     * 根据id获得price
     */
    public static function getPriceById($id){
        $areaid = substr($id,0,9);
        $area = self::findOne(['area_id'=>$areaid]);
        if($area){
//            if($type == 1){
                $pid = $area->getAttribute('price_id');
//            }else{
//                $pid = $area->getAttribute('subsidy_price');
//            }
//            return $pid;
            $model = SystemZoneList::findOne(['id'=>$pid]);
            return $model == true ? $model->getAttribute('price') : 0;
        }else{
            return 0;
        }
    }

    /*
     * 批量删除地区价格
     */
    public static function modifyAreaPrice($arr){
        return self::updateAll(['price_id'=>0],['area_id'=>$arr['drr']]);
    }
}
