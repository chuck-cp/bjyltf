<?php

namespace cms\modules\member\models;

use cms\modules\examine\models\Activity;
use common\libs\PyClass;
use Yii;
use cms\models\LogExamine;
use cms\modules\shop\models\Shop;
use cms\modules\member\models\Member;
use cms\modules\member\models\MemberLower;
use yii\base\Exception;

/**
 * This is the model class for table "{{%member_info}}".

 */
class MemberInfo extends \yii\db\ActiveRecord
{
    public $mobile;
    public $province;
    public $city;
    public $area;
    public $town;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'id_number', 'id_front_image', 'id_back_image'], 'required'],
            [['member_id', 'examine_status','electrician_examine_status'], 'integer'],
            [['id_number'], 'string', 'max' => 18],
            [['id_front_image', 'id_back_image', 'id_hand_image'], 'string', 'max' => 255],
            [['electrician_examine_status'], 'safe'],
        ];
    }
    /**
     * 根据member_id获取身份证信息
     */
    public static function getIdInfoByMemberId($member_id,$column){
        if(!$member_id || !$column){
            return '---';
        }else{
            return self::findOne(['member_id'=>$member_id]) ? self::findOne(['member_id'=>$member_id])->getAttribute($column) : '---';
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => '编号',
            'name' => '姓名',
            'id_number' => 'Id Number',
            'id_front_image' => 'Id Front Image',
            'id_back_image' => 'Id Back Image',
            'id_hand_image' => 'Id Hand Image',
            'examine_status' => '状态',
            'electrician_examine_status'=>'安装人员状态'
        ];
    }
    /**
     * 身份证审核(member_info,log_examine)
     */
    public static function saveInfo($obj, $status, $desc){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //yl_member_info
            $obj->examine_status = $status;
            $re = $obj->save(false);

            //yl_log_examine
            $logModel = new LogExamine();
            $logModel->examine_key = 2;
            $logModel->foreign_id = $obj->member_id;
            $logModel->examine_result = $status;
            switch ($desc){
                case '1':
                    $desc = '身份证信息有误';
                    break;
                case '2':
                    $desc = '地址有误';
                    break;
                default :
                    $desc = $desc;
            }
            $logModel->examine_desc = $desc;
            $logModel->create_user_id = Yii::$app->user->identity->getId();
            $logModel->create_user_name = Yii::$app->user->identity->username;
            $res = $logModel->save();

            if($status == 1){//如果审核通过
                //yl_shop
                Shop::updateAll(['member_name'=>$obj->name],['member_id'=>$obj->member_id]);
                //yl_member
                $pinyin = new PyClass();
                $initial = substr($pinyin->getpy($obj->name,true,true),0,1);
                $res = Member::updateAll(['name'=>$obj->name,'name_prefix'=>$initial],['id'=>$obj->member_id]);
                $memberlist = Member::findOne(['id'=>$obj->member_id]);
                if($memberlist){
                    $resActivity = Activity::updateAll(['member_name'=>$obj->name],['member_mobile'=>$memberlist->mobile]);
                }
                $resShop = Shop::updateAll(['introducer_member_name'=>$obj->name],['introducer_member_id'=>$obj->member_id]);
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            Yii::error($e->getMessage(),'error');
            return false;
        }
    }
    /**
     * 审核状态
     */
    public static function getMemberStatus($num){
        $srr = [
            '-1' => '待提交',
            '0' => '待审核',
            '1' => '审核通过',
            '2' => '审核未通过',
        ];
        if(array_key_exists($num,$srr)){
            return $srr[$num];
        }else{
            return '---';
        }
    }

    /**
     * 电工证审核状态
     */
    public static function getMemberElectricianStatus($num){
        $srr = [
            '-1' => '待提交',
            '0' => '待审核',
            '1' => '审核通过',
            '2' => '已驳回',
        ];
        if(array_key_exists($num,$srr)){
            return $srr[$num];
        }else{
            return '---';
        }
    }

    /**
     * 获得用户身信息
     */
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id']);
    }

    /**
     * 关联查询获取用户的收益金额、店铺总数、装屏总数
     */
    public function getMemCount(){
        return $this->hasOne(MemberAccount::className(),['member_id'=>'member_id']);
    }

    /**
     * 关联team表获取团队名称
     */
    public function getMemTeam(){
        return $this->hasOne(MemberTeam::className(),['id'=>'join_team_id'])->select('id,team_member_name,team_name');
    }
}
