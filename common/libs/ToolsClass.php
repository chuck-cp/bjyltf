<?php
/*
 * 小工具类
 * */
namespace common\libs;


use cms\config\system;
use cms\modules\authority\models\AuthAssignment;
use cms\modules\authority\models\AuthItemChild;
use cms\modules\authority\models\AuthRule;
use pms\models\account\Account;
use pms\models\account\AccountRecord;
use pms\models\cfg\CfgAddress;
use pms\models\cfg\CfgBasic;
use pms\models\cfg\CfgChannel;
use pms\models\cfg\CfgKeyword;
use pms\models\cfg\CfgMenu;
use pms\models\cfg\CfgPay;
use pms\models\cfg\CfgShopCategory;
use pms\models\cfg\CfgShopPrice;
use pms\models\content\CmsClassify;
use pms\models\content\CmsHelpClassify;
use pms\models\goods\Goods;
use pms\models\goods\GoodsBrand;
use pms\models\goods\GoodsClassify;
use pms\models\juhe\JuheArticle;
use pms\models\juhe\JuheBrand;
use pms\models\juhe\JuheClassify;
use pms\models\juhe\JuheGoods;
use pms\models\juhe\JuheShop;
use pms\models\log\LogAudit;
use pms\models\log\LogOprate;
use pms\models\market\MarketAdvertPosition;
use pms\models\member\MemberInviteCode;
use pms\models\member\MemberInviteUser;
use pms\models\member\MemberUsermessage;
use pms\models\rbac\ItemChild;
use pms\models\rbac\MembersNode;
use pms\models\rbac\MembersRole;
use pms\models\rbac\MembersRoleUser;
use pms\models\report\PriceAppealAccount;
use pms\models\serial\SerialNumbers;
use pms\models\shop\ShopBaseinfo;
use pms\models\shop\ShopBasicinfo;
use pms\models\shop\ShopBrand;
use pms\models\shop\ShopClassify;
use pms\models\shop\ShopCompany;
use pms\models\shop\ShopDomain;
use pms\models\shop\ShopUpdate;
use pms\models\sys\SysQueueEmail;
use pms\models\sys\SysQueueMessage;
use pms\models\user\Members;
use pms\models\user\User;
use pms\models\view\ViewGoods;
use pms\models\view\ViewOrderShop;
use pms\models\view\ViewShop;
use Yii;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\UploadedFile;
use ZipArchive;

class ToolsClass
{
    public static function reduceBigMapKey($advertKey,$advertTime,$rateNumber,$date){
        $advertRateList = [
            'a1_60' => 0,
            'a1_120' => 1,
            'a1_150' => 2,
            'a1_180' => 3,
            'a1_240' => 4,
            'a1_300' => 5,
            'a2_5' => 6,
            'a2_10' => 7,
            'a2_15' => 8,
            'a2_20' => 9,
            'a2_25' => 10,
            'a2_30' => 11,
            'a2_60' => 12,
            'b_30' => 13,
            'c_60' => 14,
            'd_60' => 15,
        ];
        if(!isset($advertRateList[$advertKey.'_'.$advertTime])){
            return false;
        }
        $system_start_at = "2019-01-01";
        $offset = self::timediffunit($system_start_at,$date);
        $bigMapKey = ($offset * 200) + ($advertRateList[$advertKey.'_'.$advertTime] * 10 + $rateNumber);
        return $bigMapKey;
    }

    /*
     * 分钟转秒
     * */
    public static function minuteCoverSecond($minute){
        return strstr($minute,"分钟") ? str_replace("分钟","",$minute) * 60 : str_replace("秒","",$minute);
    }
    /*
     * 输出图片
     * @image url 图片地址
     * @width int 图片大小
     * @format string 格式
     * */
    public static function echoImage($image,$width=''){
        $width = empty($width) ? '50px' : $width.'px';
        $imageHtml = "<p class='layerImage'><img src='{$image}' width='{$width}'/></p>";
        return $imageHtml;
    }

    /*
     * 输出删除按钮
     * */
    public static function deleteButton($url){
        return Html::a(\Yii::t('yii','delete'),$url,['data-method'=>'post','data-confirm'=>'确定要删除此信息吗？']);
    }

    //获取开启关闭状态
    public static function getCommonStatus($num){
        $status_array = DataClass::status_common();
        return isset($status_array[$num]) ? $status_array[$num] : '';
    }

    //将数组替换成对应文字描述
    public static function getStatusBayNum($num,$action,$style=0){
        $status_array = DataClass::$action();
        $result = '';
        if(strstr($num,',')){
            //多选,查分输出多个
            $num = explode(',',$num);
            foreach($num as $n){
                $result .= empty($result) ? $status_array[$n] : ','.$status_array[$n];
            }
        }else{
            //单选
            $result =  isset($status_array[$num]) ? $status_array[$num] : '';
        }
        if($style > 0 && $num == $style){
            $result = '<font color=red>'.$result.'</font>';
        }
        return $result;
    }
    public static function get_date_time($time='',$format='Y-m-d H:i:s',$type=0){
        $time = $type == 1 ? strtotime($time): $time;
        $time = empty($time) ? time() : $time;
        return date($format,$time);
    }
    public static function curl($url,$data='',$header=null,$post=true){
        if(empty($url)){
            return false;
        }
        if(is_array($url)){
            $mh = curl_multi_init();
            foreach($url as $cKey=>$cURL){
                //echo $cURL.'<br>';
                $ch[$cKey] = curl_init();
                curl_setopt($ch[$cKey],CURLOPT_URL,$cURL);
                curl_setopt($ch[$cKey],CURLOPT_HEADER, 0);
                curl_setopt($ch[$cKey],CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch[$cKey],CURLOPT_TIMEOUT,600);
                curl_setopt($ch[$cKey],CURLOPT_FOLLOWLOCATION,1);
                curl_multi_add_handle($mh,$ch[$cKey]);
            }
            $active = null;
            //防卡死写法：执行批处理句柄
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

            while ($active && $mrc == CURLM_OK) {
                if (curl_multi_select($mh) != -1) {
                    do {
                        $mrc = curl_multi_exec($mh, $active);
                        //echo 'mrc='.$mrc.'<br>';
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                }
            }
            foreach ($url as $i => $cUrl) {
                //获取当前解析的cURL的相关传输信息
                //$info = curl_multi_info_read($mh);
                //获取请求头信息
                //$heards = curl_getinfo($ch[$i]);
                //echo $heards['url'].'-'.$heards['http_code'].'<br>';
                //获取输出的文本流
                //$res[$i] = curl_multi_getcontent($ch[$i]);
                // 移除curl批处理句柄资源中的某个句柄资源
                curl_multi_remove_handle($mh, $ch[$i]);
                //关闭cURL会话
                curl_close($ch[$i]);
            }
            //关闭全部句柄
            curl_multi_close($mh);
            return true;
        }else{
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            if($post){
                curl_setopt($ch,CURLOPT_POST,false);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            }
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOBODY, 0);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            if($header){
                curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            }
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
    }
    //检查是否有权限(用于菜单检测)
    public static function checkMenuPermission($url,$PermissionKey){
        //return true;
        if(substr($url,0,1) != '/'){
            return false;
        }
        $url = explode('/',$url);
        $cUrl = $url[1];
        $cUrl .= isset($url[2]) ? '/'.$url[2] : '';
        if(is_numeric(array_search($cUrl,$PermissionKey))){
            if(count($url) > 3){
                $Permissions = DataSource::getPermissions();
                if(isset($Permissions[$cUrl]['data'])){
                    $pData = explode(',',$Permissions[$cUrl]['data']);

                    if(in_array($url[3],$pData)){
                        return true;
                    }
                }
            }
            return true;
        }
        return false;
    }
    //根据IP获取经纬度(百度)
    public static function getCoordinateByIpBaidu($ip){
        $content = file_get_contents("http://api.map.baidu.com/location/ip?ak=AC7e63f3f3e8566926ae42fd7778ce18&ip={$ip}&coor=bd09ll");
        $json = json_decode($content,true);
        if(isset($json['content'])){
            $coordinate = $json['content']['point'];
            $coordinate = doubleval($coordinate['x']).','.doubleval($coordinate['y']);
            return $coordinate;
        }
    }
    //根据经纬度获取地址(百度)
    public static function getLocationByBaidu($coordinate){
        //http://api.map.baidu.com/geocoder?location=39.960037,116.455624&output=json&key=37492c0ee6f924cb5e934fa08c6b1676
        //http://api.map.baidu.com/geocoder?location=39.960037,116.455624&output=json&key=37492c0ee6f924cb5e934fa08c6b1676
        //$url = 'http://api.map.baidu.com/geocoder?location='.$coordinate."&output=json&key=";
        $url = "http://api.map.baidu.com/geocoder/v2/?location={$coordinate}&output=json&pois=0&ak=AC7e63f3f3e8566926ae42fd7778ce18";
        $result_data = self::curl($url);
        $result_array = array('address'=>
            array('province'=>'',
                'city'=>'',
                'district'=>'',
                'street'=>'',
                'street_number'=>''
            ),
            'cityCode'=>''
        );
        if(empty($result_data)){
            return $result_array;
        }
        $result_data = json_decode($result_data,true);
        if(!empty($result_data['formatted_address'])){
            return $result_array;
        }
        $result_array = array('address'=>
            array('province'=>$result_data['result']['addressComponent']['province'],
                'country'=>'中国',
                'city'=>$result_data['result']['addressComponent']['city'],
                'district'=>$result_data['result']['addressComponent']['district'],
                'street'=>$result_data['result']['addressComponent']['street'],
                'street_number'=>$result_data['result']['addressComponent']['street_number']
            ),
            'cityCode'=>$result_data['result']['cityCode']
        );
        return $result_array;
    }
    //根据经纬度获取地址(谷歌)
    public static function getLocationByGoogle($coordinate){
        //http://maps.google.com/maps/geo?q=35.666925,139.758845&output=json&key=abcdefg&v=2
        $url = "http://maps.google.com/maps/geo?output=json&q={$coordinate}&key=abcdefg&v=2";
        $result_data['original'] = self::curl($url);
        $result_json = (array)json_decode($result_data['original'], true);
        if(count($result_json['Placemark']) > 0){
            $tmp = $result_json['Placemark'][0];
            try{
                $result_data['new']['country'] = isset($tmp['AddressDetails']['Country']['CountryName']) ? $tmp['AddressDetails']['Country']['CountryName'] : '';
                $result_data['new']['province'] = isset($tmp['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName']) ? $tmp['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'] : '';
                $result_data['new']['city'] = isset($tmp['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName']) ? $tmp['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'] : '';
                //$result_data['new']['district'] = isset($tmp['AddressDetails']['Country']['AdministrativeArea']['Locality']['DependentLocality']['DependentLocalityName']) ? $tmp['AddressDetails']['Country']['AdministrativeArea']['Locality']['DependentLocality']['DependentLocalityName'] : '';
                //$result_data['new']['street'] = isset($tmp['AddressDetails']['Country']['AdministrativeArea']['Locality']['DependentLocality']['Thoroughfare']['ThoroughfareName']) ? $tmp['AddressDetails']['Country']['AdministrativeArea']['Locality']['DependentLocality']['Thoroughfare']['ThoroughfareName'] : '';
            }catch(Exception $e){
                $result_data['new'] = [];
            }
        }
        return $result_data;
    }
    //七牛生成缩略图
    public static function getThumbnail($imgUrl,$media_type,$size=200){
        if($media_type == 2){
            $imgUrl = $imgUrl."?vframe/jpg/offset/0";
        }
        if(strstr($imgUrl,'?')){
            $imgUrl = $imgUrl."&imageView2/1/w/{$size}/h/{$size}";
        }else{
            $imgUrl = $imgUrl."?imageView2/1/w/{$size}/h/{$size}";
        }
        return $imgUrl;
    }
    public static function numberToConvert($number){
        if(!is_numeric($number)){
            return false;
        }
        $numberArr = [
            0=>'日',1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七',8=>'八',9=>'九'
        ];
        if(isset($numberArr[$number])){
            return $numberArr[$number];
        }
        return $number;
     }
    //判断媒体地址是否争取
    public static function completeMediaUrl($media_url){
        if(empty($media_url)){
            return $media_url;
        }
        if(substr($media_url,0,4) != 'http'){
            $media_url = Yii::$app->params['media_url'].$media_url;
        }
        return $media_url;
    }
    //时间转换
    public static function timeToConvert($time,$type=1){
        $time = (int)$time;
        if(empty($time)){
            return $time;
        }
        $nowTime = time();
        $year = date('Y',$time);
        $nowYear = date('Y',$nowTime);
        if($type == 2){
            if($year < $nowYear){
                return date('Y-m-d H:i',$time);
            }else{
                return date('m-d H:i',$time);
            }
        }
        if($year < $nowYear){
            return date('Y年m月d',$time);
        }
        $diffTime = $nowTime - $time;
        //一天的秒数
        $dayTime = 24 * 3600;
        //小于60秒
        if($diffTime < 60) {
            $resultTime = '片刻前';
        }elseif($diffTime < 3600){
            //小于60分钟
            $resultTime = ceil($diffTime / 60).'分钟前';
        }elseif($diffTime < $dayTime){
            //小于一天
            $resultTime = ceil($diffTime / 3600).'小时前';
        }else{
            //大于一周
            $resultTime = date('m月d',$time);
        }
        return $resultTime;
    }
    /*
     * 极光推送
     * @type int 内容类型
     * @registration_id array 推送的ID(可以是多个)
     * @param array 要替换内容的参数
     * */
    public static function jPush($type,$media_type,$registration_id,$param=[]){
        $media_type = ToolsClass::getStatusBayNum($media_type,'media_type');
        $jPushArray = [
            1=>'您的茄子好友[{username}]喜欢了您的照片',
            2=>'您的茄子好友[{username}]评论了您的照片',
            3=>'茄子用户[{username}]喜欢了您的照片',
            4=>'茄子用户[{username}]评论了您的照片',
            5=>'您的茄子好友[{username}]在九寨沟拍了新的照片',
            6=>'茄子用户[{username}]喜欢了您的照片，并成为了您的好友',
            7=>'茄子用户[{username}]评论了您的照片，并成为了您的好友',
            8=>'您的茄子好友[{username}]回复了您的评论',
            9=>'您的茄子好友[{username}]回复了您的评论，并成为了您的好友',
        ];
        if(!isset($jPushArray[$type])){
            \Yii::error("[ERROR:$registration_id]type is null $type",'jpush');
            return false;
        }
        $jPushContent = $jPushArray[$type];
        if(empty($registration_id)){
            \Yii::error("[ERROR:NULL]$jPushContent",'jpush');
            return false;
        }
        if(!empty($param)){
            foreach($param as $k=>$v){
                $jPushContent = str_replace("{{$k}}",$v,$jPushContent);
            }
        }
        $result = \Yii::$app->jpush->push()
            ->setPlatform('all')
            //->addAllAudience()
            ->addIosNotification($jPushContent,'default','+1')
            ->addAndroidNotification($jPushContent)
            ->addRegistrationId($registration_id)
            //->setNotificationAlert($jPushContent)
            ->send();
        if($result){
            \Yii::info("[SUCCESS:$registration_id]".$jPushContent,'jpush');
        }else{
            \Yii::error("[ERROR:$registration_id]".$jPushContent,'jpush');
        }
    }

    /**
     * @Function Name: 获取html中的图片
     * @Usage:
     * @param $html
     * @return mixed
     * @Created by ${PRODUCT_NAME}.
     * @Date: 2018-1-17
     * @Author: 张宏雷
     * @Ver:
     */
    public static function getHtmlImg($html){
        $str=preg_replace("/[\t\n\r\s]+/","",$html);
        preg_match_all('/<imgsrc="(.*?)"title/', $str, $s);
        return $s[1];
    }

    /*
     * 将货币面值由分转成元
     * @param price int 钱
     * @param price int(1、分转元 2、元转分)
     * */
    public static function priceConvert($price,$type=1){
        if($type == 2){
            return ceil($price * 100);
        }
        return number_format($price / 100,2);
    }
    /*
     * 获取当前微秒时间戳
     */
    public static function getMicroTime(){
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000).rand(10000,99999);
        //(str_pad($sec.round($msec*1000),13,"0",STR_PAD_RIGHT));
    }
    /**
     * 原样输出
     */
    public static function p($arr){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

    /*
     * 拆分字符串
     * */
    public static function explode($delimiter,$string){
        if(empty($string)){
            return [];
        }
        if(strstr($string,",")){
            return explode($delimiter,$string);
        }else{
            return [$string];
        }
    }

//功能：计算两个时间戳之间相差的日时分秒
//$begin_time 开始时间戳
//$end_time 结束时间戳
    public static function timediff($begin_time, $end_time, $type = false)
    {
        if($begin_time < $end_time){
            $starttime = $begin_time;
            $endtime = $end_time;
        }else{
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        //计算天数
        $timediff = $endtime-$starttime;
        $days = intval($timediff/86400);
        //计算小时数
        $remain = $timediff%86400;
        $hours = intval($remain/3600);
        //计算分钟数
        $remain = $remain%3600;
        $mins = intval($remain/60);
        //计算秒数
        $secs = $remain%60;
        if($type == 'day'){
            return $days;
        }
        $res = $days.' 天 '.$hours.' 小时 '.$mins.' 分钟 '.$secs.' 秒 ';
        return $res;
    }

    //功能：计算两个时间戳之间相差的日时分秒
        //$begin_time 开始时间戳
        //$end_time 结束时间戳
    public static function timediffunit($begin_time, $end_time)
    {
        if($begin_time < $end_time){
            $starttime = $begin_time;
            $endtime = $end_time;
        }else{
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        //计算天数
        $timediff = strtotime($endtime)-strtotime($starttime);
        $days = intval($timediff/86400);
        return $days;

    }

    //导入权限控制器路由
    public static function addrule(){
        $mune = system::systemMenu();
        foreach($mune as $key=>$value){
            $rulemodel = new AuthRule();
            $rulemodel->name = '/'.$key;
            $rulemodel->data = $value['title'];
            $rulemodel->level = 1;
            $rulemodel->created_at = date("Y-m-d H:i:s");
//            $rulemodel->save();
            foreach($value['child'] as $ks =>$vs){
                $rulemodel = new AuthRule();
                //2级
                $array = explode('/',$vs['href']);
                array_pop($array);
                $newname = implode('/',$array);
                $rulemodel->name = $newname;
                $rulemodel->data = $vs['title'];
                $rulemodel->level = 2;

                //3级
//                $rulemodel->name = $vs['href'];
//                $rulemodel->data = $vs['title'];
//                $rulemodel->level = 3;

                $rulemodel->created_at = date("Y-m-d H:i:s");
//                $rulemodel->save();
            }
        }
    }

    /*
     *发送手机短信验证
     */
//    public static function sendSms($mobile,$code='',$type,$shopnum=0,$shopprice=0){
//        $params['username'] =\Yii::$app->params['SMS_API_USER'];
//        $params['pwd'] = md5(\Yii::$app->params['SMS_API_PWD']);
//        $params['p'] = $mobile;
//        $params['charSetStr'] = "utf";
//        $time = date('Y-m-d',time());
//        if ($type == 1) {
//            $price = $shopnum*$shopprice;
//            $params['msg'] = "【玉龙传媒】{$price}元现金已到账！这是您已成功安装{$shopnum}家店铺的LED屏，并获得玉龙传媒业务合作人资格的奖励，请下载玉龙传媒APP，在“我的” →“我的区域”中选择管理区域，即刻领{$price}元现金红包，同时优享6大特权！退订回T！";
//        }else{
//            $params['msg'] = "【玉龙传媒】您的手机号码校验码是 ". $code . ",请于五分钟内输入，工作人员不会向您索取，请勿泄露!";
//        }
//        $postQs = http_build_query($params);
//        $callurl = \Yii::$app->params['SMS_API'];
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $callurl);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $postQs);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
//
//        $output = curl_exec($ch);
//        $array = json_decode($output, true);
//        curl_close($ch);
//        if ($array['status'] == '100') {
//            return "ok";
//        }else{
//            return $array['status'];
//        }
//    }
    /*
     *发送手机短信验证
     */
    public static function sendSMS($mobile,$code='',$type,$shopnum=0,$shopprice=0){
        //创蓝接口参数
        if ($type == 1) {
            $price = $shopnum*$shopprice;
            $msg = "【玉龙传媒】{$price}元现金已到账！这是您已成功安装{$shopnum}家店铺的LED屏，并获得玉龙传媒业务合作人资格的奖励，请下载玉龙传媒APP，在“我的” →“我的区域”中选择管理区域，即刻领{$price}元现金红包，同时优享6大特权！退订回T！";
        }elseif ($type == 2) {
            $msg = "推送节目单失败";
//        }else{
//            $msg = "【玉龙传媒】您的手机号码校验码是 ". $code . ",请于五分钟内输入，工作人员不会向您索取，请勿泄露!";
        }
        $postArr = array (
            'account'  => \Yii::$app->params['API_ACCOUNT'],
            'password' => \Yii::$app->params['API_PASSWORD'],
            'msg' => urlencode($msg),
            'phone' => $mobile,
            'report' => true,
        );
        $url =  \Yii::$app->params['API_SEND_URL'];
        $postFields = json_encode($postArr);

        $ch = curl_init ();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8'   //json版本需要填写  Content-Type: application/json;
            )
        );
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,60);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error(  $ch);
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
        curl_close ( $ch );
//        return $result;
    }

    /**
     * 到处CSV格式文件
     * @param $data
     * @param $title
     * @param $file_name
     */
    public static function Getcsv($data,$title,$file_name)
    {

        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename='.$file_name );
        header ( 'Cache-Control: max-age=0' );
        $file = fopen('php://output',"a");
        $limit=1000;
        $calc=0;
        foreach ($title as $v){
            $tit[]=iconv('UTF-8', 'GBK//IGNORE',$v);
        }
        fputcsv($file,$tit);
        foreach ($data as $v){
            $calc++;
            if($limit==$calc){
                ob_flush();
                flush();
                $calc=0;
            }
            foreach ($v as $t){
                $tarr[]=iconv('UTF-8', 'GBK//IGNORE',$t);
            }
            fputcsv($file,$tarr);
            unset($tarr);
        }
        unset($list);
        fclose($file);
        exit();
    }
    public static function Getcsvzip($data,$title,$fileName)
    {
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        // buffer计数器
        $cnt = 0;
        // 逐行取出数据，不浪费内存
        $fp = fopen('../runtime/'.$fileName, 'w'); //生成临时文件
/*        chmod($fileName,777);//修改可执行权限*/
        $fileName ='../runtime/'.$fileName;
        // 将数据通过fputcsv写到文件句柄
        foreach ($title as $v){
            $tit[]=iconv('UTF-8', 'GBK//IGNORE',$v);
        }
        fputcsv($fp,$tit);
        foreach ($data as $v){
            $cnt++;
            if($limit==$cnt){
                ob_flush();
                flush();
                $cnt=0;
            }
            foreach ($v as $t){
                $tarr[]=iconv('UTF-8', 'GBK//IGNORE',$t);
            }
            fputcsv($fp,$tarr);
            unset($tarr);
        }
        fclose($fp);  //每生成一个文件关闭
        return $fileName;
    }

    public static function zip($filenameArr,$filename){
        $zip = new ZipArchive();
        $zip->open('../runtime/'.$filename, ZipArchive::CREATE);   //打开压缩包
        foreach ($filenameArr as $file) {
            $zip->addFile($file, basename($file));   //向压缩包中添加文件
        }
        $zip->close();  //关闭压缩包
        foreach ($filenameArr as $file) {
            unlink($file); //删除csv临时文件
        }
        //输出压缩文件提供下载
        header("Cache-Control: max-age=0");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename('../runtime/'.$filename)); // 文件名
        header("Content-Type: application/zip"); // zip格式的
        header("Content-Transfer-Encoding: binary"); //
        header('Content-Length: ' . filesize('../runtime/'.$filename)); //
        @readfile('../runtime/'.$filename);//输出文件;
        unlink('../runtime/'.$filename); //删除压缩包临时文件
    }
    /*
     * 腾讯云上传地址转换
     * @url string 图片地址
     * @coverType int 转换类型(1、腾讯云转玉龙 2、玉龙转腾讯云)
     * */
    public static function replaceCosUrl($url,$coverType = 1){
        if (empty($url)) {
            return $url;
        }
        if ($coverType == 2) {
            $url = str_replace('https://i1.bjyltf.com','http://yulongchuanmei-1255626690.cossh.myqcloud.com',$url);
        } else {
            $url = str_replace('http://yulongchuanmei-1255626690.file.myqcloud.com','https://i1.bjyltf.com',$url);
            $url = str_replace('http://yulongchuanmei-1255626690.cossh.myqcloud.com','https://i1.bjyltf.com',$url);
        }
        return $url;
    }
    public static function str_rand($length) {
        $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if(!is_int($length) || $length < 0) {
            return false;
        }
        $string = '';
        for($i = $length; $i > 0; $i--){
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }
        return $string;
    }

    /*
     * 打印日志
     * */
    public static function printLog($title,$content,$type = 1){
        $date = date('Y-m-d H:i:s');
        echo "[{$title}][{$date}]{$content}\r\n";
    }

    /*
     * 验证客服的token
     * */
    public static function authCustomToken($string,$token){
        return md5($string.'aksk1laIM') == $token;
    }

    /*
     * 生成客服的加密token
     * */
    public static function makeCustomToken($string){
        return md5($string.'aksk1laIM');
    }

    //去字符串中空格
    public static function trimall($str){
        $qian=array(" "," ","\t","\n","\r","&nbsp;");
        $hou=array("","","","","","");
        return str_replace($qian,$hou,$str);
    }

    /*
     * 坐标转换
     * @param type int 转换类型(1、国标转百度 2、百度转国标)
     * @param longitude string 经度
     * @param latitude string 纬度
     * return ["x":"119.1234156(经度)","y":"36.4123465(纬度)"]
     * */
    public static function coordinateCover($type,$longitude,$latitude) {
        if ($type == 1) {
            $from = 3;
            $to = 5;
        } else {
            $from = 5;
            $to = 3;
        }
        $url = "http://api.map.baidu.com/geoconv/v1/?coords={$longitude},{$latitude}&from={$from}&to={$to}&ak=CRgp4Bf6ODIwFxlA4qjGrrrGApIFOYoY";
        $result = self::curl($url);
        $result = json_decode($result,true);
        if ($result['status'] == 0) {
            return $result['result'][0];
        }
        return [];
    }

    /*
     * 获取设备的坐标
     * @param string 设备的软件编码
     * */
    public static function getDeviceCoordinate($software_number) {
        $resultData = self::curl(Yii::$app->params['pushProgram']."/front/device/selectLocation/{$software_number}",'','',false);
        if (empty($resultData)) {
            return [];
        }
        $resultData = json_decode($resultData,true);
        $result = [];
        if (isset($resultData['code']) && $resultData['code'] == 0) {
            foreach ($resultData['data'] as $value) {
                $result[$value['deviceNum']] = $value['location'];
            }
        }
        return $result;
    }

    /*
     * 模拟curl登陆
     * @param url string url地址
     * @param method sting 请求方式
     * @param param array 请求挈带的参数
     * */
    public static function b2bcurl($url,$param=[],$method='POST',$encrypt=0){
        if($encrypt == 1){
            $param = \Yii::$app->des3->encode(json_encode($param));
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        if($method == 'PUT'){
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "put");
//        }elseif($method == 'POST'){
//            curl_setopt($ch, CURLOPT_POST, 1);
//        }
        if($param){
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_setopt($ch, CURLOPT_POST, $param);
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT,5);
        curl_setopt($ch, CURLOPT_HEADER,0);
        $resultCurl = curl_exec($ch);
        curl_close($ch);
        return $resultCurl;
    }

    //根据地址获取坐标
    public static function getLngLat($address){
        $addarray = ['bd_lng'=>0,'bd_lat'=>0,'lng'=>0,'lat'=>0];
        $newdata = urlencode($address);
        //百度通过地址获取坐标
        $url = "http://api.map.baidu.com/geocoder/v2/?address=".$newdata."&output=json&ak=uX28OgIzOvbBvfcCFMqxzORy6AGBvEHO";
        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        if($json_data->status === 0) {
            $lng = $json_data->result->location->lng;
            $lat = $json_data->result->location->lat;
            $addarray['bd_lng'] = $lng;
            $addarray['bd_lat'] = $lat;
        }
        //高德通过地址获取坐标
        $urlgd='http://restapi.amap.com/v3/geocode/geo?key=a8f38c01f3380ccb2595751466073a16&address='.$address.'&city=';
        $gddata=file_get_contents($urlgd);
        $address_gddata=json_decode($gddata,true);
        if($address_gddata['status']==1){
            $gdaddress = explode(',',$address_gddata['geocodes'][0]['location']);
            $addarray['lng'] = $gdaddress[0];
            $addarray['lat'] = $gdaddress[1];
        }
        return $addarray;
    }
}
