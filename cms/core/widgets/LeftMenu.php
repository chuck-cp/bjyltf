<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace cms\core\widgets;

use cms\models\LoginForm;
use common\libs\ToolsClass;
use cms\config\system;
use yii\helpers\Html;

class LeftMenu extends \yii\bootstrap\Widget
{

    /**
     * @创建系统左侧菜单小部件
     */
    public $pid = 65;
    public function run(){
        $MenuHtml = '';
        $leftMenuArray = isset(system::systemMenu()[$this->pid]['child']) ? system::systemMenu()[$this->pid]['child'] : [];
        foreach($leftMenuArray as $Menu){
            if(empty($Menu["href"])){
                $href = 'javascript:';
            }else{
                if(!LoginForm::checkPermission($Menu["href"])){
                    continue;
                }
                $href = [$Menu["href"]];
            }
            $href = empty($Menu["href"]) ? 'javascript:' : [$Menu["href"]];
            if($Menu['key'] == 'menu_title'){
                $MenuHtml .= '<li class="has-sub menu_title">';
            }else{
                //$MenuHtml.= $Menu['key'] == $this->checkModel(\Yii::$app->request->get('r')) ? '<li class="has-sub active ">' : '<li class="has-sub">' ;
                /************************************/
                if($Menu['key'] == $this->checkModel(\Yii::$app->request->get('r'))){
                    $MenuHtml.= '<li class="has-sub active ">';
                }else{
                    $MenuHtml.= '<li class="has-sub">';
                    if(isset($Menu['aa'])){
                        $MenuHtml .= Html::a('<span class="menu-text">'.$Menu["title"].'</span><span class="arrow"></span>','javascript:;',['class'=>""]);
                    }
                }
                /************************************/
            }
            $MenuHtml .= Html::a('<span class="name">'.$Menu["title"].'</span>',$href,['class'=>"dropdown-toggle tip-bottom",'data-toggle'=>"tooltip"]);
            /**************判断有无子菜单*****************/
            if(isset($Menu['aa'])){
                //$MenuHtml.= '<li class="has-sub">' ;
                $MenuHtml .= '<ul class="sub" style="display:none" id="parentMenu_'.$Menu['id'].'">';
                foreach($Menu['aa'] as $c){
                    //循环输出下级分类
                    //if(ToolsClass::checkMenuPermission($c["href"],$PermissionKey)){
                    if($c['model'] == $this->checkModel(\Yii::$app->request->get('r'))){
                        $MenuHtml .= '<li class="current">';
                        $MenuHtml .= Html::a('<span class="menu-text">'.$c["title"].'</span>',[$c["href"]],['class'=>""]);
                    }else{
                        $MenuHtml .= '<li>';
                        $MenuHtml .= Html::a('<span class="menu-text">'.$c["title"].'</span>',[$c["href"]],['class'=>""]);
                    }
                    // }
                    $MenuHtml .= '</li>';
                }
                $MenuHtml .= '</ul>';
            }
            /**************判断有无子菜单*****************/
            $MenuHtml .= '</li>';
        }
        return $MenuHtml;
    }
    public function init()
    {
        parent::init();
        if($r = \Yii::$app->request->get('r')){
            if(strstr($r,'/')){
                $r = explode('/',$r);
                $this->pid = $r[0];
            }else{
                $this->pid = $r;
            }
        }else{
            $this->pid = 'config';
        }
    }
    public function checkModel($r){
        if(empty($r)){
            return 'config';
        }
        if(strstr($r,'/')){
            $r = explode('/',$r);
            $r = isset($r[2]) ? $r[1].'_'.$r[2] : $r[0];
        }
        return $r;
    }
}
