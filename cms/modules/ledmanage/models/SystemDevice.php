<?php

namespace cms\modules\ledmanage\models;

use cms\models\LogDevice;
use cms\models\SystemOffice;
use Yii;
use cms\modules\authority\models\User;
use common\libs\ToolsClass;
use yii\base\Exception;
use cms\modules\config\models\SystemConfig;
use cms\modules\member\models\Member;
/**
 * This is the model class for table "{{%system_device}}".
 *
 * @property string $device_number 设备编号
 * @property string $manufactor 厂家名称
 * @property string $batch 设备批次
 * @property int $gps 是否有GPS定位，默认 0 没有 1 有
 * @property string $receiving_at 收货日期
 * @property string $remark 备注
 * @property int $is_output 是否出库，默认 0未出库 1 已出库
 * @property int $status 设备状态 默认 0 离线 1 在线
 * @property string $create_at 添加时间
 */
class SystemDevice extends \yii\db\ActiveRecord
{
    public $receiving_at_end;
    public $create_at_end;
    public $goods_receipt_at_end;
    public $stock_out_at_end;
    public $in_manager_name;
    public $out_manager_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_device}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch', 'gps', 'is_output', 'status', 'is_delete', 'spec', 'storehouse', 'out_manager', 'in_manager'], 'integer'],
            [['receiving_at', 'manufactor', 'office_id', 'spec', 'storehouse', 'batch'], 'required','message'=>'请填写相关信息'],
            [['receiving_at', 'create_at', 'receiving_at_end', 'goods_receipt_at', 'stock_out_at','receive_member_id','in_manager_name','out_manager_name'], 'safe'],
            [['device_number', 'manufactor'], 'string', 'max' => 30],
            [['software_id'], 'string', 'max' => 100],
            [['device_number', 'software_id'], 'unique'],
            [['remark'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'device_number' => '硬件编号',
            'software_id' => '软件编号',
            'manufactor' => '厂家名称',
            'batch' => '批次',
            'gps' => 'GPS',
            'receiving_at' => '入库日期',
            'remark' => '备注',
            'is_output' => '是否出库',
            'status' => '状态',
            'create_at' => '入库日期',
            'spec' =>'规格',
            'storehouse' => '仓库',
            'goods_receipt_at' => '收货日期',
            'stock_out_at' => '出库日期',
            'out_manager' => '出库负责人',
            'in_manager' => '入库负责人',
            'is_delete' => '是否删除',
          //  'receiving_at_end' => '设备领取人',
            'receive_member_id' => '设备领取人',
        ];
    }
    /**
     * 添加多个设备到数据库
     */
    public function saveDevice(){
        $trans = Yii::$app->db->beginTransaction();
        $arr = $this->attributes;
        if(empty($arr)){
            return false;
        }
        $device = [];
        foreach ($arr['device_number'] as $k => $v){
            foreach ($arr['software_id'] as $kk => $vv){
                if($arr['device_number'][$k] && $arr['software_id'][$k]){
                    $device[$k]['device_number'] = $arr['device_number'][$k];
                    $device[$k]['software_id'] = $arr['software_id'][$k];
                }
            }
        }
        if(empty($device)){
            return false;
        }
        try{
            foreach ($device as $k => $v){
                if($v){
                    $model = new self();
                    $model->office_id = $this->office_id;
                    $model->manufactor = $this->manufactor;
                    $model->batch = $this->batch;
                    $model->gps = $this->gps;
                    $model->receiving_at = $this->receiving_at;
                    $model->remark = $this->remark;
                    $model->device_number = ToolsClass::trimall($v['device_number']);
                    $model->software_id = ToolsClass::trimall($v['software_id']);
                    //入库负责人
                    $model->in_manager = Yii::$app->user->identity->getId();
                    //规格
                    $model->spec = intval($this->spec);
                    //仓库
                    $model->storehouse = intval($this->storehouse);
                    $re = $model->save();
                    if(!$re){
                        $trans->rollBack();
                        return false;
                    }
                    $office = SystemOffice::find()->where(['id'=>$this->office_id])->asArray()->one();
                    LogDevice::addlog(ToolsClass::trimall($v['device_number']),$office,2,1);
                }
            }
            $trans->commit();
            return true;
        }catch (Exception $e){
            $trans->rollBack();
            return false;
        }
    }
    /**
     * 添加多个设备到数据库
     */
    public function changeDevice(){
        $trans = Yii::$app->db->beginTransaction();
        $arr = $this->attributes;
        if(empty($arr)){
            return false;
        }

        SystemDevice::updateAll(['office_id'=>$this->office_id,'batch'=>$this->batch,'receive_office_id'=>0,'is_output'=>0,'storehouse'=>$this->storehouse,'in_manager'=>Yii::$app->user->identity->getId(),'out_manager'=>0,'stock_out_at'=>'0000-00-00 00:00:00'],['device_number'=>$arr['device_number']]);
        if(empty($arr['device_number'])){
            return false;
        }
        try{
            foreach ($arr['device_number'] as $k => $v){
                if($v){
                    $office = SystemOffice::find()->where(['id'=>$this->office_id])->asArray()->one();
                    LogDevice::addlog(ToolsClass::trimall($v),$office,2,1);
                }
            }
            $trans->commit();
            return true;
        }catch (Exception $e){
            $trans->rollBack();
            return false;
        }

    }
    /*
     * 获取有无状态
     */
    public static function getIsHave($type,$number){
        $srr = [];
        switch ($type){
            case 'gps':
                $srr = [
                    '0' => '无',
                    '1' => '有',
                ];
                break;
            case 'is_output':
                $srr = [
                    '0' => '未出库',
                    '1' => '已出库',
                ];
                break;
            case 'is_delete':
                $srr = [
                    '0' => '已删除',
                    '1' => '正常',
                ];
                break;
            case 'status':
                $srr = [
                    '0' => '离线',
                    '1' => '在线',
                ];
                break;
            default:
                $srr = [];
        }
        return array_key_exists($number,$srr) ? $srr[$number] : '未设置';
    }
    /*
     * 获取厂家名称、规格、仓库名称
     */
    public static function getNamesByIndex($type, $index, $need_array = false,$kuid=0){
        switch ($type){
            case 'manufactor':
                $arr = (new self())->getManufactory();
                break;
            case 'spec':
                $arr = (new self())->getSpec();
                break;
            case 'storehouse':
                $arr = (new self())->getStorehouse($kuid);
                break;
            case 'receive_member_id':
                $arr = (new self())->receivememberid($index);
                break;
            default:
                $arr = [];
        }
        if($need_array){
            return $arr;
        }
        return array_key_exists($index,$arr) ? $arr[$index] : '未设置';
    }
    //获取厂家
    private function getManufactory(){
        $factory = SystemConfig::find()->where(['id'=>'manufactory'])->select('content')->asArray()->one();
        return $factory == true ? explode(',', $factory['content']) : [];
    }
    //获取规格
    private function getSpec(){
        $spec = SystemConfig::find()->where(['id'=>'led_spec'])->select('content')->asArray()->one();
        return $spec == true ? explode(',', $spec['content']) : [];
    }
    //获取系统仓库
    private function getStorehouse($kuid){
        $store = SystemOffice::find()->where(['id'=>$kuid])->select('id,storehouse')->asArray()->one();
        return $store == true ? explode(',', $store['storehouse']) : [];
    }
    //获取设备领取人
    private function membername($id){
        $store = Member::find()->where(['id'=>$id])->select('name')->asArray()->one();
        return $store['name']?$store['name']:'未设置';
    }
    /*
     * 处理数字变成文字
     */
    public function reformExport($column,$value){
        switch ($column){
            case 'gps':
                return $res = self::getIsHave('gps',$value);
                break;
            case 'is_output':
                return $res = self::getIsHave('is_output',$value);
                break;
            case 'status':
                return $res = self::getIsHave('status',$value);
                break;
            //出库负责人
            case 'out_manager':
                return User::getNameById($value);
                break;
            //入库负责人
            case 'in_manager':
                return User::getNameById($value);
                break;
            //是否删除
            case 'is_delete':
                return $res = self::getIsHave('is_delete',$value);
                break;
            //仓库
            case 'storehouse':
                return self::getNamesByIndex('storehouse',$value);
                break;
            //厂家
            case 'manufactor':
                return self::getNamesByIndex('manufactor',$value);
                break;
            //规格
            case 'led_spec':
                return self::getNamesByIndex('led_spec',$value);
                break;
            case 'receive_member_id':
                return self::membername($value);
                break;
            default :
                return $value."\t";
        }
    }

    //物流信息
    public static function getWlInfo($type, $code){
        $requestData= "{'OrderCode':'','ShipperCode':".$type.",'LogisticCode':".$code."}";
        $sign = self::encrypt($requestData,'');
        $url = 'https://www.kuaidi100.com/query?ak=00001&v=3.0&f=json&locale=zh_CN&postid='.$code.'&type='.$type.'&sign='.$sign;
        //echo $url;die;
        // 1. 初始化
        $ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        if($output === FALSE ){
            //echo "CURL Error:".curl_error($ch);
        }
        // 4. 释放curl句柄
        curl_close($ch);
        return $output;
    }
    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
     public static function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

    /*
     * 处理csv导出数据
     */
    public static function CsvData($data){
        $Csv = [];
        foreach($data['data'] as $k=>$v){
            $Csv[$k]['id']=$v['id'];
            $Csv[$k]['device_number']=$v['device_number']."\t";//硬件编号
            $Csv[$k]['software_id']=$v['software_id']."\t";//软件编号
            $Csv[$k]['manufactor']=array_key_exists($v['manufactor'],$data['config']['manufactory']) ? $data['config']['manufactory'][$v['manufactor']] : '未设置';//厂家名称
            if(!empty($v['offices'])){
                $Csv[$k]['office_name']=$v['offices']['office_name'];
                $Csv[$k]['storehouse']=array_key_exists($v['storehouse'],explode(',',$v['offices']['storehouse'])) ?explode(',',$v['offices']['storehouse'])[$v['storehouse']] : '未设置';//仓库
            }else{
                $Csv[$k]['office_name']='';
                $Csv[$k]['storehouse']='';
            }
//            $Csv[$k]['storehouse']=array_key_exists($v['storehouse'],$data['config']['storehouse']) ? $data['config']['storehouse'][$v['storehouse']] : '未设置';

            $Csv[$k]['spec']=array_key_exists($v['spec'],$data['config']['led_spec']) ? $data['config']['led_spec'][$v['spec']] : '未设置';//规格
            $Csv[$k]['gps']=SystemDevice::getIsHave('gps',$v['gps']);//GPS
            $Csv[$k]['create_at']=$v['create_at'];//入库时间
            if($v['in_manager']==0){//入库负责人
                $Csv[$k]['in_manager']='---';
            }else{
                if(empty($data['in_manager'])){
                    $Csv[$k]['in_manager']='---';
                }else{
                    if(isset($data['in_manager'][$v['in_manager']])){
                        $Csv[$k]['in_manager']=$data['in_manager'][$v['in_manager']];
                    }else{
                        $Csv[$k]['in_manager']='---';
                    }
                }
            }
            $Csv[$k]['stock_out_at']=$v['stock_out_at'];//出库时间
            if($v['out_manager']==0){//出库负责人
                $Csv[$k]['out_manager']='---';
            }else{
                if(empty($data['out_manager'])){
                    $Csv[$k]['out_manager']='---';
                }else{
                    if(isset($data['out_manager'][$v['out_manager']])){
                        $Csv[$k]['out_manager']=$data['out_manager'][$v['out_manager']];
                    }else{
                        $Csv[$k]['out_manager']='---';
                    }
                }
            }
            if($v['receive_member_id']==0){//设备领取人
                $Csv[$k]['receive_member_id']='---';
            }else{
                if(empty($data['receive_member_id'])){
                    $Csv[$k]['receive_member_id']='---';
                }else{
                    if(isset($data['receive_member_id'][$v['receive_member_id']])){
                        $Csv[$k]['receive_member_id']=$data['receive_member_id'][$v['receive_member_id']];
                    }else{
                        $Csv[$k]['receive_member_id']='---';
                    }
                }
            }
            $Csv[$k]['is_output']=SystemDevice::getIsHave('is_output',$v['is_output']);//是否出库
            $Csv[$k]['batch']=$v['batch'];//设备批次
            $Csv[$k]['remark']=$v['remark'];//设备批次
        }
        return $Csv;
    }

    /**
     * 获取库存数量
     * wpw
     */
    public static function isoutput($kuid=0){
        if(empty($kuid)){
            //在库数量
            $arr[0]=self::find()->where(['is_output'=>0])->count();
            //已出库的数量
            $arr[1]=self::find()->where(['is_output'=>1])->count();
        }else{
            //在库数量
            $arr[0]=self::find()->where(['is_output'=>0,'office_id'=>$kuid])->count();
            //已出库的数量
            $arr[1]=self::find()->where(['is_output'=>1,'office_id'=>$kuid])->count();
        }
        return $arr;
    }

    //获取办事处信息
    //获取厂家
    public static function SystemOfficeOne($id){
        $OfficeOne = SystemOffice::find()->where(['id'=>$id])->asArray()->one();
        return $OfficeOne == true ? $OfficeOne : ['office_name'=>'','storehouse'=>'storehouse'];
    }

    // 关联办事处表
    public function getOffices(){
        return $this->hasOne(SystemOffice::className(),['id'=>'office_id']);
    }
}
