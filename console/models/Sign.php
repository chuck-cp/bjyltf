<?php

namespace console\models;



use common\libs\ArrayClass;
use common\libs\Redis;
use common\libs\ToolsClass;
use yii\base\Model;
use yii\db\ActiveRecord;

class Sign extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign}}';
    }

    /*
     * 获取签到数据
     * @param string date 日期
     * @param string signType 签到类型
     * */
    public function getSignData($data,$signType) {
        if ($signType == 'business') {
            return self::find()->joinWith('business',false)->where(['and',['>=','create_at',$data .' 00:00:00'],['<=','create_at',$data .' 23:59:59'], ['team_type' => 1]])->select('mongo_id,member_id,team_id,late_sign,shop_name,yl_sign.id')->asArray()->all();
        } else {
            return self::find()->where(['and',['>=','create_at',$data .' 00:00:00'],['<=','create_at',$data .' 23:59:59'], ['team_type' => 2]])->select('member_id,team_id,late_sign')->asArray()->all();
        }
    }

    // 统计每日人员签到数据
    public function memberSignCount() {
        $dbTrans = \Yii::$app->db->beginTransaction();
        try {
            $countDate = date('Y-m-d',strtotime('-1 day'));
            $memberCountModel = new SignMemberCount();
            # 获取未签到、超时签到、未达标的成员、已签到成员、团队ID、早退成员
            list($lateSignMember,$noSignMember,$noQualifiedMember,$signMember,$teamData,$leaveEarlyMember) = $memberCountModel->getMemberCountData($countDate);
            # 获取统计数据
            $maintainSignData = $this->reformSignData('maintain',$countDate,$teamData['maintain'],$lateSignMember,$noSignMember,$noQualifiedMember,$signMember,$leaveEarlyMember);
            $businessSignData = $this->reformSignData('business',$countDate,$teamData['business'],$lateSignMember,$noSignMember,$noQualifiedMember,$signMember,$leaveEarlyMember);
            # 业务人员团队总和统计
            $businessModel = new SignBusinessCount();
            if(!$businessModel->saveSignCountData($businessSignData,$countDate)){
                throw new \Exception("业务人员团队总和统计错误");
            }
            # 维护人员团队总和统计
            $maintainModel = new SignMaintainCount();
            if(!$maintainModel->saveSignCountData($maintainSignData,$countDate)){
                throw new \Exception("业务人员团队总和统计错误");
            }
            # 业务人员按团队统计
            $teamBusinessModel = new SignTeamBusinessCount();
            if(!$teamBusinessModel->saveSignCountData($businessSignData,$countDate)){
                throw new \Exception("业务人员按团队统计错误");
            }
            # 维护人员按团队统计
            $teamMaintainModel = new SignTeamMaintainCount();
            if(!$teamMaintainModel->saveSignCountData($maintainSignData,$countDate)){
                throw new \Exception("维护人员按团队统计错误");
            }
            $dbTrans->commit();
        } catch (\Exception $e) {
            $dbTrans->rollBack();
            ToolsClass::printLog('member_sign_count','Line:'.$e->getLine().' '.$e->getMessage());
        }
    }


    /*
     * 分析签到数据,计算出签到总人数、超时签到人数、未达标人数、未签到人数
     * @param signType string 签到数据类型(业务人员或维护人员)
     * @param signData array 签到数据
     * @param teamData array 团队ID
     * @param lateSignMember array 超时签到成员
     * @param noQualifiedMember array 未达标成员列表
     * @param noSignMember array 未签到成员列表
     * @param signMember array 已签到成员列表
     * @param leaveEarlyMember array 早退的成员列表
     * */
    public function reformSignData($signType,$countDate,$teamData,$lateSignMember,$noSignMember,$noQualifiedMember,$signMember,$leaveEarlyMember) {
        # 组合返回结果
        $resultData = [];
        # 获取签到数据
        $signData = $this->getSignData($countDate,$signType);
        $teamData = array_unique($teamData);
        if ($signType == 'business') {
            $reformData = [];
            # 业务员签到
            $sign_shop_data = []; # 用于计算重复店铺的数据
            foreach ($signData as $key => $value) {
                if (!isset($reformData[$value['team_id']])) {
                    $reformData[$value['team_id']] = [];
                }
                if (!isset($reformData[$value['team_id']][$value['member_id']])) {
                    $reformData[$value['team_id']][$value['member_id']] = 1;
                } else {
                    $reformData[$value['team_id']][$value['member_id']]++;
                }
                $sign_shop_data[$value['team_id']][] = ['shop_id' => $value['id'], 'mongo_id' => $value['mongo_id'], 'shop_name'=>$value['shop_name']];
            }
            // 业务员签到
            foreach ($teamData as $team_id) {
                $total_sign = ArrayClass::issetArray($sign_shop_data,$team_id,[]);
                # 总签到数
                $total_sign_shop_number = count($total_sign);
                # 重复签到数
                if (empty($total_sign)) {
                    $repeat_sign_number = $total_sign_shop_number;
                } else {
                    $repeat_sign_number = $total_sign_shop_number - count(array_unique(array_column($total_sign,'mongo_id')));
                }
                # 重复签到店铺
                $repeat_shop_list = $this->reduceRepeatShop($total_sign);
                # 重复签到率
                $repeat_sign_rate = empty($total_sign_shop_number) ? 0 : number_format($repeat_sign_number / $total_sign_shop_number,2);
                $overtime_sign_member_number = ArrayClass::issetArray($lateSignMember,$team_id,[]);
                $no_sign_member_number = ArrayClass::issetArray($noSignMember,$team_id,[]);
                $unqualified_member_number = ArrayClass::issetArray($noQualifiedMember,$team_id,[]);
                $total_sign_member_list = ArrayClass::issetArray($reformData,$team_id,[]);
                $leave_early_member = ArrayClass::issetArray($leaveEarlyMember,$team_id,[]);

                $resultData[$team_id] = [
                    'total_sign_member_list' => array_keys($total_sign_member_list),
                    'total_sign_member_number' => count($total_sign_member_list),
                    'overtime_sign_member_number' => count($overtime_sign_member_number),
                    'no_sign_member_number' => count($no_sign_member_number),
                    'unqualified_member_number' => count($unqualified_member_number),
                    'leave_early_number' => count($leave_early_member),
                    'total_sign_shop_number' => $total_sign_shop_number,
                    'repeat_sign_number' => $repeat_sign_number,
                    'repeat_sign_rate' => $repeat_sign_rate,
                    'repeat_shop_number' => count($repeat_shop_list),
                    'repeat_shop_list' => $repeat_shop_list
                ];
            }
        } else {
            $sign_shop_data = []; # 用于计算签到总数量
            if ($signData) {
                foreach ($signData as $key => $value) {
                    $sign_shop_data[$value['team_id']] = isset($sign_shop_data[$value['team_id']]) ? $sign_shop_data[$value['team_id']] + 1 : 1;
                }
            }
            # 维护人员签到
            foreach ($teamData as $team_id) {
                # 总签到数
                $resultData[$team_id] = [
                    'total_sign_number' => ArrayClass::issetArray($sign_shop_data,$team_id),
                    'total_sign_member_number' => count(ArrayClass::issetArray($signMember,$team_id,[])),
                    'overtime_sign_member_number' => count(ArrayClass::issetArray($lateSignMember,$team_id,[])),
                    'no_sign_member_number' => count(ArrayClass::issetArray($noSignMember,$team_id,[])),
                    'unqualified_member_number' => count(ArrayClass::issetArray($noQualifiedMember,$team_id,[])),
                    'leave_early_number' => count(ArrayClass::issetArray($leaveEarlyMember,$team_id,[])),
                ];
            }
        }
        return $resultData;
    }

    /**
     * 计算重复签到的店铺的签到数量和第一次签到的店铺名称
     */
    public function reduceRepeatShop($signShopData) {
        $shopList = [];
        foreach ($signShopData as $data) {
            if (isset($shopList[$data['mongo_id']])) {
                if ($data['shop_id'] < $shopList[$data['mongo_id']]['shop_id']) {
                    $shopList[$data['mongo_id']]['shop_name'] = $data['shop_name'];
                }
                $shopList[$data['mongo_id']]['number']++;
            } else {
                $shopList[$data['mongo_id']] = ['shop_id' => $data['shop_id'],'shop_name' => $data['shop_name'],'number' => 1];
            }
        }
        foreach ($shopList as $key => $value) {
            if ($value['number'] < 2) {
                unset($shopList[$key]);
            }
        }
        return $shopList;
    }

    public function getBusiness() {
        return $this->hasOne(SignBusiness::className(),['sign_id' => 'id'])->select('mongo_id');
    }
}
