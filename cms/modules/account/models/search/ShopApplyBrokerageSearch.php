<?php

namespace cms\modules\account\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\account\models\ShopApplyBrokerage;

/**
 * ShopApplyBrokerageSearch represents the model behind the search form of `cms\modules\account\models\ShopApplyBrokerage`.
 */
class ShopApplyBrokerageSearch extends ShopApplyBrokerage
{
    public $province;
    public $city;
    public $area;
    public $town;
    public $create_at_end;
    public $offset;
    public $limit;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'area_id', 'apply_id', 'screen_number', 'mirror_number', 'price', 'grant_status', 'date'], 'integer'],
            [['shop_name', 'area_name', 'address', 'apply_name', 'apply_mobile', 'create_at', 'create_at_end', 'install_finish_at', 'province', 'city', 'area', 'town', 'offset', 'limit'], 'safe'],
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
        $query = ShopApplyBrokerage::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'area_id' => $this->area_id,
            'apply_id' => $this->apply_id,
            'screen_number' => $this->screen_number,
            'mirror_number' => $this->mirror_number,
            'price' => $this->price,
            'grant_status' => $this->grant_status,
            'date' => $this->date,
            'create_at' => $this->create_at,
            'install_finish_at' => $this->install_finish_at,
        ]);

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

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'area_name', $this->area_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'apply_mobile', $this->apply_mobile]);

        if($export==1){
            return $query;
        }else if($export==2){
            return $arr['data'] = $query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }

        return $dataProvider;
    }
}
