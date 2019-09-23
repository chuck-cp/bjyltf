<?php

namespace cms\modules\screen\models;

use cms\modules\account\models\OrderBrokerage;
use cms\modules\ledmanage\models\SystemDevice;
use cms\modules\member\models\MemberShopCount;
use cms\modules\member\models\Member;
use cms\modules\shop\models\Shop;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "{{%screen}}".
 *
 * @property string $id
 * @property string $shop_id
 * @property string $member_id
 * @property string $name
 * @property integer $status
 */
class Screen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%screen}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'name'], 'required'],
            [['shop_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['offline_time'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '屏幕编号',
            'shop_id' => '店铺ID',
            'number' => ' 屏幕硬件编号',
            'software_number' => ' 屏幕软件编码',
            'name' => '屏幕名称',
            'status' => '状态',
            'offline_time' => '离线时间',
        ];
    }
    /*
     * 屏幕状态值
     */
    public static function getScreenStatus($status){
        $crr = [
            '0' => '未激活',
            '1' => '正常',
            '2' => '离线',
            '3' => '待更换',
        ];
        if(array_key_exists($status,$crr)){
            return $crr[$status];
        }else{
            return '---';
        }
    }
    /**
     * 获取店铺的屏幕安装位置
     */
    public static function getScreePosition($shop_id){
        $model = self::find()->where(['shop_id'=>$shop_id]);
        if($model){
            return $screens = $model->asArray()->all();
        }else{
            return [];
        }
    }

    //获取店铺的屏幕信息
    public static function getScreenInfo($shopid)
    {
        $post = self::find()->where(['shop_id'=>$shopid])->select('number,software_number,image,status')->asArray()->all();
        if(empty($post)){
            $scrennnum = Shop::find()->where(['id'=>$shopid])->select('screen_number')->asArray()->one();
            if($scrennnum['screen_number'] == 0){
                $screeninfo[0] = [
                    'number'=>'',
                    'software_number'=>'',
                    'image'=>'',
                    'remark'=>'',
                    'status'=>'',
                    'statuslist'=>'',
                ];
            }else{
                for($i = 0;$i<$scrennnum['screen_number'];$i++){
                    $screeninfo[$i] = [
                        'number'=>'',
                        'software_number'=>'',
                        'image'=>'',
                        'remark'=>'',
                        'status'=>'',
                        'statuslist'=>'',
                    ];
                }
            }
        }else{
            foreach ($post as $key => $value) {
                $id = SystemDevice::find()->where(['device_number' => $value['number']])->select('remark')->asArray()->one();
                switch($value['status']){
                    case 0:
                        $post[$key]['statuslist'] = '未激活';
                        break;
                    case 1:
                        $post[$key]['statuslist'] = '正常';
                        break;
                    case 2:
                        $post[$key]['statuslist'] = '离线';
                        break;
                    case 3:
                        $post[$key]['statuslist'] = '待更换';
                        break;
                    default:
                        echo "No number between 1 and 3";
                }
                if (empty($id)) {
                    $id = ['remark' => ''];
                }
//                $address =self::getScreenstreet($value['number']);//获取屏幕地址
//                if($address->code == 0){
//                    foreach($address->data as $key => $value){
//                        $addresslist = OrderBrokerage::objtoarray($value->location);
//                    }
//                }
//                if(empty($addresslist)){
//                   $addresslist = ['address'=>'无法获取地址!'];
//                }
                $screeninfo[] = array_merge($post[$key], $id);
            }
        }
        return $screeninfo;
    }

    //获取店铺的屏幕信息
    public static function getScreenstreet($screenid)
    {
        $screenid = '4400503748183c27084f';
        $post_storage_url = 'http://123.207.145.129:8080/front/device/selectLocation/'.$screenid;
        $header[] = 'Authorization:';
        $header[] = 'Accept:application/json';
        $header[] = 'Content-Type:application/json;charset=utf-8';
        $curl = curl_init();  //初始化
        curl_setopt($curl,CURLOPT_URL,$post_storage_url);  //设置url
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $resultCurl = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($resultCurl);
        return $res;
    }


    //修改商家的管理者
    public static function upAdminShop($idarray){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $shoids = explode(',',$idarray['shopid']);
            Shop::updateAll(['admin_member_id'=>$idarray ['memberid']],['in','id',$shoids]);
            $screen_number = Shop::find()->where(['in','id',$shoids])->select('screen_number,area')->asArray()->all();
            $area = new MemberShopArea();
            $date = new MemberShopDate();
            foreach ($screen_number as $k=>$v){
                $areaModel = clone $area;
                $dateModel = clone $date;
                if($v['admin_member_id']!==0){
                    continue;
                }
                if(!MemberShopCount::updateOrCreate($idarray['memberid'],$v['screen_number'],1)){
                    throw new Exception("[error]管理店铺统计表写入失败");
                }
                $memberarea = $areaModel->find()->where(['member_id'=>$idarray['memberid'],'type'=>3])->count();
                if(empty($memberarea)){
                    $areaModel->member_id=$idarray['memberid'];
                    $areaModel->type=3;
                    $areaModel->area=$v['area'];
                    $areaModel->save();
                }
                $memberdate = $dateModel->find()->where(['member_id'=>$idarray['memberid'],'type'=>3])->count();
                if(empty($memberdate)){
                    $dateModel->member_id=$idarray['memberid'];
                    $dateModel->type=3;
                    $dateModel->date=date("Y-m");
                    $dateModel->save();
                }
            }
//            if(!RedisClass::sadd('member_admin_area:'.$idarray['memberid'],$screen_number['area'])){
//                throw new Exception("[error]写入redis地区失败");
//            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return false;
        }
    }

    //获取当前屏幕库存备注remark
    public static function getScreenRemark($num){
        $remark = SystemDevice::find()->where(['device_number'=>$num])->select('remark')->asArray()->one();
        return $remark['remark'];
    }



}
