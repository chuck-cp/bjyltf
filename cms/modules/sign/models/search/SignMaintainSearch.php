<?php

namespace cms\modules\sign\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignMaintain;

/**
 * SignMaintainSearch represents the model behind the search form of `cms\modules\sign\models\SignMaintain`.
 */
class SignMaintainSearch extends SignMaintain
{

    public $team_name;
    public $province;
    public $city;
    public $area;
    public $town;
    public $create_at_end;
    public $date;
    public $screen;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'shop_id', 'area_id', 'member_id', 'shop_type', 'frist_sign', 'late_sign', 'evaluate'], 'integer'],
            [['member_name', 'shop_name', 'shop_address', 'longitude', 'latitude', 'contacts_name', 'contacts_mobile', 'maintain_content', 'screen_start_at', 'screen_end_at', 'description', 'create_at','team_name','province','city','area','town','create_at_end','date','screen'], 'safe'],
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
    public function search($params)
    {
        $query = SignMaintain::find();

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
        if($this->evaluate==4){
            $query->andWhere(['evaluate'=>0]);
        }elseif($this->evaluate>=1&&$this->evaluate<4){
            $query->andWhere(['evaluate'=>$this->evaluate]);
        }

        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_maintain.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_maintain.create_at',$this->create_at_end.' 23:59:59']);
        }
        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(area_id,'.strlen($area).')' => $area]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'team_id' => $this->team_id,
            'shop_id' => $this->shop_id,
            'shop_type' => $this->shop_type,
        ]);

        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'shop_address', $this->shop_address]);
        $query->orderBy('id desc');
        return $dataProvider;
    }
    public function maintain_list_search($params)
    {
        $query = SignMaintain::find()->joinWith('signTeam');

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
        if($this->date){
            $this->create_at=$this->date;
            $this->create_at_end=$this->date;
        }
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_maintain.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_maintain.create_at',$this->create_at_end.' 23:59:59']);
        }
        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(area_id,'.strlen($area).')' => $area]);
        }

        if($this->frist_sign==1){
            $query->andWhere(['frist_sign'=>1]);
        }elseif ($this->frist_sign==2){
            $query->andWhere(['frist_sign'=>0]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'team_id' => $this->team_id,
            'shop_id' => $this->shop_id,
            'shop_type' => $this->shop_type,
        ]);

        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'shop_address', $this->shop_address]);
        $query->orderBy('id desc');
        return $dataProvider;
    }
}
