<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace cms\core\widgets;
use common\libs\ToolsClass;
use cms\config\system;
use yii\helpers\Html;

//use common\libs\DataSource;
//use dms\models\cfg\CfgMenu;

class LeftMenu extends \yii\bootstrap\Widget
{

    /**
     * @创建系统左侧菜单小部件
     */
    public $LeftMenuArr = [];
    public $pid = 65;
    public function run(){
        $MenuHtml = '';
        $leftMenuArray = isset(system::systemMenu()[$this->pid]['child']) ? system::systemMenu()[$this->pid]['child'] : [];
        //$PermissionKey = array_keys(DataSource::getPermissions());
        foreach($leftMenuArray as $Menu){
            if(isset($Menu['aa'])){
                //$childMenu = CfgMenu::find()->where(['pid'=>$Menu['id']])->asArray()->all();

                $MenuHtml.= '<li class="has-sub">' ;
                $MenuHtml .= Html::a('<span class="menu-text">'.$Menu["title"].'</span><span class="arrow"></span>','javascript:;',['class'=>""]);
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
            }else{
                $MenuHtml.= $Menu['key'] == $this->checkModel(\Yii::$app->request->get('r')) ? '<li class="has-sub active ">' : '<li class="has-sub">' ;
            }
            $MenuHtml .= '</li>';
        }
//        echo '<pre>';
//        print_r($MenuHtml);die;
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
            return 'cfg';
        }
        if(strstr($r,'/')){
            $r = explode('/',$r);
            $r = isset($r[1]) ? $r[1] : $r[0];
        }
        return $r;
    }
}
