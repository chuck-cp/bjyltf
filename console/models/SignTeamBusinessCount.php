<?php

namespace console\models;


use common\libs\ToolsClass;
use yii\db\ActiveRecord;

class SignTeamBusinessCount extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_team_business_count}}';
    }


    // 保存签到数据
    public function saveSignCountData($signCountData,$create_at) {
        try {
            if (empty($signCountData)) {
                return true;
            }
            $countModel = new SignTeamBusinessCount();
            foreach ($signCountData as $team_id => $countData) {
                $cloneModel = clone $countModel;
                $cloneModel->team_id = $team_id;
                $cloneModel->total_sign_member_number = $countData['total_sign_member_number'];
                $cloneModel->overtime_sign_member_number = $countData['overtime_sign_member_number'];
                $cloneModel->no_sign_member_number = $countData['no_sign_member_number'];
                $cloneModel->unqualified_member_number = $countData['unqualified_member_number'];
                $cloneModel->total_sign_shop_number = $countData['total_sign_shop_number'];
                $cloneModel->leave_early_number = $countData['leave_early_number'];
                $cloneModel->repeat_sign_number = $countData['repeat_sign_number'];
                $cloneModel->repeat_sign_rate = $countData['repeat_sign_rate'];
                $cloneModel->repeat_shop_number = $countData['repeat_shop_number'];
                $cloneModel->create_at = $create_at;
                $cloneModel->save();
                # 写入重复店铺
                $shopDetailModel = new SignTeamCountShopDetail();
                if ($cloneModel->repeat_shop_number > 0) {
                    foreach ($countData['repeat_shop_list'] as $key => $value) {
                        $shopCloneModel = clone $shopDetailModel;
                        $shopCloneModel->shop_name = $value['shop_name'];
                        $shopCloneModel->team_id = $team_id;
                        $shopCloneModel->sign_id = $value['shop_id'];
                        $shopCloneModel->sign_number = $value['number'];
                        $shopCloneModel->mongo_id = $key;
                        $shopCloneModel->create_at = $create_at;
                        $shopCloneModel->save();
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            ToolsClass::printLog('save_sign_count_data',$e->getMessage());
            \Yii::error($e->getMessage());
            return false;
        }
    }
}
