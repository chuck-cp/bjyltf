<?php

namespace console\models;


use common\libs\ToolsClass;
use yii\db\ActiveRecord;

class SignBusinessCount extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_business_count}}';
    }

    // 保存签到数据
    public function saveSignCountData($signCountData,$create_at) {
        try {
            if (empty($signCountData)) {
                return true;
            }
            $total_sign_member_list = [];
            $this->overtime_sign_member_number = 0;
            $this->no_sign_member_number = 0;
            $this->unqualified_member_number = 0;
            $this->repeat_sign_number = 0;
            $this->repeat_shop_number = 0;
            $this->leave_early_number = 0;
            foreach ($signCountData as $team_id => $countData) {
                $total_sign_member_list = array_merge($total_sign_member_list,$countData['total_sign_member_list']);
                $this->leave_early_number += $countData['leave_early_number'];
                $this->overtime_sign_member_number += $countData['overtime_sign_member_number'];
                $this->no_sign_member_number += $countData['no_sign_member_number'];
                $this->unqualified_member_number += $countData['unqualified_member_number'];
                $this->total_sign_shop_number += $countData['total_sign_shop_number'];
                $this->repeat_sign_number += $countData['repeat_sign_number'];
                $this->repeat_shop_number += $countData['repeat_shop_number'];
            }
            $this->total_sign_member_number = count(array_unique($total_sign_member_list));
            $this->create_at = $create_at;
            $this->repeat_sign_rate = $this->total_sign_shop_number == 0 ? 0 : number_format($this->repeat_sign_number / $this->total_sign_shop_number,2);
            $this->save();
            return true;
        } catch (\Exception $e) {
            ToolsClass::printLog('save_sign_count_data',$e->getMessage());
            \Yii::error($e->getMessage());
            return false;
        }
    }
}
