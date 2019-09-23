<?php
namespace cms\core;
use common\libs\ConstClass;
use cms\modules\shop\models\MemberShopExamine;
use yii\base\Exception;


abstract class CmsExamineModel extends CmsActiveRecord
{
    /*
     * 审核数据
     * @examine_type int 审核结果类型(1、通过 2、驳回)
     * @logModel obj 写入审核失败的log对象
     * */
    public function examine($examine_type){
        //$dbTrans = \Yii::$app->$lan->beginTransaction();
        try{
            if($examine_type == ConstClass::EXAMINE_TYPE_ADOPT){
                //审核通过
                $examineResult = $this->examineAdopt();
            }elseif($examine_type == ConstClass::EXAMINE_TYPE_REJECT){
                //审核部通过
                $examineResult = $this->examineReject();
            }
            //$dbTrans->commit();
            return $examineResult;
        }catch (Exception $e){
            print_r($e->getMessage());
            //$dbTrans->rollBack();
            return false;
        }
    }
    //审核通过
    abstract protected function examineAdopt();
    //审核不通过
    abstract protected function examineReject();
}
