<?php

namespace cms\modules\account\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\models\ScreenRunTimeShopSubsidy;

/**
 * ScreenRunTimeShopSubsidySearch represents the model behind the search form of `cms\models\ScreenRunTimeShopSubsidy`.
 */
class ScreenRunTimeShopSubsidySearch extends ScreenRunTimeShopSubsidy
{
    public $create_at_end;
    public $province;
    public $city;
    public $area;
    public $town;
    public $type;
    public $abnormal;
    public $offset;
    public $limit;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'screen_number', 'price', 'status', 'grant_status', 'date' ,'apply_id'], 'integer'],
            [['shop_name', 'area_name', 'apply_name', 'apply_mobile', 'create_at','create_at_end','province','city','area','town','type','abnormal', 'offset', 'limit'], 'safe'],
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
        $query = ScreenRunTimeShopSubsidy::find();

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
        if($this->type==2){
            $query->andWhere(['grant_status'=>1]);
        }
        if($this->abnormal==1){
            $query->andWhere('price=reduce_price');
        }
        if($this->abnormal==2){
            $query->andWhere('price<>reduce_price');
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'apply_id' => $this->apply_id,
            'screen_number' => $this->screen_number,
            'price' => $this->price,
            'grant_status' => $this->grant_status,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'area_name', $this->area_name])
            ->andFilterWhere(['like', 'apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'apply_mobile', $this->apply_mobile]);
        $query->orderBy('id desc');
        if($export==1){
            return $query;
        }else if($export==2){
            return $arr['data'] = $query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }
        //$commandQuery = clone $query;
        // echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
