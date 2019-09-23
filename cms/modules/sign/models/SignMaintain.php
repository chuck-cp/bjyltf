<?php

namespace cms\modules\sign\models;

use Yii;

/**
 * This is the model class for table "yl_sign_maintain".
 *
 * @property string $id
 * @property string $team_id 团队ID
 * @property string $shop_id 店铺ID
 * @property string $area_id 店铺所在的地区ID
 * @property string $member_id 签到人的用户ID
 * @property string $member_name 签到到人员的姓名
 * @property string $shop_name 店铺名称
 * @property string $shop_address 店铺位置
 * @property string $longitude 经度
 * @property string $latitude 维度
 * @property string $contacts_name 店铺联系人姓名
 * @property string $contacts_mobile 店铺联系人电话
 * @property int $shop_type 店铺类型(1、租赁 2、自营 3、连锁)
 * @property string $maintain_content 维护内容
 * @property string $screen_start_at 设备开机时间
 * @property string $screen_end_at 设备关机时间
 * @property string $description 备注
 * @property int $frist_sign 是否是当天首次签到(1、是)
 * @property int $late_sign 是否超时签到(1、是)
 * @property int $evaluate 签到评价(1、好评 2、中评 3、差评)
 * @property string $create_at 签到时间
 */
class SignMaintain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_maintain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'member_name', 'shop_name', 'shop_address', 'longitude', 'latitude', 'contacts_name', 'contacts_mobile', 'maintain_content'], 'required'],
            [['team_id', 'shop_id', 'area_id', 'member_id', 'shop_type', 'frist_sign', 'late_sign', 'evaluate'], 'integer'],
            [['create_at'], 'safe'],
            [['member_name', 'contacts_name'], 'string', 'max' => 50],
            [['shop_name', 'maintain_content'], 'string', 'max' => 100],
            [['shop_address', 'description'], 'string', 'max' => 200],
            [['longitude', 'latitude'], 'string', 'max' => 20],
            [['contacts_mobile'], 'string', 'max' => 11],
            [['screen_start_at', 'screen_end_at'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => 'Team ID',
            'shop_id' => 'Shop ID',
            'area_id' => 'Area ID',
            'member_id' => 'Member ID',
            'member_name' => 'Member Name',
            'shop_name' => 'Shop Name',
            'shop_address' => 'Shop Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'contacts_name' => 'Contacts Name',
            'contacts_mobile' => 'Contacts Mobile',
            'shop_type' => 'Shop Type',
            'maintain_content' => 'Maintain Content',
            'screen_start_at' => 'Screen Start At',
            'screen_end_at' => 'Screen End At',
            'description' => 'Description',
            'frist_sign' => 'Frist Sign',
            'late_sign' => 'Late Sign',
            'evaluate' => 'Evaluate',
            'create_at' => 'Create At',
        ];
    }
    //关联用户团队信息表获取团队名称
    public function getSignTeam(){
        return $this->hasOne(SignTeam::className(),['id'=>'team_id'])->select('id,team_name');
    }

    //维护内容
    public static function MaintainContent($maintaincontent){
        $maintaincontentS=[
            '日常检查',
            '屏幕检修',
            '网络检查',
            '拷贝内容',
            '更新安装包',
            '调整设备开关机时间'
        ];
        foreach (explode(',',$maintaincontent) as $v){
            $maintaincontents[]= $maintaincontentS[$v-1];
        }
        if(!empty($maintaincontents))
            return implode(',',$maintaincontents);
        return '';
    }
}
