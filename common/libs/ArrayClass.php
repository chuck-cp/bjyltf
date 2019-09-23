<?php
/**
 *系统数组操作类
 */

namespace common\libs;


use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ArrayClass extends ArrayHelper {
    //拆分字符串
    public static function explodeString($eString,$string){
        if(empty($string)){
            return null;
        }else{
            return explode($eString,$string);
        }
    }
    //判断兼职是否存在
    public static function issetArray($array,$key,$result=0){
        return isset($array[$key]) ? $array[$key] : $result;
    }
    //遍历$unsetArray根据val删除$exportData中存在的字段
    public static function unsetArray($exportData,$unsetArray){
        if(empty($exportData) || empty($unsetArray)){
            return $exportData;
        }
        foreach($unsetArray as $k=>$v){
            foreach($exportData as $eK=>$eV){
                unset($exportData[$eK][$v]);
            }
        }
        return $exportData;
    }
    //把数组的键值由字段名称转换成数值
    public static function array_key_filed_convert_number($array){
        if(empty($array) || !is_array($array)){
            return [];
        }
        $resultArr = [];
        foreach($array as $key=>$arr){
            if(!is_array($arr)){
                continue;
            }
            foreach($arr as $ar){
                $resultArr[$key][] = $ar;
            }
        }
        return $resultArr;
    }
    public static function array_sort_by_key($array,$key,$order='asc'){
        if(empty($array)) return false;
        $k=count($array);
        if($order == 'asc'){
            for($i=0;$i<$k;$i++){
                for($j=$i+1;$j<$k;$j++){
                    if($array[$i][$key] > $array[$j][$key]){
                        $temp = $array[$i];
                        $array[$i] = $array[$j];
                        $array[$j] = $temp;
                    }
                }
            }
        }else{
            for($i=0;$i<$k;$i++){
                for($j=$i+1;$j<$k;$j++){
                    if($array[$i][$key] < $array[$j][$key]){
                        $temp = $array[$i];
                        $array[$i] = $array[$j];
                        $array[$j] = $temp;
                    }
                }
            }
        }

        return $array;
    }
    public static function truncate_utf8_string($string, $length, $etc = '...',$isA=0)
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        if($isA == 1){
            $result = Html::a($result,'#',['title'=>$string]);
        }
        return $result;
    }
    public static function leftString($string,$l,$isLink=0){
        if(intval($l) <= 0){
            return false;
        }
        if(strlen($string) < $l){
            return $string;
        }
        $mt_string = mb_substr($string,0,$l,'utf-8').'…';
        return $isLink == 1 ? Html::a($mt_string,'#',['title'=>$string]) : $mt_string;
    }
    /*
     * 判断包含
     * @s1 string 字符串
     * @s2 array 数组
     * @type int 1是数组包含 2是字符串包含
     * @result 返回的结果
     * */
    public static function inArray($s1,$s2,$type=1,$result="checked"){
        if(empty($s1) || empty($s2)){
            return false;
        }
        if($type == 1){
            return in_array($s2,$s1) ? $result : false;
        }else{
            return strstr($s1,$s2) ? $result : false;
        }
    }
} 