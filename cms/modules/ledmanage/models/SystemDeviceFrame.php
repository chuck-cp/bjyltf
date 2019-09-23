<?php

namespace cms\modules\ledmanage\models;

use cms\models\LogDevice;
use cms\models\SystemOffice;
use cms\modules\config\models\SystemConfig;
use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_system_device_frame".
 *
 * @property string $id
 * @property string $device_number 设备编号
 * @property string $device_size 设备尺寸
 * @property string $device_material 设备材质
 * @property string $device_level 设备品质(高、中、低)
 * @property string $office_id 所属办事处的ID
 * @property string $receive_office_id 配送到指定办事处的ID
 * @property string $manufactor 厂家名称
 * @property string $batch 设备批次
 * @property string $receiving_at 收货日期
 * @property string $remark 备注
 * @property int $is_output 是否出库，默认 0未出库 1 已出库
 * @property int $status 设备状态(1、正常 2、损坏)
 * @property string $create_at 添加时间
 * @property int $is_delete 0,删除 1，正常
 * @property int $spec 设备规格
 * @property string $goods_receipt_at 收货日期
 * @property string $stock_out_at 出库日期
 * @property int $storehouse 仓库
 * @property int $out_manager 出库负责人
 * @property int $in_manager 入库负责人
 * @property int $receive_member_id 设备领取人的ID
 */
class SystemDeviceFrame extends \yii\db\ActiveRecord
{
    public $create_at_end;
    public $stock_out_at_end;
    public $in_manager_name;
    public $out_manager_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_system_device_frame';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['office_id', 'nfc', 'receive_office_id', 'batch', 'is_output', 'status', 'is_delete', 'storehouse', 'out_manager', 'in_manager', 'receive_member_id'], 'integer'],
            [['receiving_at', 'manufactor','device_size','device_material','device_level','batch','storehouse'], 'required'],
            [['receiving_at', 'create_at', 'goods_receipt_at', 'stock_out_at'], 'safe'],
            [['device_number', 'manufactor'], 'string', 'max' => 30],
            [['device_size', 'device_material'], 'string', 'max' => 20],
            [['device_level'], 'string', 'max' => 2],
            [['remark'], 'string', 'max' => 120],
            [['device_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_number' => '设备编号',
            'device_size' => '设备尺寸',
            'device_material' => '设备材质',
            'device_level' => '设备品质',
            'office_id' => '所属办事处ID',
            'receive_office_id' => '配送到指定办事处ID',
            'manufactor' => '厂家名称',
            'batch' => '设备批次',
            'receiving_at' => '收货日期',
            'remark' => '备注',
            'is_output' => '是否出库',
            'status' => '设备状态',
            'create_at' => '添加时间',
            'is_delete' => 'Is Delete',
            'nfc' => '是否支持NFC',
            'goods_receipt_at' => '收货日期',
            'stock_out_at' => '出库日期',
            'storehouse' => '仓库',
            'out_manager' => '出库负责人',
            'in_manager' => '入库负责人',
            'receive_member_id' => '设备领取人ID',
        ];
    }

    //获取库存数量
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

    // 关联办事处表
    public function getOffices(){
        return $this->hasOne(SystemOffice::className(),['id'=>'office_id']);
    }

    /*
     * 获取厂家名称、尺寸、仓库名称
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
            case 'level':
                $arr = (new self())->getLevel();
                break;
            case 'material':
                $arr = (new self())->getMaterial();
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
        $factory = SystemConfig::find()->where(['id'=>'frame_device_manufactor'])->select('content')->asArray()->one();
        return $factory == true ? explode(',', $factory['content']) : [];
    }
    //获取尺寸
    private function getSpec(){
        $spec = SystemConfig::find()->where(['id'=>'frame_device_size'])->select('content')->asArray()->one();
        return $spec == true ? explode(',', $spec['content']) : [];
    }
    //获取品质
    private function getLevel(){
        $level = SystemConfig::find()->where(['id'=>'frame_device_level'])->select('content')->asArray()->one();
        return $level == true ? explode(',', $level['content']) : [];
    }
    //获取材质
    private function getMaterial(){
        $material = SystemConfig::find()->where(['id'=>'frame_device_material'])->select('content')->asArray()->one();
        return $material == true ? explode(',', $material['content']) : [];
    }
    //获取系统仓库
    private function getStorehouse($kuid){
        $store = SystemOffice::find()->where(['id'=>$kuid])->select('id,storehouse')->asArray()->one();
        return $store == true ? explode(',', $store['storehouse']) : [];
    }

    //获取有无状态
    public static function getIsHave($type,$number){
        $srr = [];
        switch ($type){
            case 'nfc':
                $srr = [
                    '1' => '支持',
                    '2' => '不支持',
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

    //添加多个设备到数据库
    public function changeDevice(){
        $trans = Yii::$app->db->beginTransaction();
        $arr = $this->attributes;
        if(empty($arr)){
            return false;
        }

        SystemDeviceFrame::updateAll(['office_id'=>$this->office_id,'batch'=>$this->batch,'receive_office_id'=>0,'is_output'=>0,'storehouse'=>$this->storehouse,'in_manager'=>Yii::$app->user->identity->getId(),'out_manager'=>0,'stock_out_at'=>'0000-00-00 00:00:00'],['device_number'=>$arr['device_number']]);
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
            if($arr['device_number'][$k]){
                $device[$k]['device_number'] = $arr['device_number'][$k];
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
                    $model->nfc = $this->nfc;
                    $model->receiving_at = $this->receiving_at;
                    $model->remark = $this->remark;
                    $model->device_number = ToolsClass::trimall($v['device_number']);
                    //入库负责人
                    $model->in_manager = Yii::$app->user->identity->getId();
                    //规格
                    $model->device_size = intval($this->device_size);
                    //材质
                    $model->device_material = intval($this->device_material);
                    //品质
                    $model->device_level = intval($this->device_level);
                    //仓库
                    $model->storehouse = intval($this->storehouse);
                    $re = $model->save(false);
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
            Yii::error($e->getMessage(),'error');
            $trans->rollBack();
            return false;
        }
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

    //获取办事处信息
    //获取厂家
    public static function SystemOfficeOne($id){
        $OfficeOne = SystemOffice::find()->where(['id'=>$id])->asArray()->one();
        return $OfficeOne == true ? $OfficeOne : ['office_name'=>'','storehouse'=>'storehouse'];
    }

    //处理csv导出数据
    public static function CsvData($data){
        $Csv = [];
        $Manufactory = (new self())->getManufactory();//厂家
        $Spec = (new self())->getSpec();//规格
        $Level = (new self())->getLevel();//等级
        $Material = (new self())->getMaterial();//材质
        foreach($data['data'] as $k=>$v){
            $Csv[$k]['id']=$v['id'];//序号
            $Csv[$k]['device_number']=$v['device_number']."\t";//硬件编号
            $Csv[$k]['manufactor']=$Manufactory[$v['manufactor']]? $Manufactory[$v['manufactor']] : '未设置';//厂家名称
            if(!empty($v['offices'])){
                $Csv[$k]['office_name']=$v['offices']['office_name'];//办事处
                $Csv[$k]['storehouse']=array_key_exists($v['storehouse'],explode(',',$v['offices']['storehouse'])) ?explode(',',$v['offices']['storehouse'])[$v['storehouse']] : '未设置';//仓库
            }else{
                $Csv[$k]['office_name']='';//办事处
                $Csv[$k]['storehouse']='';//仓库
            }
            $Csv[$k]['device_size']=$Spec[$v['device_size']] ? $Spec[$v['device_size']] : '未设置';//规格
            $Csv[$k]['device_material']=$Material[$v['device_material']] ? $Material[$v['device_material']] : '未设置';//材质
            $Csv[$k]['device_level']=$Level[$v['device_level']] ? $Level[$v['device_level']]  : '未设置';//品质
            $Csv[$k]['nfc']=SystemDeviceFrame::getIsHave('nfc',$v['nfc']);//NFC
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
}
