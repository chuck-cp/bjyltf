<?php
/*
 * 系统常用函数类
 * */
namespace common\libs;


use dms\modules\system\models\SysConfig;
use pms\modules\config\models\PmsUsers;
use pms\modules\config\models\SysCountry;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UploadedFile;
/*
 * 系统资源类
 * 说明:该类用于系统常用方法的存储
 * */
class SystemClass
{
    /*
     * 获取配置参数
     * */
    public static function getConfig($id){
        $configModel = SysConfig::find()->where(['id'=>$id])->asArray();
        if(is_array($id)){
            $configModel = $configModel->select('id,value')->all();
            if(empty($configModel)){
                return false;
            }
            $resultArr = [];
            foreach($configModel as $config){
                $resultArr[$config['id']] = $config['value'];
            }
            return $resultArr;
        }
        $configModel = $configModel->select('value')->one();
        if(!empty($configModel)){
            return $configModel['value'];
        }
    }
    /*
     * 发送短信
     * */
    public static function sendMessage($mobile,$content){

        $params['username'] =\Yii::$app->params['SMS_API_USER'];
        $params['pwd'] = md5(\Yii::$app->params['SMS_API_PWD']);
        $params['p'] = $mobile;
        $params['charSetStr'] = "utf";
        $params['msg'] = '【最低捞】'.$content;

        $postQs = http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \Yii::$app->params['SMS_API']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postQs);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);

        $output = curl_exec($ch);
        $array = json_decode($output, true);
        if($array['status'] == 100){
            $status = 1;
        }
        curl_close($ch);
    }

    /*
     * 获取当前用户所有权限
     * @update 是否重新从数据库写入redis数据 1写入
     * */
    public static function getPermissions($update=0){
        $userID = \Yii::$app->user->getId() ;
        //如果update_permission等于1，重新生成权限缓存
        $update_permission = \Yii::$app->user->getIdentity()->update_permission;
        $redis = RedisClass::init();
        $permission = $redis->get('pms_permission_by_'.$userID);
        if(empty($permission) || $update == 0 || $update_permission){   //如果Cookie中没有数据,就从数据读取
            $permission = [];
            $Assignments = PmsUsers::findBySql('select b.parent,b.child,b.`value`,c.description from (zd_pms_assignment a LEFT JOIN zd_pms_item_child b on a.item_name = b.parent) LEFT JOIN zd_pms_item c on b.child = c.`name` where a.user_id = '.$userID)->asArray()->all();
            foreach($Assignments as $ass){
                $data = $ass['value'];
                if(!empty($permission[$ass['child']])){
                    $data = implode(',',array_unique(explode(',',$permission[$ass['child']]['data'].','.$ass['value'])));
                }
                $permission[$ass['child']] = [
                    'name'=>$ass['description'],
                    'data'=>$data,
                ];
            }
            //如果$update_permission等于1,更新缓存后将$update_permission恢复成0
            if($update_permission == 1){
                PmsUsers::updateAll(['update_permission'=>0],['id'=>$userID]);
            }

            $redis->set('pms_permission_by_'.$userID,serialize($permission));

        }else{
            $permission = unserialize($permission);
        }
        return $permission;
    }
}