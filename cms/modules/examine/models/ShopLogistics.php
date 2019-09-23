<?php

namespace cms\modules\examine\models;

use cms\modules\ledmanage\models\SystemDevice;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use Yii;

/**
 * This is the model class for table "{{%shop_logistics}}".
 *
 * @property int $id
 * @property int $shop_id 店铺ID
 * @property string $name 物流名称
 * @property int $logistics_id 物流编号
 * @property string $creact_at 添加时间
 */
class ShopLogistics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_logistics}}';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'name' => 'Name',
            'logistics_id' => 'Logistics ID',
            'creact_at' => 'Creact At',
        ];
    }

    //获取屏幕发货信息
    public static function getwuliuInfo($shopid)
    {
        $post = self::find()->where(['shop_id'=>$shopid])->select('name,logistics_id')->asArray()->all();
        return $post;
    }

    //添加安装信息
    public static function addLogistics($info)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $wlmodel = new ShopLogistics();
            //保存物流信息
            $wlmodel->shop_id=$info['shopid'];
            $wlmodel->name=$info['ShopLogistics']['name'];
            $wlmodel->logistics_id=$info['ShopLogistics']['logistics_id'];
            $wlmodel->save();
            //更新店铺状态
            if(empty($info['types'])) {
                Shop::updateAll(['delivery_status' => 3], ['id' => $info['shopid']]);
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return false;
        }
    }
    /**
     * 获取物流信息
     */
    public static function getLogist($shop_id){
        $model = self::find()->where(['shop_id'=>$shop_id]);
        if($model){
            $arr = $model->select('name,logistics_id')->asArray()->one();
            if(empty($arr) || !in_array($arr['name'],['shunfeng','shentong','yuantong','yunda','youzhengguonei','zhongtong'])){
                $arr['name']='---';
                $arr['logistics_id']='---';
            }else{
                $arr['name'] = self::getLogistList('all')[$arr['name']];
            }
            return $arr;
        }else{
            return false;
        }
    }
    /*
     * 物流列表
     */
    public static function getLogistList($type = 'all', $index = 0){
        $lrr = [
            'shunfeng' => '顺丰物流',
            'shentong' => '申通物流',
            'yuantong' => '圆通物流',
            'yunda' => '韵达物流',
            'youzhengguonei' => '邮政小包',
            'zhongtong' => '中通物流',
        ];
        if ($type == 'all') {
            return $lrr;
        }
        return array_key_exists($index, $lrr) ? $lrr[$index] : '---';
    }

    //屏幕配货
    public static function screenRemove($array){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //保存屏幕信息
            $screennum = Screen::find()->where(['shop_id'=>$array['shopid']])->count();
            foreach($array['Screen']['number'] as $key=>$value){
                $screenSwId = SystemDevice::findOne(['device_number'=>trim($value)]);
                $scmodel = new Screen();
                $scname = '屏幕'.($screennum+$key+1);
                $scmodel->shop_id=$array['shopid'];
                $scmodel->number=trim($value);
                $scmodel->software_number=$screenSwId->software_id;
                $scmodel->name=(string)$scname;
                $scmodel->save();
            }
            //更新店铺状态
            if(empty($array['types'])){
                Shop::updateAll(['delivery_status'=>2],['id'=>$array['shopid']]);
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return false;
        }
    }
}
