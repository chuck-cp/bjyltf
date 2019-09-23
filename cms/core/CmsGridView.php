<?php
namespace cms\core;
use yii\grid\GridView;

class CmsGridView extends GridView
{
   public function init(){
       parent::init();
       if(empty($this->pager)){
           $this->pager = [
               'firstPageLabel' => '首页',
               'nextPageLabel' => '下一页',
               'prevPageLabel' => '上一页',
               'lastPageLabel' => '末页',
           ];
       }
   }
}
