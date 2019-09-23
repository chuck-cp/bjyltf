<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "yl_member_install_subsidy_list".
 *
 * @property string $id
 * @property string $subsidy_id install_subidy表的ID
 * @property string $subsidy_price 补贴金额(分)
 * @property string $subisdy_desc 补贴理由
 * @property string $create_user_id 补贴人ID
 * @property string $create_user_name 补贴人姓名
 * @property string $create_at 补贴时间
 */
class MemberInstallSubsidyList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member_install_subsidy_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subsidy_id', 'subsidy_price', 'create_user_id'], 'integer'],
            [['subisdy_desc', 'create_user_name'], 'required'],
            [['create_at'], 'safe'],
            [['subisdy_desc'], 'string', 'max' => 100],
            [['create_user_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subsidy_id' => 'Subsidy ID',
            'subsidy_price' => 'Subsidy Price',
            'subisdy_desc' => 'Subisdy Desc',
            'create_user_id' => 'Create User ID',
            'create_user_name' => 'Create User Name',
            'create_at' => 'Create At',
        ];
    }
    //安装人每日补贴新增记录
    public static function AddingRecord($subsidy_price,$id,$install_member_id,$subisdy_desc){
        $model = new MemberInstallSubsidyList();
        $model->subsidy_id=$id;
        $model->subsidy_price=$subsidy_price;
        $model->install_member_id=$install_member_id;
        $model->subisdy_desc=$subisdy_desc;
        $model->create_user_id=Yii::$app->user->identity->getId();
        $model->create_user_name=Yii::$app->user->identity->username;
        $model->create_at=date('Y-m-d H:i:s');
        $model->save();
    }

    /**
     * 获取用户信息
     */
    public function getMemberNameMobile(){
        return $this->hasOne(Member::className(),['id'=>'install_member_id'])->select('id,name,mobile');
    }

    /**
     * 获取今日收入
     */
    public function getMemberIncomePrice(){
        return $this->hasOne(MemberInstallSubsidy::className(),['id'=>'subsidy_id'])->select('id,income_price');
    }

}
