<?php

namespace console\controllers;

use console\models\Order;
use console\models\OrderDate;
use console\models\OrderArea;
use console\models\OrderPlayPresentation;
use console\models\OrderPlayPresentationList;
use console\models\OrderPlayView;
use console\models\OrderPlayViewArea;
use console\models\OrderThrow;
use console\models\OrderThrowProgramCount;
use console\models\OrderThrowProgramScreenCount;
use console\models\Screen;
use console\models\Shop;
use yii\console\Controller;
use Yii;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class PlayController extends Controller
{


    public  function actionPlayorder(){
        $orderdata=Order::find()->where(['examine_status'=>5])->select('id')->asArray()->all();
        foreach($orderdata as $k=>$v){
          if($this->actionPlay($v['id'])){

              Yii::error("[play_order]" . date('Y-m-d H:i:s')."监播报告生成失败".$v['id']);
          }else{
              Yii::error("[play_order]" . date('Y-m-d H:i:s')."监播报告生成失败".$v['id']);
          }
        }

    }



    /*
                 * 订单播放报告生成
                 *
                 * */
    public function  actionPlay($orderid)
    {
       // $orderid = 593;

        $orderprogram = new  OrderThrowProgramCount();
        $orderprogramdata = $orderprogram->findtrue($orderid);
        $order = Order::find()->where(['id' => $orderid])->select('id,order_code,salesman_name,custom_service_name,advert_name,rate,advert_time,area_name,total_day,create_at')->asArray()->one();
        $orderDate = OrderDate::find()->where(['order_id' => $orderid])->select('start_at,end_at')->asArray()->one();
        $orderArea = OrderArea::find()->where(['order_id' => $orderid])->select('street_area,street_screen_number,area_id')->asArray()->one();
        //理发店数量  屏幕数量  大型理发店  中型理发店  小型理发店 新增店铺数量 新增屏幕数量 新增店铺数量 新增传播人数
        $arrshoparea = explode(",", $orderArea['area_id']);
        $num = strlen($arrshoparea[0]);
        $shop = Shop::find()->where(['and',['status'=>5],['in', 'left(area,' . $num . ')', $arrshoparea] ])->select('id,screen_number,shop_type,area,install_finish_at')->asArray()->all();//购买区域下所有安装完成的店铺
        if (empty($shop)) {
            Yii::error("[" . date('Y-m-d H:i:s') . "][" . $orderid . "]订单地区下没有店铺");
            //continue;
        }
        $throw_shop_number = count($shop);//覆盖的店铺数量
        $throw_screen_number = 0;//屏幕数量
        $da_shop_num = 0;//大型店铺屏幕数量
        $zhong_shop_num = 0;//中型店铺屏幕数量
        $xiao_shop_num = 0;//小型店铺屏幕数量
        $shop_number=0;//新增店铺数量
        $screen_number=0;//新增屏幕数量
        $sowing_screen_num=0;//已播屏幕数
        $sowing_screen_num_area=array();//已播屏幕数
        foreach ($shop as $shopk => $shopv) {
            $throw_screen_number = $throw_screen_number + $shopv['screen_number'];
            if ($shopv['shop_type'] == 3) {
                $da_shop_num = $da_shop_num + 1;
            } elseif ($shopv['shop_type'] == 2) {
                $zhong_shop_num = $zhong_shop_num + 1;
            } else {
                $xiao_shop_num = $xiao_shop_num + 1;
            }
            //安装完成时间大于订单时间为新增店铺 和屏幕
            if(strtotime($shopv['install_finish_at'])>strtotime($order['create_at'])){
                $shop_number=$shop_number+1;
                $screen_number=$screen_number+$shopv['screen_number'];
            }else{//购买时店铺
                $screenData=Screen::find()->where(['shop_id'=>$shopv['id']])->select('id,software_number')->asArray()->all();
                foreach($screenData as $screenK=>$screenV){
                    $issowing=new OrderThrowProgramScreenCount();
                    $issowingData=$issowing->findtrue($orderid,$screenV['software_number']);
                    if($issowingData){
                        $sowing_screen_num=$sowing_screen_num+1;
                        $sowing_screen_num_area[$shopv['area']]=$sowing_screen_num_area[$shopv['area']]+1;
                    }
                    //存在 加1
                    //$orderid, 订单id+屏幕去查询
                    //$screenV['software_number']
                }
            }


        }
        $new_play_number=$screen_number*$order['rate'];//新增店铺数量
        $new_play_rate=$screen_number*24;//新增传播人数
        $large_shop_rate = round($da_shop_num / $throw_shop_number, 3) * 100;//大屏占比
        $medium_shop_rate = round($zhong_shop_num / $throw_shop_number, 3) * 100;//中屏占比
        $small_shop_rate =round( $xiao_shop_num / $throw_shop_number, 3) * 100;//小屏占比
        // 省 市 县 街道 投放地区
        $arrArea=explode(",",$orderArea['street_area']);
        $province=array();
        $city=array();
        $area=array();
        foreach($arrArea as $k=>$v){
            $provinceA=substr($v, 0, 5);
            if(!in_array($provinceA,$province)){
                $province[]=$provinceA;
            }
            $cityA=substr($v, 0, 7);
            if(!in_array($cityA,$city)){
                $city[]=$cityA;
            }
            $areaA=substr($v, 0, 9);
            if(!in_array($areaA,$area)){
                $area[]=$areaA;
            }
        }
        $throw_province_number=count($province);
        $throw_city_number=count($city);
        $throw_area_number=count($area);
        $throw_street_number=count($arrArea);
        //  总次数  总时长  总人数  日播放情况 日播放排行
        $viewArea=explode(",",$orderArea['area_id']);//投放地区
        $street_screen_number = explode(",", $orderArea['street_screen_number']);
        $orderjiedaoArea = explode(",", $orderArea['street_area']);
        $datecount = array();//存储每个街道实际播放量
        $areaplaycount=array();//存储每个购买区域实际播放量
        foreach ($orderjiedaoArea as $k => $v) {//地区循环
            $play_number_str = "";
            $play_number_count = "";
            for ($i = strtotime($orderDate['start_at']); $i <= strtotime($orderDate['end_at']); $i += 86400) {//日期循环
                //计算每个街道实际播放量
                $date = date("Y-m-d", $i);
                if (isset($orderprogramdata[$v][$date]))//判断该地区该日期是否有播放记录，有进行统计，无 即无排期
                {
                    $play_number_str = $play_number_str . $orderprogramdata[$v][$date] . ",";
                    $play_number_count = $play_number_count + $orderprogramdata[$v][$date];
                    if (!isset($datecount[$date])) {
                        $datecount[$date] = 0;
                    }
                    $datecount[$date] = $datecount[$date] + $orderprogramdata[$v][$date];
                } else {
                    $play_number_str = $play_number_str . "无排期" . ",";
                }
            }
            $should_total_area=$street_screen_number[$k] * $order['rate'] * $order['total_day'];//每个街道应播数量
            $data[$k]['area_id'] = $v;
            $data[$k]['data_list'] = rtrim($play_number_str, ',');
            $data[$k]['play_total'] = $play_number_count;
            $data[$k]['should_total'] =$should_total_area;
            //计算每个购买地区的实际播放数量
            foreach($viewArea as $viewAreak=>$viewAreav){
                $num=strlen($viewAreav);
                $area=substr($v, 0, $num);
                if($viewAreav==$area){
                    empty($areaplaycount[$viewAreav]['play_total'])?$areaplaycount[$viewAreav]['play_total']=0:"";
                    $areaplaycount[$viewAreav]['play_total']=$areaplaycount[$viewAreav]['play_total']+$play_number_count;
                    $areaplaycount[$viewAreav]['area_name']=$viewAreav;
                }
            }
        }
        //存储日、地区播放数据
        $should_total = 0;//总播放次数
        foreach ($data as $datak => $datav) {
            $orderplaylistModel = new OrderPlayPresentationList();
            $orderplaylistModel->order_id = $orderid;
            $orderplaylistModel->area_id = $datav['area_id'];
            $orderplaylistModel->data_list = $datav['data_list'];
            $orderplaylistModel->play_total = $datav['play_total'];;
            $orderplaylistModel->should_total = $datav['should_total'];
            $orderplaylistModel->percentage = round($datav['play_total'] / $datav['should_total'], 2) * 100;
          //  $orderplaylistModel->save();
            $should_total = $should_total + $datav['should_total'];
        }
        $play_total = 0;
        $data_list = "";
        //存储日、总播放数据
        foreach ($datecount as $datecountk => $datecountv) {
            $data_list = $data_list . $datecountv . ",";
            $play_total = $play_total + $datecountv;
        }
        $total_play_rate=round($play_total / $should_total, 3) * 100;//总播放比
        $orderplayModel = new OrderPlayPresentation();
        $orderplayModel->order_id = $orderid;
        $orderplayModel->data_list = rtrim($data_list, ',');
        $orderplayModel->play_total = $play_total;
        $orderplayModel->should_total = $should_total;
        $orderplayModel->percentage = $total_play_rate;
      //  $orderplayModel->save();
        //播放总时长
        //广告时长转换为秒
        $advert_time = $order['advert_time'];
        if (strpos($advert_time, '秒') !== false) {
            $advert_time = str_replace('秒', '', $advert_time);
        }
        if (strpos($advert_time, '分钟') !== false) {
            $advert_time = str_replace('分钟', '', $advert_time) * 60;
        }
        $total_play_time = $play_total * $advert_time;//播放总时长
        $total_watch_number = $throw_screen_number * 24;
        $start_at=$orderDate['start_at'];//投放开始时间
        $end_at=$orderDate['end_at'];//投放结束时间

        $orderplayview=new OrderPlayView();
        $orderplayview->order_id=$order['id'];
        $orderplayview->order_code=$order['order_code'];
        $orderplayview->salesman_name=$order['salesman_name'];
        $orderplayview->custom_service_name=$order['custom_service_name'];
        $orderplayview->advert_name=$order['advert_name'];
        $orderplayview->rate=$order['rate'];
        $orderplayview->advert_time=$order['advert_time'];
        $orderplayview->area_name=$order['area_name'];
        $orderplayview->throw_province_number=$throw_province_number;
        $orderplayview->throw_city_number=$throw_city_number;
        $orderplayview->throw_area_number=$throw_area_number;
        $orderplayview->throw_street_number=$throw_street_number;
        $orderplayview->throw_shop_number=$throw_shop_number;
        $orderplayview->throw_screen_number=$throw_screen_number;
        $orderplayview->total_play_number=$play_total;
        $orderplayview->total_play_time=$total_play_time;
        $orderplayview->total_play_rate=$total_play_rate;
        $orderplayview->total_watch_number=$total_watch_number;
        $orderplayview->large_shop_rate=$large_shop_rate;
        $orderplayview->medium_shop_rate=$medium_shop_rate;
        $orderplayview->small_shop_rate=$small_shop_rate;
        $orderplayview->start_at=$start_at;
        $orderplayview->end_at=$end_at;
        $orderplayview->shop_number=$shop_number;
        $orderplayview->screen_number=$screen_number;
        $orderplayview->new_play_number=$new_play_number;
        $orderplayview->new_play_rate=$new_play_rate;
        $orderplayview->save();
        foreach($areaplaycount as $areaplaycountk=>$areaplaycountv){
         //   echo $areaplaycountv['play_total']."\n";
            $orde_rplay_view_area=new OrderPlayViewArea();
            $orde_rplay_view_area->order_id=$order['id'];
            $orde_rplay_view_area->area_name=$areaplaycountv['area_name'];
            $orde_rplay_view_area->throw_number=$areaplaycountv['play_total'];
            $orde_rplay_view_area->throw_rate=round($areaplaycountv['play_total'] / $play_total, 3) * 100;
            $orde_rplay_view_area->save();
        }
    }

    public function  actionTest(){


    }

}
