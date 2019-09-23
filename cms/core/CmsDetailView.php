<?php
namespace cms\core;
use yii\widgets\DetailView;
class CmsDetailView extends DetailView
{
   public function init(){
       parent::init();
       $this->template = '<tr><th class="detail_th" {captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>';
   }
}
