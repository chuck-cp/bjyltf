<?php
namespace cms\core;

use yii\db\ActiveRecord;

class CmsActiveRecord extends ActiveRecord
{
    public static function getDb(){
        $dbName = 'zdlao_'.\Yii::$app->session->get('pms_language_code');
        return \Yii::$app->get($dbName);
    }
    /*
     * 返回操作结果数据
     * */
    public function returnSaveResult($result,$message){
        return [
            'result'=>$result,
            'message'=>$message
        ];
    }
    public function save($runValidation = true, $attributeNames = null){
        if(parent::save()){
            return true;
        }else{
            print_r($this->errors);
            //throw new ErrorException("db error");
        }
    }

    /*
     * 用于接收ajax对数据的修改和删除
     * @operation 操作类型(update|delete)
     * @keyName 主键名称
     * */
    public function ajaxSubmit($operation='update',$keyName='id',$where=[]){
        if(!\Yii::$app->request->isAjax){
            return false;
        }
        $status = (int)\Yii::$app->request->get('status');
        $endStatus = \Yii::$app->request->get('endStatus');
        $id = \Yii::$app->request->get('id');
        if(strstr($id,',')){
            $id = explode(',',$id);
        }else{
            $id = (int)$id;
        }
        if(empty($id)){
            return false;
        }
        if($operation == 'update'){
            $andWhere = [$keyName=>$id,'status'=>$status];
            if(!empty($where)){
                $andWhere = array_merge($andWhere,$where);
            }
            //没有设置修改后的状态
            if(!is_numeric($endStatus)){
                if($status == 1){
                    $endStatus = 2;
                }else{
                    $endStatus = 1;
                }
            }
            $updateResult = self::updateAll(['status'=>$endStatus],$andWhere);
        }elseif($operation == 'delete'){
            $updateResult = self::deleteAll([$keyName=>$id]);
        }

        return $updateResult;
    }
}
