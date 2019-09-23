<?php

namespace cms\modules\examine\models\search;

use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\examine\models\ShopContract;

/**
 * ShopContractSearch represents the model behind the search form of `cms\modules\examine\models\ShopContract`.
 */
class ShopContractSearch extends ShopContract
{
    public $shop_name;
    public $apply_name;
    public $member_name;
    public $status;
    public $shop_operate_type;
    public $create_at_end;
    public $company_name;
    public $headquarters_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'shop_type', 'examine_status'], 'integer'],
            [['contract_number', 'cabinet_number', 'receiver_name', 'description','shop_name','apply_name','member_name','status','shop_operate_type','create_at_end','company_name','headquarters_name'], 'safe'],
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
    public function search($params,$export = 0)
    {
        $query = ShopContract::find()->joinWith('shop')->joinWith('shopApply');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }
        if($this->status && isset($this->status)){
            $query->andWhere(['yl_shop.status'=>$this->status]);
        }
        if($this->shop_operate_type && isset($this->shop_operate_type)){
            $query->andWhere(['yl_shop.shop_operate_type'=>$this->shop_operate_type]);
        }
        if($this->examine_status && isset($this->examine_status)){
            if($this->examine_status==4){
                $query->andWhere(['yl_shop_contract.status'=>2]);
            }else{
                $query->andWhere(['yl_shop_contract.examine_status'=>$this->examine_status-1]);
                $query->andWhere(['yl_shop_contract.status'=>1]);
            }

        }

        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_shop_contract.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_shop_contract.create_at',$this->create_at_end.' 23:59:59']);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'yl_shop_contract.shop_type' => $this->shop_type,
        ]);
        $query->andFilterWhere(['like', 'yl_shop.name', $this->shop_name])
            ->andFilterWhere(['like', 'yl_shop.member_name', $this->member_name])
            ->andFilterWhere(['like', 'yl_shop_apply.apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'cabinet_number', $this->cabinet_number]);
        $query->orderBy('create_at desc');
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }
    //总部搜索
    public function headsearch($params,$export = 0)
    {
        $query = ShopContract::find()->joinWith('headquarters');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }
        if($this->examine_status && isset($this->examine_status)){
            if($this->examine_status==4){
                $query->andWhere(['yl_shop_contract.status'=>2]);
            }else{
                $query->andWhere(['yl_shop_contract.examine_status'=>$this->examine_status-1]);
                $query->andWhere(['yl_shop_contract.status'=>1]);
            }

        }

        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_shop_contract.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_shop_contract.create_at',$this->create_at_end.' 23:59:59']);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'shop_type' => $this->shop_type,
        ]);

        $query->andFilterWhere(['like', 'yl_shop_headquarters.company_name', $this->company_name])
            ->andFilterWhere(['like', 'yl_shop_headquarters.name', $this->headquarters_name])
            ->andFilterWhere(['like', 'yl_shop_headquarters.member_name', $this->member_name])
            ->andFilterWhere(['like', 'cabinet_number', $this->cabinet_number]);
        $query->orderBy('create_at desc');
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }
}
