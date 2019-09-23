<?php

namespace cms\modules\sign\models\search;

use cms\models\SystemAddress;
use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignBusiness;

/**
 * SignBusinessSearch represents the model behind the search form of `cms\modules\sign\models\SignBusiness`.
 */
class SignBusinessSearch extends SignBusiness
{
    public $province;
    public $city;
    public $area;
    public $town;
    public $screen;
    public $member_name;
    public $team_name;
    public $create_at_end;
    public $date;
    public $mongo_ids;
    public $RepeatShop;
    public $totalmongo_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'member_id', 'shop_acreage', 'shop_mirror_number', 'minimum_charge', 'shop_type', 'screen_number', 'frist_sign', 'late_sign'], 'integer'],
            [['shop_name', 'shop_address', 'longitude', 'latitude', 'mobile', 'screen_brand_name', 'description', 'create_at','province','city','area','town','screen','member_name','team_name','create_at_end','mongo_ids','RepeatShop','totalmongo_id'], 'safe'],
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
        $query = SignBusiness::find()->joinWith('signTeam');

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
        if($this->screen==2){
            $query->andWhere(['=','screen_number',0]);
        }elseif ($this->screen==1){
            $query->andWhere(['<>','screen_number',0]);
        }
        if($this->date){
            $this->create_at = $this->date;
            $this->create_at_end = $this->date;
        }
        if(!empty($this->mongo_ids)){
            $query->andWhere(['in','yl_sign_business.mongo_id',$this->mongo_ids]);
        }
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_business.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_business.create_at',$this->create_at_end.' 23:59:59']);
        }
        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $area_name=SystemAddress::find()->where(['id'=>$area])->select('name')->asArray()->one()['name'];
        }
        if(strlen($area)==5){
            $query->andWhere(['province'=>$area_name]);
        }else if(strlen($area)==7){
            $query->andWhere(['city'=>$area_name]);
        }else if(strlen($area)==9){
            $query->andWhere(['area'=>$area_name]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'team_id' => $this->team_name,
            'member_id' => $this->member_id,
            'shop_acreage' => $this->shop_acreage,
            'shop_mirror_number' => $this->shop_mirror_number,
            'minimum_charge' => $this->minimum_charge,
            'shop_type' => $this->shop_type,
            'frist_sign' => $this->frist_sign,
            'late_sign' => $this->late_sign,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'shop_address', $this->shop_address])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'screen_brand_name', $this->screen_brand_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'member_name', $this->member_name]);
        if($this->RepeatShop && $this->RepeatShop==1){
           // $query->select('yl_member_shop_apply_count.*,sum(yl_member_shop_apply_count.shop_number) as totalshop,sum(yl_member_shop_apply_count.screen_number) as totalscreen')->orderBy('totalshop desc');
            $query->select('yl_sign_business.*,count(yl_sign_business.mongo_id) as totalmongo_id')->groupBy('mongo_id');
        }else{
            $query->orderBy('create_at desc');
        }
        $commandQuery = clone $query;
       // echo $commandQuery->createCommand()->getRawSql();
        //  return $query;
        return $dataProvider;
    }
}
