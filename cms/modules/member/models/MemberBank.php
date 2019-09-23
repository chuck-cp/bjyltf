<?php

namespace cms\modules\member\models;

use cms\modules\config\models\SystemBank;
use Yii;

/**
 * This is the model class for table "{{%member_bank}}".
 *
 * @property string $id
 * @property string $member_id 用户ID
 * @property string $name 持卡人姓名
 * @property string $number 银行卡号
 * @property string $bank_name 银行名称
 * @property int $bank_id 银行ID
 * @property string $mobile 预留手机号
 */
class MemberBank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_bank}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'name', 'mobile'], 'required'],
            [['member_id', 'bank_id'], 'integer'],
            [['name', 'number'], 'string', 'max' => 50],
            [['bank_name'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 20],
            [['create_at'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'name' => '账户名称',
            'number' => '银行卡号',
            'bank_name' => '银行名称',
            'bank_id' => 'Bank ID',
            'mobile' => '银行预留电话',
            'create_at' => '绑定日期',
        ];
    }

    /**
     * 获得用户身份证信息
     */
    public function getMembreInfo(){
        return $this->hasOne(MemberInfo::className(),['member_id'=>'member_id']);//->select('member_id, name, id_number, id_front_image, id_back_image, id_hand_image,examine_status')
    }

    /**
     * 获得用户对应银行
     */
    public function getSystemBank(){
        return $this->hasOne(SystemBank::className(),['id'=>'bank_id']);
    }
}
