<?php
/**
 *系统数组操作类
 */

namespace common\libs;


use yii\helpers\ArrayHelper;
use yii\helpers\Html;

//正则类
class RegularClass {

    //验证手机号
    public static function matchMobile($string){
        $regex = '/^(1)\\d{10}$/';
        return self::match($string,$regex);
    }
    //验证
    public static function match($string,$regex){
        $result = preg_match($regex,$string);
        return $result;
    }
} 