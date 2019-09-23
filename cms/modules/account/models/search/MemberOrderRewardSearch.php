<?php

namespace cms\modules\account\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\account\models\MemberOrderReward;

/**
 * MemberOrderRewardSearch represents the model behind the search form of `cms\modules\account\models\MemberOrderReward`.
 */
class MemberOrderRewardSearch extends MemberOrderReward
{
    public $create_at_end;
    public $apply_name;
    public $apply_mobile;
    public $province;
    public $city;
    public $area;
    public $town;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'order_price', 'reward_price', 'external_shop_id', 'shop_id', 'area_id'], 'integer'],
            [['order_id', 'order_create_at', 'goods_name', 'external_shop_name', 'shop_name', 'area_name', 'create_at', 'create_at_end','apply_name','apply_mobile','province','city','area','town'], 'safe'],
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
        $query = MemberOrderReward::find()->joinWith('shopApply');

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
            $query->andWhere(['>=','create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','create_at',$this->create_at_end.' 23:59:59']);
        }

        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(area_id,'.strlen($area).')' => $area]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            'order_create_at' => $this->order_create_at,
            'order_price' => $this->order_price,
            'reward_price' => $this->reward_price,
            'external_shop_id' => $this->external_shop_id,
            'shop_id' => $this->shop_id,
            'area_id' => $this->area_id,
        ]);

        $query->andFilterWhere(['like', 'goods_name', $this->goods_name])
            ->andFilterWhere(['like', 'yl_shop_apply.apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'yl_shop_apply.apply_mobile', $this->apply_mobile])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name]);
        if($export==1){
            return $query;
        }
        $query->orderBy('id desc');
        return $dataProvider;
    }
}
