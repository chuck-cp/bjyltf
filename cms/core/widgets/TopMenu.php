<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace cms\core\widgets;
use cms\models\LoginForm;
use cms\modules\authority\models\AuthAssignment;
use cms\modules\authority\models\AuthItemChild;
use common\libs\ToolsClass;
use cms\config\system;
use yii\helpers\Html;
use Yii;

class TopMenu extends \yii\bootstrap\Widget
{

    /**
     * @创建系统菜单小部件
     */
    public function run(){
        if(Yii::$app->user->isGuest){
            $this->goHome();
            return false;
        }
        $MenuHtml = '';
        $topMenuArray = system::systemMenu();
        $uid = \Yii::$app->user->identity->getId();
        $itemName = AuthAssignment::find()->where(['user_id'=>$uid])->select('item_name')->asArray()->all();
        $newhref = [];
        foreach($itemName as $kin=>$vin){
            $hrefs = AuthItemChild::find()->where(['parent'=>$vin['item_name']])->select('child')->asArray()->all();//赋予的权限路径
            if(empty($hrefs)){
                continue;
            }else{
                foreach($hrefs as $ks=>$vs){
                    $hrefkeys[] = explode('/',$vs['child']);
                }
                $newhref[] = $hrefs;
            }
        }
        if(in_array('超级管理员',array_column($itemName,'item_name'))){
            foreach($topMenuArray as $k=>$Menu){
                if(empty($Menu["href"])){
                    $href = $Menu["child"][0]['href'];
                }else{
                    $href = $Menu['href'];
                }
                if(!LoginForm::checkPermission($href)){
                    continue;
                }
                $MenuHtml.= $k == $this->checkModel(\Yii::$app->request->get('r')) ? '<li class="dropdown open">' : '<li class="dropdown">' ;
                $MenuHtml .= Html::a('<span class="name">'.$Menu["title"].'</span>',[$href],['class'=>"dropdown-toggle tip-bottom",'data-toggle'=>"tooltip"]);
                $MenuHtml .= '</li>';
            }
        }else{
            if(empty($hrefkeys)){
                return '';
            }
            $href = [];
            $hrefkey = array_unique(array_column($hrefkeys,1));
            foreach($topMenuArray as $k=>$Menu){
                if(in_array($k,$hrefkey)){
                    if(empty($Menu["href"])){
                        $menuhref = array_column($Menu['child'],'href');//顶部+左侧路径
//                        $href = $Menu["child"][0]['href'];
                        foreach($newhref as $klist => $vlist){//赋予的权限路径
                            $hreflist = array_column($vlist,'child');
                            foreach($menuhref as $km=>$vm){
                                if(in_array($vm,$hreflist)){
                                    $href = $vm;
                                    continue;
//                                }else{
//                                    $href = $vm;
                                }
                            }
                        }
                    }else{
                        $href = $Menu['href'];
                    }
                    if(!LoginForm::checkPermission($href)){
                        continue;
                    }
                    $MenuHtml.= $k == $this->checkModel(\Yii::$app->request->get('r')) ? '<li class="dropdown open">' : '<li class="dropdown">' ;
                    $MenuHtml .= Html::a('<span class="name">'.$Menu["title"].'</span>',[$href],['class'=>"dropdown-toggle tip-bottom",'data-toggle'=>"tooltip"]);
                    $MenuHtml .= '</li>';
                }
            }
        }
        return $MenuHtml;
    }
    public function init()
    {
        parent::init();
    }
    public function checkModel($r){
        if(empty($r)){
            return 'config';
        }
        if(strstr($r,'/')){
            $r = explode('/',$r);
            $r = $r[0];
        }
        return $r;
    }
}
