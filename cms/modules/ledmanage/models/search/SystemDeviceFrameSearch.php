<?php

namespace cms\modules\ledmanage\models\search;

use cms\models\User;
use cms\modules\config\models\SystemConfig;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\ledmanage\models\SystemDeviceFrame;
use yii\data\Pagination;
use cms\modules\config\models\SystemCostorehousesnfig;
use cms\modules\member\models\Member;


/**
 * SystemDeviceFrameSearch represents the model behind the search form of `cms\modules\ledmanage\models\SystemDeviceFrame`.
 */
class SystemDeviceFrameSearch extends SystemDeviceFrame
{
    public $storehouses;
    public $limit;
    public $office;
    public $receive_member_name;
    public $offset;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nfc', 'office_id', 'receive_office_id', 'batch', 'is_output', 'status', 'is_delete', 'storehouse', 'out_manager', 'in_manager', 'receive_member_id'], 'integer'],
            [['device_number', 'device_size', 'device_material', 'device_level', 'manufactor', 'receiving_at', 'remark', 'create_at', 'goods_receipt_at', 'stock_out_at','create_at_end','stock_out_at_end','storehouses','office','nfc'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$export=0)
    {
        $query = SystemDeviceFrame::find()->joinWith('offices');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'office_id' => $this->office_id,
            'receive_office_id' => $this->receive_office_id,
            'batch' => $this->batch,
            'receiving_at' => $this->receiving_at,
            'is_output' => $this->is_output,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'is_delete' => $this->is_delete,
            'goods_receipt_at' => $this->goods_receipt_at,
            'stock_out_at' => $this->stock_out_at,
            'storehouse' => $this->storehouse,
            'out_manager' => $this->out_manager,
            'in_manager' => $this->in_manager,
            'receive_member_id' => $this->receive_member_id,
            'manufactor' => $this->manufactor,
            'device_level' => $this->device_level,
            'device_size' => $this->device_size,
            'nfc' => $this->nfc,
        ]);

        //time search 入库
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','create_at',$this->create_at_end.' 23:59:59']);
        }
        //出库
        if(isset($this->stock_out_at) && $this->stock_out_at){
            $query->andWhere(['>=','stock_out_at',$this->stock_out_at.' 00:00:00']);
        }
        if(isset($this->stock_out_at_end) && $this->stock_out_at_end){
            $query->andWhere(['<=','stock_out_at',$this->stock_out_at_end.' 23:59:59']);
        }
        //收货
        if(isset($this->goods_receipt_at) && $this->goods_receipt_at){
            $query->andWhere(['>=','goods_receipt_at',$this->goods_receipt_at.' 00:00:00']);
        }
        if(isset($this->goods_receipt_at_end) && $this->goods_receipt_at_end){
            $query->andWhere(['<=','goods_receipt_at',$this->goods_receipt_at_end.' 23:59:59']);
        }
        //入库人
        if(isset($this->in_manager_name) && $this->in_manager_name){
            $in_names = User::find()->where(['like','username',$this->in_manager_name])->asArray()->all();
            $ids = array_column($in_names,'id');
            $query->andWhere(['in', 'in_manager',$ids]);
        }
        //出库人
        if(isset($this->out_manager_name) && $this->out_manager_name){
            $out_names = User::find()->where(['like','username',$this->out_manager_name])->asArray()->all();
            $ids = array_column($out_names,'id');
            $query->andWhere(['in', 'out_manager',$ids]);
        }
        //领取人
        if(isset($this->receive_member_id) && $this->receive_member_id){
            $moblies = Member::find()->where(['like','mobile',$this->receive_member_id])->asArray()->all();
            $ids = array_column($moblies,'id');
            $query->andWhere(['in', 'receive_member_id',$ids]);
        }
        //领取人姓名
        if(isset($this->receive_member_name) && $this->receive_member_name){
            $memberName = Member::find()->where(['like','name',$this->receive_member_name])->asArray()->all();
            $names = array_column($memberName,'id');
            $query->andWhere(['in', 'receive_member_id',$names]);
        }

        //办事处仓库搜索
        if($this->storehouses!=='' && isset($this->storehouses)){
            $query->andWhere(['=','office_id',$this->office]);
            $query->andWhere(['=','yl_system_device_frame.storehouse',$this->storehouses]);
        }elseif(!$this->storehouses  && $this->office){
            $query->andWhere(['=','office_id',$this->office]);
        }
        if($this->storehouse!=='' && isset($this->storehouse)){
            $query->andWhere(['=','office_id',$this->office_id]);
            $query->andWhere(['=','yl_system_device_frame.storehouse',$this->storehouse]);
        }

        $query->orderBy('id desc');
        $query->andFilterWhere(['like', 'device_number', $this->device_number])
            ->andFilterWhere(['like', 'device_material', $this->device_material]);

        if($export == 1){
            $arr['data'] = $query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }elseif($export == 2){
            return $query;
        }else{
            $arr['counts'] = $query->count();
            $arr['pages'] = new Pagination(['totalCount' =>$arr['counts'],'pageSize'=>'20']);
            $arr['data'] = $query->offset($arr['pages']->offset)->limit($arr['pages']->limit)->asArray()->all();
        }

        //获取厂家名称，仓库，规格
        $configAll=SystemConfig::find()->where(['in','id',['frame_device_level','frame_device_manufactor','frame_device_size','frame_device_material']])->select('id,content')->asArray()->all();
        foreach($configAll as $k=>$v){
            $arr['config'][$v['id']]=$v['content']? explode(',', $v['content']) : [];
        }

        //获取当前页的 入库负责人，出库负责人，设备领取人
        foreach($arr['data'] as $kcr=>$vcr){
            $cr['out_manager'][]=$vcr['out_manager']; //出库负责人
            $cr['in_manager'][]=$vcr['in_manager']; //入库负责人
            $cr['receive_member_id'][]=$vcr['receive_member_id']; //设备领取人
        }

        //出库负责人
        if(empty($cr['out_manager'])){
            $arr['out_manager']=[];
        }else{
            $out_manager=User::find()->where(['in','id',array_unique($cr['out_manager'])])->select('id,username')->asArray()->all();
            if(empty($out_manager)){
                $arr['out_manager']=[];
            }else{
                foreach($out_manager as $kc=>$vc){
                    $arr['out_manager'][$vc['id']]=$vc['username'];
                }
            }
        }

        //入库负责人
        if(empty($cr['in_manager'])){
            $arr['in_manager']=[];
        }else{
            $in_manager=User::find()->where(['in','id',array_unique($cr['in_manager'])])->select('id,username')->asArray()->all();
            if(empty($in_manager)){
                $arr['in_manager']=[];
            }else{
                foreach($in_manager as $kr=>$vr){
                    $arr['in_manager'][$vr['id']]=$vr['username'];
                }
            }
        }

        //设备领取人
        if(empty($cr['receive_member_id'])){
            $arr['receive_member_id']=[];
        }else{
            $receive_member_id=Member::find()->where(['in','id',array_unique($cr['receive_member_id'])])->select('id,name,mobile')->asArray()->all();
            if(empty($receive_member_id)){
                $arr['receive_member_id']=[];
            }else{
                foreach($receive_member_id as $kr=>$vr){
                    $arr['receive_member_id'][$vr['id']]=$vr['name'];
                }
            }
        }

        return $arr;
//        return $dataProvider;
    }
}
