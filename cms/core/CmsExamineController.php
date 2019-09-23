<?php

namespace cms\core;

use common\libs\ConstClass;
use cms\modules\shop\models\LogExamine;
use cms\modules\shop\models\MemberShopExamine;
/**
 * 审核控制器接口
 */
abstract class CmsExamineController extends CmsController
{
    /*
     * 审核
     * @id int 要审核的数据ID
     * @examine_type 审核类型(1、通过 2、驳回)
     * @examine_key 审核的数据类型(1、店铺 2、商品)
     * */
    public function actionExamine($id,$examine_type,$examine_key){
        $examineModel = $this->findModel(['id'=>$id,'status'=>0]);
        if($examine_type == ConstClass::EXAMINE_TYPE_ADOPT){
            return $examineModel->examine($examine_type);
        }elseif($examine_type == ConstClass::EXAMINE_TYPE_REJECT){
            $logModel = new LogExamine();
            $this->layout = false;
            if(\Yii::$app->request->isPost){
                $logModel->load(\Yii::$app->request->post());
                $logModel->foreign_id = $id;
                $logModel->examine_key = $examine_key;
                if(!$logModel->save()){
                    return $this->checkSaveResult(false);
                }
                return $this->checkSaveResult($examineModel->examine($examine_type));
            }else{
                return $this->render("//public/examine",[
                    'model'=>$logModel
                ]);
            }
        }
    }
    //获取当前类的类名
    abstract protected function findModel($id);
}
