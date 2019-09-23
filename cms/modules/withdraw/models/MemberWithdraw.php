<?php

namespace cms\modules\withdraw\models;
use cms\modules\member\models\MemberInfo;
use Yii;
use cms\models\LogExamine;
use cms\models\LogAccount;
use cms\modules\member\models\MemberAccount;
use cms\modules\withdraw\models\MemberAccountCount;
use cms\modules\withdraw\models\MemberAccountMessage;
use yii\base\Exception;

/**
 * This is the model class for table "{{%member_withdraw}}".
 *
 * @property string $id
 * @property string $serial_number 流水号
 * @property string $member_id 提现人ID
 * @property string $member_name 提现人姓名
 * @property string $mobile 提现人手机号
 * @property string $bank_name 提现人银行名称
 * @property string $bank_mobile 银行预留手机号
 * @property string $payee_name 收款人姓名
 * @property int $status 状态(0、未提现 1、已提现2、提现失败)
 * @property string $price 提现金额
 * @property string $poundage 手续费
 * @property string $account_balance 账户余额
 * @property int $examine_status 审核状态(0、待审核 1、财务已审核 2、审计已审核 3、出纳已打款)
 * @property string $create_at 提现时间
 * @property int $account_type 账号类型（1、个人账号2、公司账号）
 */
class MemberWithdraw extends \yii\db\ActiveRecord
{
    public $create_at_end;
    public $update_at_end;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_withdraw}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial_number', 'member_id', 'member_name', 'mobile', 'bank_name', 'bank_mobile', 'payee_name','bank_account'], 'required'],
            [['serial_number', 'member_id', 'status', 'price', 'poundage', 'account_balance', 'examine_status', 'account_type'], 'integer'],
            [['create_at'], 'safe'],
            [['member_name', 'payee_name'], 'string', 'max' => 50],
            [['mobile', 'bank_mobile'], 'string', 'max' => 20],
            [['bank_account'],'string','max' => 25],
            [['bank_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => '流水号',
            'member_id' => '提现人ID',
            'member_name' => '姓名',
            'mobile' => '手机号',
            'bank_name' => '收款人银行',
            'bank_mobile' => '银行预留手机号',
            'payee_name' => '收款人',
            'status' => '提现状态',
            'price' => '提现金额',
            'poundage' => '手续费',
            'account_balance' => '账户余额',
            'examine_status' => '审核状态',
            'create_at' => '申请时间',
            'account_type' => '账户类型',
            'bank_account' => '收款账号',
        ];
    }
    /**
     * 提现状态
     */
    public static function getExaimneStatus($num,$result=1)
    {
        $arr = [
            '0' => '待审核',
            '1' => '财务',
            '2' => '审计',
            '3' => '出纳',
        ];
        if($num == 0){ return $arr[0];}
        if(array_key_exists($num,$arr)){
            if($result == 0){
                return $arr[$num].'待审核';
            }elseif ($result == 1){
                return $arr[$num].'已驳回';
            }else{
                return $arr[$num].'已通过';
            }
        }else{
            return '---';
        }
    }
    /**
     * 审核通过修改状态和在log_examine表里写数据(财务和审计操作)
     */
    public function saveWithdraw($id,$type,$status,$result,$content=''){
       try{
           $price = self::findOne(['id'=>$id])->getAttribute('price');
           //yl_member_withdraw
           $model = self::findOne(['id'=>$id]);
           $model->examine_status = $status;
           $model->examine_result = $result;
           $foreign_id = $model->save();
           //驳回
           if($result == 1){
                $re = $this->handMoney($id,$type,$result,$content,$price);
                if(!$re){
                    throw new Exception('驳回失败');
                }
           }
           return true;
       }catch (Exception $e){
           Yii::error($e->getMessage(),'error');
           return false;
       }

    }
    /**
     *提现成功或失败后的操作（出纳）
     */
    public function saveCashier($id,$type,$status,$result){
        try{
            $content = '';
            //1.yl_member_withdraw
            $model = self::findOne(['id'=>$id]);
            $member_id = $model->getAttribute('member_id');
            $account_id = $model->getAttribute('account_id');
            $model->examine_status = $status;
            $model->examine_result = $result;
            $model->status = $result == 2 ? 1 : 0;
            $withRes = $model->save();

            //2.yl_log_examine
            $logModel = new LogExamine();
            $logModel->examine_key = $type;
            $logModel->foreign_id = $id;
            $logModel->examine_result = $result == 2 ? 1 : 2;
            $logModel->examine_desc = $content;
            $logModel->create_user_id = Yii::$app->user->identity->getId();
            $logModel->create_user_name = Yii::$app->user->identity->username;
            $logExmRes = $logModel->save();

            //3.yl_log_account
            $account_id = $model->getAttribute('account_id');
            $logAccountModel = new LogAccount();

            //4.yl_member_account
            $maModel = MemberAccount::findOne(['member_id'=>$member_id]);

            //5.yl_member_account_count
            $accountAccountModel = new MemberAccountCount();
            $accountAccountModel->member_id = $member_id;

            //6.yl_member_account_message
            $accountMessageModel = new MemberAccountMessage();
            $accountMessageModel->member_id = $member_id;
            if($result == 2){
                //3
                $lModel = LogAccount::findOne(['id'=>$account_id]);
                $lModel->status = 2;
                $logAccRes = $lModel->save();

                //4
                $maModel->withdraw_price += $model->price;
                $accRes = $maModel->save();
                //5
                $yearMonth = date('Y-m',time());
                $accAccModel = MemberAccountCount::findOne(['member_id'=>$member_id,'create_at'=>$yearMonth]);
                if($accAccModel){//modify
                    $accAccModel->withdraw_price += $model->price;
                    $accAccModel->save();
                }else{//new record
                    $accountAccountModel->withdraw_price += $model->price;
                    $accountAccountModel->create_at = $yearMonth;
                    $accountAccountModel->save();
                }

                //6
                $accountMessageModel->title = '你申请的'.number_format($model->price/100,2).'元提现成功。';
                $messageRes = $accountMessageModel->save();

            }elseif($result == 1){
                //3提现失败在此表里写一条记录
                $logAccountModel->status = 3;
                $logAccountModel->type = 1;
                $logAccountModel->price = $model->price;
                $logAccountModel->title = '提现失败';
                $logAccountModel->member_id = $member_id;
                $logAccountModel->account_type = 3;
                $logAccountModel->before_price = MemberAccount::findOne(['member_id'=>$member_id])->getAttribute('balance') ;
                $logAccRes = $logAccountModel->save();

                //4
                $maModel->balance += $model->price;
                $accRes = $maModel->save();

                //6
                $accountMessageModel->title = '你申请的'.number_format($model->price/100,2).'元提现失败。';
                $messageRes = $accountMessageModel->save();
            }
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            return false;
        }
    }
    /**
     * 提现管理通过或者驳回的时候钱的操作
     */
    public function handMoney($id,$type,$result,$content='',$price){
        try{
            $model = self::findOne(['id'=>$id]);
            $member_id = $model->getAttribute('member_id');
            $logModel = new LogExamine();
            $logModel->examine_key = $type;//默认3
            $logModel->foreign_id = $id;
            $logModel->examine_result = $result == 2 ? 1 : 2;
            $logModel->examine_desc = $content;
            $logModel->create_user_id = Yii::$app->user->identity->getId();
            $logModel->create_user_name = Yii::$app->user->identity->username;
            $res = $logModel->save();
            //MemberAccount
            if($result == 1){
                $memAccountModel = MemberAccount::findOne(['member_id'=>$member_id]);
                $memAccountModel->balance += $price;
                $memAccountModel->save();

                $accountMessageModel = new MemberAccountMessage();
                $accountMessageModel->title = '你申请的'.number_format($price/100,2).'元提现失败。';
                $accountMessageModel->member_id = $member_id;
                $messageRes = $accountMessageModel->save();
                //3提现失败在此表里写一条记录
                $logAccountModel = new LogAccount();
                $logAccountModel->status = 3;
                $logAccountModel->type = 1;
                $logAccountModel->price = $model->price;
                $logAccountModel->title = '提现失败';
                $logAccountModel->desc = $content;
                $logAccountModel->member_id = $member_id;
                $logAccountModel->account_type = 3;
                $logAccountModel->before_price = MemberAccount::findOne(['member_id'=>$member_id])->getAttribute('balance') ;
                $logAccRes = $logAccountModel->save();
            }
            return true;
        }catch (Exception $e){
            return false;
        }
        //MemberAccountCount

    }

    /**
     * 获取账户信息
     */
    public function getMemberinfo(){
        return $this->hasOne(MemberInfo::className(),['member_id'=>'member_id'])->select('member_id,name,id_number');
    }
}
