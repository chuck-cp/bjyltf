<?php

namespace cms\modules\schedules\models;

use cms\models\SystemAddress;
use cms\modules\config\models\SystemConfig;
use cms\modules\schedules\models\SystemAdvertArea;
use common\libs\ToolsClass;
use Yii;
use yii\db\Expression;
use app;

/**
 * This is the model class for table "yl_system_advert".
 *
 * @property string $id
 * @property string $advert_name 广告名称
 * @property string $advert_position_key 广告位标识
 * @property int $advert_time 广告时长(分钟)
 * @property string $shop_name 店铺名称
 * @property string $image_url 图片地址
 * @property string $link_url 链接地址
 * @property int $throw_rate 投放频次
 * @property int $throw_status 投放状态(0、未推送 1、已推送 2、投放完成)
 * @property string $start_at 开始日期
 * @property string $end_at 结束日期
 * @property string $create_at 创建时间
 */
class SystemAdvert extends \yii\db\ActiveRecord
{
    public $create_at_end;
    public $advert_position_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_system_advert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['advert_name', 'advert_position_key', 'image_url', 'start_at', 'end_at'], 'required'],
            [['id', 'throw_rate', 'throw_status'], 'integer'],
            [['start_at', 'end_at', 'create_at','create_at_end','over_at'], 'safe'],
            [['advert_name'], 'string', 'max' => 30],
            [['advert_position_key'], 'string', 'max' => 5],
            [['shop_name'], 'string', 'max' => 100],
            [['image_url', 'link_url'], 'string', 'max' => 200],
            [['id'], 'unique'],
            [['link_url'], 'url'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'advert_name' => '广告名称',
            'advert_position_key' => '广告位',
            'advert_time' => '投放时长',
            'shop_name' => '店铺名称',
            'image_url' => '素材',
            'link_url' => '链接地址',
            'throw_rate' => 'Throw Rate',
            'throw_status' => 'Throw Status',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'create_at' => 'Create At',
            'over_at' => 'Over At',
        ];
    }

    /**
     * @param $data
     * 添加A屏广告
     */
    public static function Add($data,$model){

        $advert_advance_upload_time_set=SystemConfig::find()->where(['id'=>'advert_advance_upload_time_set'])->asArray()->one()['content'];
        if($advert_advance_upload_time_set==2){
            $advert_advance_upload_time=SystemConfig::find()->where(['id'=>'advert_advance_upload_time'])->asArray()->one()['content'];
            $date=date('Y-m-d',strtotime('+'.$advert_advance_upload_time.'day'));
            if($data['SystemAdvert']['start_at']<$date){
                return json_encode(['code'=>3,'msg'=>'未达到提前天数无法保存']);
            }
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $dateArr=self::getDateRange($data['SystemAdvert']['start_at'],$data['SystemAdvert']['end_at']);
            foreach ($dateArr as $v){
                $detailModelOne=SystemAdvertDetail::findOne(['advert_position_key'=>$data['SystemAdvert']['advert_position_key'],'create_at'=>$v]);
                if($detailModelOne){
                    /*if($detailModelOne->rate+$data['SystemAdvert']['throw_rate']>9){
                        $msg[]=$v.'号批次已满，无法保存';
                        continue;
                    }*/
                    $detailModelOne->advert_position_key=$data['SystemAdvert']['advert_position_key'];
                    $detailModelOne->create_at=$v;
                    $detailModelOne->rate+=$data['SystemAdvert']['throw_rate'];
                    $detailModelOne->save();
                }else{
                    $detailModel=new SystemAdvertDetail();
                    $detailModel->advert_position_key=$data['SystemAdvert']['advert_position_key'];
                    $detailModel->create_at=$v;
                    $detailModel->rate=$data['SystemAdvert']['throw_rate'];
                    $detailModel->save();
                }
            }
            if(!empty($msg)){
                return json_encode(['code'=>2,'msg'=>implode(',',$msg)]);
            }
            $model->advert_position_key=$data['SystemAdvert']['advert_position_key'];
            $model->advert_name=$data['SystemAdvert']['advert_name'];
            $model->shop_name=$data['SystemAdvert']['shop_name'];
            $model->link_url=trim($data['SystemAdvert']['link_url']);
            $model->over_at=$data['SystemAdvert']['over_at'];
            $model->start_at=$data['SystemAdvert']['start_at'];
            $model->end_at=$data['SystemAdvert']['end_at'];
            $model->advert_time=$data['SystemAdvert']['advert_time'];
            $model->throw_rate=$data['SystemAdvert']['throw_rate'];
            $model->image_url=$data['SystemAdvert']['image_url'];
            $model->create_at=date('Y-m-d H:i:s');
            if($data['size']){
                $model->image_size=$data['size'];
            }
            if($data['sha1']){
                $model->image_sha=$data['sha1'];
            }
            $model->save();
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }

    }

    /**
     * 修改广告
     */
    public static function Edit($data,$model){
        if($model->throw_status==2){
            if(!$data['SystemAdvert']['start_at'] || !$data['SystemAdvert']['end_at']){
                return json_encode(['code'=>3,'msg'=>'广告信息填写不完整']);
            }
        }
        $advert_advance_upload_time_set=SystemConfig::find()->where(['id'=>'advert_advance_upload_time_set'])->asArray()->one()['content'];
        if($advert_advance_upload_time_set==2){
            $advert_advance_upload_time=SystemConfig::find()->where(['id'=>'advert_advance_upload_time'])->asArray()->one()['content'];
            $date=date('Y-m-d',strtotime('+'.$advert_advance_upload_time.'day'));
            if($data['SystemAdvert']['start_at']<$date){
                return json_encode(['code'=>4,'msg'=>'未达到提前天数无法保存']);
            }
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //先删除
            $dateArrOld = self::getDateRange($model->start_at,$model->end_at);
            foreach($dateArrOld as $ko=>$vo){
                $detailModelOne=SystemAdvertDetail::findOne(['advert_position_key'=>$data['SystemAdvert']['advert_position_key'],'create_at'=>$vo]);
                $detailModelOne->rate-=$model->throw_rate;
                $detailModelOne->save();
            }
            //再增加
            $dateArr=self::getDateRange($data['SystemAdvert']['start_at'],$data['SystemAdvert']['end_at']);
            foreach ($dateArr as $v){
                $detailModelOne=SystemAdvertDetail::findOne(['advert_position_key'=>$data['SystemAdvert']['advert_position_key'],'create_at'=>$v]);
                if($detailModelOne){
                    /*if($detailModelOne->rate+$data['SystemAdvert']['throw_rate']>9){
                        $msg[]=$v.'号批次已满，无法保存';
                        continue;
                    }*/
                    $detailModelOne->advert_position_key=$data['SystemAdvert']['advert_position_key'];
                    $detailModelOne->create_at=$v;
                    $detailModelOne->rate+=$data['SystemAdvert']['throw_rate'];
                    $detailModelOne->save();
                }else{
                    $detailModel=new SystemAdvertDetail();
                    $detailModel->advert_position_key=$data['SystemAdvert']['advert_position_key'];
                    $detailModel->create_at=$v;
                    $detailModel->rate=$data['SystemAdvert']['throw_rate'];
                    $detailModel->save();
                }
            }
            if(!empty($msg)){
                return json_encode(['code'=>2,'msg'=>implode(',',$msg)]);
            }
            $model->throw_status = 0;
            $model->advert_position_key=$data['SystemAdvert']['advert_position_key'];
            $model->advert_name=$data['SystemAdvert']['advert_name'];
            $model->shop_name=$data['SystemAdvert']['shop_name'];
            $model->link_url=trim($data['SystemAdvert']['link_url']);
            $model->over_at=$data['SystemAdvert']['over_at'];
            $model->start_at=$data['SystemAdvert']['start_at'];
            $model->end_at=$data['SystemAdvert']['end_at'];
            $model->advert_time=$data['SystemAdvert']['advert_time'];
            $model->throw_rate=$data['SystemAdvert']['throw_rate'];
            $model->image_url=$data['SystemAdvert']['image_url'];
            if($data['size']){
                $model->image_size=$data['size'];
            }
            if($data['sha1']){
                $model->image_sha=$data['sha1'];
            }
            $model->save(false);
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }


    /**
     * @param $start
     * @param $end
     * @return array
     * 添加C屏广告
     */
    public static function Add_c($data,$model){
        $advert_advance_upload_time_set=SystemConfig::find()->where(['id'=>'advert_advance_upload_time_set'])->asArray()->one()['content'];
        if(isset($data['city_name'])){
            if(empty(array_filter($data['city_name']))){
                return json_encode(['code'=>4,'msg'=>'至少选择一个市']);
            }
        }else{
            return json_encode(['code'=>4,'msg'=>'至少选择一个市']);
        }
        if($advert_advance_upload_time_set==2){
            $advert_advance_upload_time=SystemConfig::find()->where(['id'=>'advert_advance_upload_time'])->asArray()->one()['content'];
            $date=date('Y-m-d',strtotime('+'.$advert_advance_upload_time.'day'));
            if($data['SystemAdvert']['start_at']<$date){
                return json_encode(['code'=>3,'msg'=>'未达到提前天数无法保存']);
            }
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->advert_position_key=$data['SystemAdvert']['advert_position_key'];
            $model->advert_name=$data['SystemAdvert']['advert_name'];
            $model->start_at=$data['SystemAdvert']['start_at'];
            $model->end_at=$data['SystemAdvert']['end_at'];
            $model->advert_time=$data['SystemAdvert']['advert_time'];
            $model->throw_rate=$data['SystemAdvert']['throw_rate'];
            $model->image_url=$data['SystemAdvert']['image_url'];
            $model->advert_type=2;
            $model->create_at=date('Y-m-d H:i:s');
            if($data['SystemAdvert']['advert_position_key']=='A1' ||$data['SystemAdvert']['advert_position_key']=='A2'){
                if($data['size_video']){
                    $model->image_size=$data['size_video'];
                }
                if($data['sha1_video']){
                    $model->image_sha=$data['sha1_video'];
                }
                if($data['video_url']){
                    $model->image_url=$data['video_url'];
                }
            }else{
                if($data['size']){
                    $model->image_size=$data['size'];
                }
                if($data['sha1']){
                    $model->image_sha=$data['sha1'];
                }
                $model->image_url=$data['SystemAdvert']['image_url'];
            }

            $model->save(false);
           /* foreach ($data['province_name'] as $value){
                $adrsModel = new SystemAddress();
                $arr2=$adrsModel::getAreasByPid($value);
                foreach($arr2 as $k=>$v){
                    $ids[]=$k;
                }
            }
            //判断时候选择了市
            if(isset($data['city_name'])){
                //对所选地区进行合并去重
                $ids_arr=array_merge($ids,);
            }else{
                $ids_arr=$ids;
            }*/
            $SystemAdvertAreaModel=new SystemAdvertArea();
            foreach(array_filter($data['city_name']) as $v_id){
                $_SystemAdvertAreaModel=clone $SystemAdvertAreaModel;
                $_SystemAdvertAreaModel->advert_id=$model->id;
                $_SystemAdvertAreaModel->area_id=$v_id;
                $_SystemAdvertAreaModel->save();
            }
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }

    }


    /**
     * @param $start
     * @param $end
     * @return array
     * 修改C屏广告
     */
    public static function Edit_c($data,$model){
        $advert_advance_upload_time_set=SystemConfig::find()->where(['id'=>'advert_advance_upload_time_set'])->asArray()->one()['content'];
        if($advert_advance_upload_time_set==2){
            $advert_advance_upload_time=SystemConfig::find()->where(['id'=>'advert_advance_upload_time'])->asArray()->one()['content'];
            $date=date('Y-m-d',strtotime('+'.$advert_advance_upload_time.'day'));
            if($data['SystemAdvert']['start_at']<$date){
                return json_encode(['code'=>3,'msg'=>'未达到提前天数无法保存']);
            }
        }
        if(isset($data['city_name'])){
            if(empty(array_filter($data['city_name']))){
                return json_encode(['code'=>4,'msg'=>'至少选择一个市']);
            }
        }else{
            return json_encode(['code'=>4,'msg'=>'至少选择一个市']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->throw_status = 0;
            $model->advert_position_key=$data['SystemAdvert']['advert_position_key'];
            $model->advert_name=$data['SystemAdvert']['advert_name'];
            $model->start_at=$data['SystemAdvert']['start_at'];
            $model->end_at=$data['SystemAdvert']['end_at'];
            $model->advert_time=$data['SystemAdvert']['advert_time'];
            $model->throw_rate=$data['SystemAdvert']['throw_rate'];
            $model->advert_type=2;
            if($data['SystemAdvert']['advert_position_key']=='A1' ||$data['SystemAdvert']['advert_position_key']=='A2'){
                if($data['size_video']){
                    $model->image_size=$data['size_video'];
                }
                if($data['sha1_video']){
                    $model->image_sha=$data['sha1_video'];
                }
                if($data['video_url']){
                    $model->image_url=$data['video_url'];
                }
            }else{
                if($data['size']){
                    $model->image_size=$data['size'];
                }
                if($data['sha1']){
                    $model->image_sha=$data['sha1'];
                }
                $model->image_url=$data['SystemAdvert']['image_url'];
            }
            $model->save(false);
            /* foreach ($data['province_name'] as $value){
                 $adrsModel = new SystemAddress();
                 $arr2=$adrsModel::getAreasByPid($value);
                 foreach($arr2 as $k=>$v){
                     $ids[]=$k;
                 }
             }
             //判断时候选择了市
             if(isset($data['city_name'])){
                 //对所选地区进行合并去重
                 $ids_arr=array_merge($ids,);
             }else{
                 $ids_arr=$ids;
             }*/
            if(isset($data['city_name'])){
                SystemAdvertArea::deleteAll(['advert_id'=>$model->id]);
                $SystemAdvertAreaModel=new SystemAdvertArea();
                foreach(array_filter($data['city_name']) as $v_id){
                    $_SystemAdvertAreaModel=clone $SystemAdvertAreaModel;
                    $_SystemAdvertAreaModel->advert_id=$model->id;
                    $_SystemAdvertAreaModel->area_id=$v_id;
                    $_SystemAdvertAreaModel->save();
                }
            }
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }




    public static function getDateRange($start, $end) {
        $range = [];
        for ($i = 0; strtotime($start . '+' . $i . ' days') <= strtotime($end); $i++) {
            $time = strtotime($start . '+' . $i . ' days');
            $range[] = date('Y-m-d', $time);
        }
        return $range;
    }
    /*
     * 删除广告
     * */
    public function deleteAdvert() {
        $dateList = self::getDateRange($this->start_at,$this->end_at);
        if (empty($dateList)) {
            return false;
        }
        $dbTrans = Yii::$app->db->beginTransaction();
        try {
            foreach ($dateList as $date) {
                SystemAdvertDetail::updateAll(['rate' => new Expression("rate - {$this->throw_rate}")],['create_at' => $date]);
            }
            $this->delete();
            $dbTrans->commit();
            return true;
        } catch (\Exception $e) {
            $dbTrans->rollBack();
            Yii::error($e->getMessage());
            return false;
        }
    }
    //C屏投放频次
    public static function ThrowRateS(){
        return ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30'];
    }

    //获取已投放地区
    public static function PutinArea($AreaAll){
        foreach($AreaAll as $v){
            $province=substr($v,0,5);

            $areaAll[$province][]=$v;
        }
        $areas = [];
        foreach ($areaAll as $k => $v){
            foreach ($v as $kt => $vt) {
                $areaName = SystemAddress::getAreaByIdLen($vt, 7);
                if(!empty($areaName)){
                    $areas[$k][$kt] = $areaName;
                }else{
                    $systemarea = SystemAddress::find()->where(['id'=>$vt])->asArray()->one();
                    $areas[$k][$kt] = $systemarea['name'];
                }
            }
        }
        return $areas;
    }

    //详情查看投放区域
    public static function areaView($advert_id){
        $areaData=SystemAdvertArea::find()->where(['advert_id'=>$advert_id])->asArray()->all();
        if(!empty($areaData)){
            foreach ($areaData as $v){
                $areaArr[]=SystemAddress::find()->where(['id'=>$v['area_id']])->asArray()->one()['name'];
            }
            return $areaArr;
        }
        return [];
    }

    public static function getAdvertPositionKey($key){
        $keyArr = [
            'A1'=>'A屏内容广告',
            'B'=>'B屏图片广告',
            'A2'=>'A屏视频广告',
            'C'=>'C屏图片广告',
            'D'=>'D屏图片广告',
        ];
        return $keyArr[$key];
    }
}
