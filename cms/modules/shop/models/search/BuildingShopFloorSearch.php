<?php

namespace cms\modules\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\shop\models\BuildingShopFloor;

/**
 * BuildingShopFloorSearch represents the model behind the search form of `cms\modules\shop\models\BuildingShopFloor`.
 */
class BuildingShopFloorSearch extends BuildingShopFloor
{
    public $town;
    public $apply_name;
    public $company_name;
    public $contract_status;
    public $led_examine_end;
    public $poster_examine_end;
    public $led_install_finish_end;
    public $contract_examine_at;
    public $contract_examine_end;
    public $poster_install_finish_end;
    public $type;
    public $default_status;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'member_id', 'shop_level', 'floor_type', 'floor_number', 'low_floor_number', 'area_id', 'led_screen_number', 'poster_screen_number', 'led_install_member_id', 'led_install_price', 'led_last_examine_user_id', 'led_examine_number', 'led_examine_status', 'poster_install_member_id', 'poster_install_price', 'poster_last_examine_user_id', 'poster_examine_number', 'poster_examine_status'], 'integer'],
            [['shop_name', 'contact_name', 'contact_mobile', 'province', 'city', 'area', 'address', 'street', 'description', 'shop_image', 'plan_image', 'floor_image', 'other_image', 'screen_start_at', 'screen_end_at', 'led_create_at', 'poster_create_at', 'led_install_member_name', 'led_install_mobile', 'led_install_finish_at', 'led_examine_user_group', 'led_examine_user_name', 'poster_install_member_name', 'poster_install_mobile', 'poster_install_finish_at', 'poster_examine_user_group', 'poster_examine_user_name','town','apply_name','company_name','contract_status','led_examine_end','poster_examine_end','led_install_finish_end','contract_examine_at','contract_examine_end','led_examine_at','poster_examine_at','poster_install_finish_end','type'], 'safe'],
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
        $query = BuildingShopFloor::find()->joinWith('buildingCompany')->joinWith('buildingShopContract');

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

        if($this->type == 1){
            $query->andWhere(['>','yl_building_shop_floor.led_total_screen_number',0]);
        }else if($this->type == 2){
            $query->andWhere(['>','yl_building_shop_floor.poster_total_screen_number',0]);
        }else if($this->type == 3){
            $query->andWhere(['>','yl_building_shop_floor.led_total_screen_number',0]);
            $query->andWhere(['yl_building_shop_floor.led_examine_status'=>[0,1,2]]);
        }else if($this->type == 4){
            $query->andWhere(['>','yl_building_shop_floor.poster_total_screen_number',0]);
            $query->andWhere(['yl_building_shop_floor.led_examine_status'=>[0,1,2]]);
        }else if($this->type == 5){
            $query->andWhere(['>','yl_building_shop_floor.poster_total_screen_number',0]);
            $query->andWhere(['yl_building_shop_floor.led_examine_status'=>[3,4,5]]);
        }else if($this->type == 6){
            $query->andWhere(['>','yl_building_shop_floor.poster_total_screen_number',0]);
            $query->andWhere(['yl_building_shop_floor.led_examine_status'=>[3,4,5]]);
        }

        $area = max($this->province,$this->city,$this->area,$this->town);
        if(!empty($area)){
            $query->andWhere(['left(yl_building_shop_floor.area_id,'.strlen($area).')' => $area]);
        }

        //LED审核通过时间
        if(isset($this->led_examine_at) && $this->led_examine_at){
            $query->andWhere(['>=','yl_building_shop_floor.led_examine_at',$this->led_examine_at.' 00:00:00']);
        }
        if(isset($this->led_examine_end) && $this->led_examine_end){
            $query->andWhere(['<=','yl_building_shop_floor.led_examine_at',$this->led_examine_end.' 23:59:59']);
        }

        //LED安装完成时间
        if(isset($this->led_install_finish_at) && $this->led_install_finish_at){
            $query->andWhere(['>=','yl_building_shop_floor.led_install_finish_at',$this->led_install_finish_at.' 00:00:00']);
        }
        if(isset($this->led_install_finish_end) && $this->led_install_finish_end){
            $query->andWhere(['<=','yl_building_shop_floor.led_install_finish_at',$this->led_install_finish_end.' 23:59:59']);
        }

        //LED合同审核通过时间
        if(isset($this->contract_examine_at) && $this->contract_examine_at){
            $query->andWhere(['>=','yl_building_shop_contract.examine_at',$this->led_install_finish_at.' 00:00:00']);
        }
        if(isset($this->contract_examine_end) && $this->contract_examine_end){
            $query->andWhere(['<=','yl_building_shop_contract.examine_at',$this->contract_examine_end.' 23:59:59']);
        }


        //海报审核通过时间
        if(isset($this->poster_examine_at) && $this->poster_examine_at){
            $query->andWhere(['>=','yl_building_shop_floor.poster_examine_at',$this->poster_examine_at.' 00:00:00']);
        }
        if(isset($this->poster_examine_end) && $this->poster_examine_end){
            $query->andWhere(['<=','yl_building_shop_floor.poster_examine_at',$this->poster_examine_end.' 23:59:59']);
        }

        //海报安装完成时间
        if(isset($this->poster_install_finish_at) && $this->poster_install_finish_at){
            $query->andWhere(['>=','yl_building_shop_floor.poster_install_finish_at',$this->poster_install_finish_at.' 00:00:00']);
        }
        if(isset($this->poster_install_finish_end) && $this->poster_install_finish_end){
            $query->andWhere(['<=','yl_building_shop_floor.poster_install_finish_at',$this->poster_install_finish_end.' 23:59:59']);
        }

        //认领
        if($this->default_status == 1){//led
            $query->andWhere([
                'yl_building_shop_floor.led_examine_status' => [0,1],
                'yl_building_shop_floor.led_examine_user_group' => '',
                'yl_building_shop_floor.led_examine_user_name' => '',
            ]);
        }elseif($this->default_status == 2){//poster
            $query->andWhere([
                'yl_building_shop_floor.poster_examine_status' => [0,1],
                'yl_building_shop_floor.poster_examine_user_group' => '',
                'yl_building_shop_floor.poster_examine_user_name' => '',
            ]);
//        }else{
//            if(Yii::$app->user->identity->member_group>0){
//                $query->andFilterWhere(['yl_building_shop_floor.examine_user_group'=> Yii::$app->user->identity->member_group]);
//            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'yl_building_shop_floor.id' => $this->id,
            'contact_mobile' => $this->contact_mobile,
            'yl_building_shop_floor.floor_type' => $this->floor_type,
            'yl_building_shop_contract.status' => $this->contract_status,
            'yl_building_shop_floor.led_examine_status' => $this->led_examine_status,
            'yl_building_shop_floor.poster_examine_status' => $this->poster_examine_status,

        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'yl_building_company.company_name', $this->company_name])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'yl_building_company.apply_name', $this->apply_name]);
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
