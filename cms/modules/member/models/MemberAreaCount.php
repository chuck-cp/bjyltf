<?php

namespace cms\modules\member\models;

use cms\modules\config\models\SystemConfig;
use common\libs\RedisClass;
use Yii;

/**
 * 用户地区统计
 */
class MemberAreaCount extends \yii\db\ActiveRecord
{
    public $parent_id;
    public $type;
    public static function tableName()
    {
        return '{{%member_area_count}}';
    }

    /*
     * 获取我的地区
     * */
    public function getMemberAreaList(){
        $areaModel = self::find()->where(['member_id'=>Yii::$app->user->id])->select('area,shop_number,screen_number')->asArray()->all();
        if(empty($areaModel)){
            return [];
        }
        foreach($areaModel as $key=>$area){
            $areaModel[$key]['name'] = SystemAddress::getAreaNameById($area['area']);
        }
        return $areaModel;
    }

    /*
     * 根据上级ID获取我的地区
     * */
    public function getMemberAreaByParentId(){
        $member_id = Yii::$app->user->id;
        if($this->type == 1){
            $dateCountList = RedisClass::smembers("member_area_lower:{$member_id}");
        }else{
            $dateCountList = RedisClass::smembers("member_area:{$member_id}");
        }
        if(empty($dateCountList)){
            return [];
        }
        $dateCountList = $this->reformArea($dateCountList,$this->parent_id);
        if(empty($dateCountList)){
            return [];
        }
        foreach($dateCountList as $area){
            $resultArea[] = [
                'id'=>$area,
                'name'=>SystemAddress::getAreaNameById($area,'ONE')
            ];
        }

        return $resultArea;
    }
    /*
     * 重组地区数据
     * */
    public function reformArea($areaArray,$parent_id){
        if(empty($parent_id)){
            $step = 5;
            foreach($areaArray as $key=>$area){
                $reformArea[substr($area,0,$step)] = $key;
            }
        }else{
            $parent_id_len = strlen($parent_id);
            if($parent_id_len >= 9){
                $step = strlen($parent_id) + 3;
            }else{
                $step = strlen($parent_id) + 2;
            }
            foreach($areaArray as $key=>$area){
                if($area == $parent_id){
                    continue;
                }
                if(substr($area,0,$parent_id_len) == $parent_id){
                    $reformArea[substr($area,0,$step)] = $key;
                }
            }
        }
        if(empty($reformArea)){
            return [];
        }
        return array_flip($reformArea);
    }

    /*
     * 获取或创建一条日期记录
     * */
    public static function createAreaCount($member_id,$area,$screen_number){
        if(empty($member_id)){
            return true;
        }
        if($area == 0){
            return true;
        }
        $area = substr($area,0,9);
        if($areaModel = MemberAreaCount::findOne(['member_id'=>$member_id,'area'=>$area])){
            $areaModel->shop_number += 1;//店铺数
            $areaModel->screen_number +=$screen_number;//屏幕数
            return $areaModel->save();
        }
        $areaModel = new MemberAreaCount();
        $areaModel->member_id = $member_id;
        $areaModel->area = $area;//地区
        $areaModel->shop_number = 1;//店铺数
        $areaModel->screen_number =$screen_number;//屏幕数
        return $areaModel->save();
    }
    /*
     * 场景
     * */
    public function scenes(){
        return [
            'area'=>[
                'parent_id'=>[
                    'type'=>'int'
                ],
                'type'=>[
                    'type'=>'int'
                ],
            ],
        ];
    }
}
