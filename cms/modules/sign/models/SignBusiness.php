<?php

namespace cms\modules\sign\models;

use Yii;
use cms\modules\member\models\Member;
use cms\modules\member\models\MemberTeam;
/**
 * This is the model class for table "yl_sign_business".
 *
 * @property string $id
 * @property string $team_id 团队ID
 * @property string $member_id 签到人的用户ID
 * @property string $shop_name 店铺名称
 * @property string $shop_acreage 店铺面积
 * @property int $shop_mirror_number 店铺镜面数量
 * @property string $shop_address 店铺位置
 * @property string $longitude 经度
 * @property string $latitude 维度
 * @property int $minimum_charge 店铺最低消费
 * @property string $mobile 联系人店铺
 * @property int $shop_type 店铺类型(1、租赁 2、自营 3、连锁)
 * @property string $screen_brand_name 屏幕品牌名称
 * @property int $screen_number 屏幕数量
 * @property string $description 备注
 * @property int $frist_sign 是否是当天首次签到(1、是)
 * @property int $late_sign 是否超时签到(1、是)
 * @property string $create_at 签到时间
 */
class SignBusiness extends \yii\db\ActiveRecord
{
    public $totalmongo_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_business';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'shop_name', 'shop_address', 'longitude', 'latitude', 'screen_brand_name'], 'required'],
            [['team_id', 'member_id', 'shop_acreage', 'shop_mirror_number', 'minimum_charge', 'shop_type', 'screen_number', 'frist_sign', 'late_sign'], 'integer'],
            [['create_at'], 'safe'],
            [['shop_name'], 'string', 'max' => 100],
            [['shop_address', 'description'], 'string', 'max' => 200],
            [['longitude', 'latitude'], 'string', 'max' => 20],
            [['mobile'], 'string', 'max' => 11],
            [['screen_brand_name'], 'string', 'max' => 30],
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
            'member_id' => 'Member ID',
            'shop_name' => 'Shop Name',
            'shop_acreage' => 'Shop Acreage',
            'shop_mirror_number' => 'Shop Mirror Number',
            'shop_address' => 'Shop Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'minimum_charge' => 'Minimum Charge',
            'mobile' => 'Mobile',
            'shop_type' => 'Shop Type',
            'screen_brand_name' => 'Screen Brand Name',
            'screen_number' => 'Screen Number',
            'description' => 'Description',
            'frist_sign' => 'Frist Sign',
            'late_sign' => 'Late Sign',
            'create_at' => 'Create At',
        ];
    }

    //关联用户团队信息表获取团队名称
    public function getSignTeam(){
        return $this->hasOne(SignTeam::className(),['id'=>'team_id'])->select('id,team_name');
    }
}
