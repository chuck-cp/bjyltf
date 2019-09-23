<?php

namespace cms\modules\account\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\account\models\MemberRewardDetail;

/**
 * MemberRewardDetailSearch represents the model behind the search form of `cms\modules\account\models\MemberRewardDetail`.
 */
class MemberRewardDetailSearch extends MemberRewardDetail
{
    public $create_at_end;
    public $shop_name;
    public $finish_at_end;
    public $apply_name;
    public $apply_mobile;
    public $province;
    public $city;
    public $area;
    public $town;
    public $search_shop_type;
    public $company_name;
    public $shop_member_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'reward_member_id', 'member_id', 'reward_price', 'order_price'], 'integer'],
            [['order_id', 'finish_at', 'create_at','create_at_end','shop_name','finish_at_end','apply_name','apply_mobile','province','city','area','town','search_shop_type','company_name','shop_member_id'], 'safe'],
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
    public function search($params,$export)
    {
        $query = MemberRewardDetail::find()->joinWith('rewardMember')->joinWith('shop')->joinWith('shopApply')->joinWith('shopHeadquarters');
//        ->select('yl_member_reward_detail.id as did,yl_member_reward_detail.reward_member_id,yl_member_reward_detail.reward_price,yl_member_reward_detail.order_id,yl_member_reward_detail.order_price,yl_member_reward_detail.finish_at,yl_member_reward_detail.create_at,yl_member_reward_member.id as rid,yl_member_reward_member.shop_id,yl_shop_apply.id,yl_shop_apply.apply_name,yl_shop_apply.apply_mobile,yl_shop.id,yl_shop.area,yl_shop.area_name');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_member_reward_detail.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_member_reward_detail.create_at',$this->create_at_end.' 23:59:59']);
        }
        if(isset($this->finish_at) && $this->finish_at){
            $query->andWhere(['>=','yl_member_reward_detail.finish_at',$this->finish_at.' 00:00:00']);
        }
        if(isset($this->finish_at_end) && $this->finish_at_end){
            $query->andWhere(['<=','yl_member_reward_detail.finish_at',$this->finish_at_end.' 23:59:59']);
        }
        if(isset($this->shop_name) && $this->shop_name){
            $query->andWhere(['like', 'yl_member_reward_member.shop_name', $this->shop_name]);
        }
        if(isset($this->search_shop_type) && $this->search_shop_type){
            $query->andWhere([ 'yl_member_reward_detail.shop_type'=>$this->search_shop_type]);
        }
        if($this->search_shop_type==1){
            $query->andFilterWhere(['yl_shop.shop_member_id' => $this->shop_member_id,]);
            //按地区搜索
            $area = max($this->province,$this->city,$this->area,$this->town);
            if($area){
                $query->andWhere(['left(yl_shop.area,'.strlen($area).')' => $area]);
            }
            $query->andFilterWhere(['like', 'yl_shop_apply.apply_name', $this->apply_name])
                ->andFilterWhere(['like', 'yl_shop_apply.apply_mobile', $this->apply_mobile]);
        }elseif($this->search_shop_type==2){
            $query->andFilterWhere(['yl_shop_headquarters.corporation_member_id' => $this->shop_member_id,]);
            $area = max($this->province,$this->city,$this->area,$this->town);
            if($area){
                $query->andWhere(['left(yl_shop_headquarters.company_area_id,'.strlen($area).')' => $area]);
            }
            $query->andFilterWhere(['like', 'yl_shop_headquarters.name', $this->apply_name])
                ->andFilterWhere(['like', 'yl_shop_headquarters.company_name', $this->company_name])
                ->andFilterWhere(['like', 'yl_shop_headquarters.mobile', $this->apply_mobile]);
        }
        $query->orderBy('finish_at desc');
       // $commandQuery = clone $query;
        //echo $commandQuery->createCommand()->getRawSql();

        // return $query;
        if($export==1){
            return $query;
        }
        return $dataProvider;
    }
}
