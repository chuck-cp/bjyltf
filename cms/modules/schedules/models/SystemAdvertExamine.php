<?php

namespace cms\modules\schedules\models;

use Yii;
use yii\base\Exception;
use cms\models\LogExamine;
/**
 * This is the model class for table "yl_system_advert_examine".
 *
 * @property string $id
 * @property string $date 广告投放日期
 * @property int $examine_status 审核状态(0、待审核 1、已通过 2、已驳回)
 * @property int $examine_number 审核次数
 * @property string $examine_user_id 审核人ID
 */
class SystemAdvertExamine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_system_advert_examine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date'], 'safe'],
            [['examine_status', 'examine_number', 'examine_user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'examine_status' => 'Examine Status',
            'examine_number' => 'Examine Number',
            'examine_user_id' => 'Examine User ID',
        ];
    }

    public function getAdvertExamine($id,$data){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $Model = self::findOne($id);
            $user_id = Yii::$app->user->identity->getId();
            if($Model->examine_number==1){
                if($user_id == $Model->examine_user_id){
                    return json_encode(['code'=>'2','msg'=>'二次审核，请更换用户']);
                }
            }
            $Model->examine_status = 0;
            if($Model->examine_number==0){
                if($data['type']==2){
                    $Model->examine_status =$data['type'];
                }
            }else{
                $Model->examine_status = $data['type'];
            }
            $Model->examine_number += 1;
            $Model->examine_user_id = $user_id;
            $Model->save();
            $examine_desc = '';
            if($data['type'] ==2){
                $examine_desc =$data['rebut_advert'];
            }
            $LogModel=new LogExamine();
            $LogModel->examine_key=9;
            $LogModel->foreign_id=$Model->id;
            $LogModel->examine_result=$data['type'];
            $LogModel->examine_desc=$examine_desc;
            $LogModel->create_user_id=Yii::$app->user->identity->getId(); //获取用户ID
            $LogModel->create_user_name=Yii::$app->user->identity->username;     //获取用户姓名
            $LogModel->save();
            $transaction->commit();
            return json_encode(['code'=>'1','msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>'3','msg'=>'操作失败']);
        }
    }

}
