<?php
/**
 *系统数组操作类
 */

namespace common\libs;


use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class StatusClass{
    #不加载分页和媒体
    const NO_LOAD_PAGE_AND_MEDIA = 0;
    const CIRCLE_TYPE_PRIVATE = 2;
    const CIRCLE_TYPE_PUBLIC = 1;
    const CIRCLE_TYPE_SYSTEM = 3;
} 