<?php

namespace cms\modules\member\models;

use cms\modules\config\models\MemberShopApplyRank;
use cms\modules\sign\models\SignTeamMember;
use Yii;
use cms\models\SystemAddress;
use common\libs\ToolsClass;
/**
 * This is the model class for table "{{%member}}".
 *
 * @property string $id
 * @property string $number
 * @property string $parent_number
 * @property string $parent_list
 * @property string $admin_area
 * @property integer $member_type
 * @property string $name
 * @property string $avatar
 * @property string $mobile
 * @property string $school
 * @property string $education
 * @property string $area
 * @property string $address
 * @property string $emergency_contact_name
 * @property string $emergency_contact_mobile
 * @property string $emergency_contact_relation
 * @property integer $status
 * @property string $create_at
 * @property string $update_at
 */
class Member extends \yii\db\ActiveRecord
{
    public $count_price;
    public $examine_status;
    public $province;
    public $city;
    public $town;
    public $admin_screen_number;
    public $admin_shop_number;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mobile'], 'required'],
            [['id', 'admin_area', 'member_type', 'status' ,'examine_status'], 'integer'],
            [['create_at', 'update_at','count_price', 'town'], 'safe'],
            [['emergency_contact_mobile'], 'string', 'max' => 20],
            [['parent_list', 'school'], 'string', 'max' => 100],
            [['name', 'emergency_contact_name'], 'string', 'max' => 50],
            [['avatar', 'address'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 11],
            [['education'], 'string', 'max' => 3],
            [['parent_id'], 'integer'],
            [['emergency_contact_relation'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_area' => '业务区域',
            'member_type' => '用户类型',
            'name' => '姓名',
            'avatar' => '头像',
            'mobile' => '联系电话',
            'school' => '毕业学校',
            'education' => '学历',
            'area' => '所属地区ID',
            'area_name' => '所属地区',
            'address' => '街道地址',
            'emergency_contact_name' => '紧急联系人姓名',
            'emergency_contact_mobile' => '紧急联系人电话',
            'emergency_contact_relation' => '紧急联系人关系',
            'status' => '状态',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'count_price' => 'count_price',
            'examine_status' => 'examine_status',
            'parent_id' => 'parent id',
        ];
    }
    /**
     * 获取联系人地址
     */
    public function getMemberAddress(){
        return $this->hasOne(SystemAddress::className(),['id'=>'area']);
    }
    /**
     * 关联查询获取用户的收益金额、店铺总数、装屏总数
     */
    public function getMemCount(){
        return $this->hasOne(MemberAccount::className(),['member_id'=>'id']);
    }
    /**
     * 根据工号获得上级信息
     */
    public function getMemByNumber($number){
        if(!$number){
            return ['id'=>0,'name'=>'---'];
        }
        $obj = self::find()->where(['id'=>$number]);
        return $obj ==true ? $obj->select('id,name')->asArray()->one() : ['id'=>0,'name'=>'---'];
    }
    /**
     * 获得用户身份证信息
     */
    public function getMemIdcardInfo(){
        return $this->hasOne(MemberInfo::className(),['member_id'=>'id']);//->select('member_id, name, id_number, id_front_image, id_back_image, id_hand_image,examine_status')
    }
    /**
     * 获取账户信息
     */
    public function getMemberAccount(){
        return $this->hasOne(MemberAccount::className(),['member_id'=>'id']);
    }
    /**
     * 根据id获得用户名
     */
    public static function getNameById($id,$column){
        if(!$id || !$column){
            return '---';
        }else{
            return self::findOne(['id'=>$id]) == true ? self::findOne(['id'=>$id])->getAttribute($column) : '---';
        }
    }

    /**
     * 根据id获取用户电话
     */
    public static function getMobileById($id,$column){
        if(!$id || !$column){
            return '---';
        }else{
            return self::findOne(['id'=>$id]) == true ? self::findOne(['id'=>$id])->getAttribute($column) : '---';
        }
    }
    /**
     * 获取系统中业务员的所有的业务区域
     */
    public static function getSystemAdminArea(){
        $adminArea = self::find()->where(['>', 'admin_area', '0'])->select('admin_area')->groupBy('admin_area')->asArray()->all();
        if(!empty($adminArea)){
            $areas = [];
            foreach ($adminArea as $k => $v){
                $areas[$v['admin_area']] = SystemAddress::getAreaByIdLen($v['admin_area'],strlen($v['admin_area']));
            }
            return json_encode($areas);
        }else{
            return json_encode([]);
        }
    }

    /**
     *  导出数据处理
     */
    public static function ExportCsv($Data){
        foreach ($Data as $k=>$v){
            $Csv[$k]['member_id']=$v['member']['id'];//序号
            $Csv[$k]['name']=$v['member']['name'];//姓名
            $Csv[$k]['mobile']=$v['member']['mobile'];//联系电话
            $Csv[$k]['area_name']=$v['member']['area_name'];//所属地区
            $Csv[$k]['admin_area']=SystemAddress::getAreaByIdLen($v['member']['admin_area'],9);//业务区域
            $Csv[$k]['shop_number']=$v['totalshop'];//已安装商家数量
            $Csv[$k]['screen_number']=$v['totalscreen'];//已安装LED数量
            $Csv[$k]['wait_shop_number']=$v['memberShopApplyRank']['wait_install_shop_number'];//待安装商家数量
            $Csv[$k]['wait_screen_number']=$v['memberShopApplyRank']['wait_install_screen_number'];//待安装LED数量
        }
        return $Csv;
    }

    /**
     * 人员查询数据导出
     */

    public static function CsvExport($CavArr){
        foreach ($CavArr as $k=>$v){
            $Csv[$k]['id']=$v['id'];//序号
            $Csv[$k]['name']=$v['name'];//姓名
            $Csv[$k]['id_number']=$v['memIdcardInfo']['id_number']."\t";//身份证号
            $Csv[$k]['mobile']=$v['mobile'];//手机
            $Csv[$k]['area']=SystemAddress::getAreaNameById($v['area']);//所属地区
            $Csv[$k]['admin_area']=SystemAddress::getAreaByIdLen($v['admin_area'],9);//业务区域
            $Csv[$k]['count_price']=ToolsClass::priceConvert($v['memberAccount']['count_price']);//收益总额
            $Csv[$k]['shop_number']=$v['memberAccount']['shop_number']?$v['memberAccount']['shop_number']:0;//联系店家数量
            $Csv[$k]['screen_number']=$v['memberAccount']['screen_number']?$v['memberAccount']['screen_number']:0;//联系LED数量
            $Csv[$k]['install_shop_number']=$v['memberAccount']['install_shop_number']?$v['memberAccount']['install_shop_number']:0;//安装商家数量
            $Csv[$k]['install_screen_number']=$v['memberAccount']['install_screen_number']?$v['memberAccount']['install_screen_number']:0;//安装LED数量
            $Csv[$k]['inside']=$v['inside']==1?'是':'否';//是否为内部人员
            $Csv[$k]['electrician_examine_status']=$v['memIdcardInfo']['electrician_examine_status']==1?'是':'否';//是否为电工
            $Csv[$k]['company_electrician']=$v['memIdcardInfo']['company_electrician']==1?'是':'否';//是否为内部电工
            //是否为合作推广人
            if($v['inside']==1){
                $Csv[$k]['inviter'] = '否';
            }else{
                if($v['parent_id']>0){
                    $Csv[$k]['inviter'] = '是';
                }else{
                    $Csv[$k]['inviter'] = '否';
                }
            }
        }
        return $Csv;
    }
    //设置、取消签到管理员时，同时设置该人员在团队里为普通成员or管理员
    public static function getSignAdmin($id,$sign)
    {
        $res = self::updateAll(['sign_team_admin'=>$sign],['id'=>$id]);
        $signMember = SignTeamMember::findOne(['member_id'=>$id]);
        if($signMember){
            if($sign == 1){
                $signMember->member_type = 3;//管理员
            }else{
                $signMember->member_type = 1;//普通成员
            }
            $signMember->save(false);
        }
        return $res;
    }

    /**
     * 获取人员总安装信息
     */
    public function getMemberCount(){
        return $this->hasOne(MemberShopCount::className(),['member_id'=>'id'])->select('member_id,admin_screen_number,admin_shop_number');
    }

    // 获取业务员联系店铺的每日安装数量统
    public function getMemberShopApplyCount(){
        return $this->hasMany(MemberShopApplyCount::className(),['member_id'=>'id']);
    }
    // 获取业务员联系店铺的每日安装数量统
    public function getMemberShopApplyRank(){
        return $this->hasOne(MemberShopApplyRank::className(),['member_id'=>'id']);
    }

    public static function getAreaName($member_id){
       $model = Member::findOne(['id'=>$member_id]);
       if($model){
           return $model->area_name.$model->address;
       }else{
           return '';
       }
    }


}
