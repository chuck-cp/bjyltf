<?php

namespace cms\modules\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\shop\models\BuildingShopPark;

/**
 * BuildingShopParkSearch represents the model behind the search form of `cms\modules\shop\models\BuildingShopPark`.
 */
class BuildingShopParkSearch extends BuildingShopPark
{
    public $town;
    public $apply_name;
    public $company_name;
    public $contract_status;
    public $type;
    public $default_status;
    public $zhuangtai;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'company_id', 'area_id', 'led_screen_number', 'poster_screen_number', 'poster_install_member_id', 'poster_install_price', 'poster_last_examine_user_id', 'poster_examine_number', 'poster_examine_status', 'contract_id'], 'integer'],
            [['shop_name', 'shop_level', 'contact_name', 'contact_mobile', 'province', 'city', 'area', 'address', 'street', 'description', 'shop_image', 'plan_image', 'other_image', 'poster_create_at', 'install_finish_at', 'poster_install_member_name', 'poster_install_mobile', 'poster_install_finish_at', 'poster_examine_user_group', 'poster_examine_user_name', 'poster_examine_at','company_name','apply_name','contract_status','town'], 'safe'],
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
        $query = BuildingShopPark::find()->joinWith('buildingCompany')->joinWith('buildingShopContract');

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
        $area = max($this->province,$this->city,$this->area,$this->town);
        if(!empty($area)){
            $query->andWhere(['left(yl_building_shop_park.area_id,'.strlen($area).')' => $area]);
        }

        //认领显示
        if($this->default_status == 1){
            $query->andWhere([
                'yl_building_shop_park.poster_examine_status' => [0,1],
                'yl_building_shop_park.poster_examine_user_group' => '',
                'yl_building_shop_park.poster_examine_user_name' => '',
            ]);
        }else{
            if(Yii::$app->user->identity->member_group>0){
                $query->andFilterWhere(['yl_building_shop_park.poster_examine_user_group'=> Yii::$app->user->identity->member_group]);
            }
        }

        if($this->zhuangtai == 1){//公园审核
            $query->andWhere([
                'yl_building_shop_park.poster_examine_status' => [0,1,2],
            ]);
        }elseif($this->zhuangtai == 2){//公园安装
            $query->andWhere([
                'yl_building_shop_park.poster_examine_status' => [3,4,5],
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'yl_building_shop_park.id' => $this->id,
            'member_id' => $this->member_id,
            'yl_building_shop_park.poster_examine_status' => $this->poster_examine_status,
            'buildingShopContract.status' => $this->contract_status,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'yl_building_company.company_name', $this->company_name])
            ->andFilterWhere(['like', 'yl_building_company.apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'contact_mobile', $this->contact_mobile])

            ->andFilterWhere(['like', 'poster_examine_user_name', $this->poster_examine_user_name]);
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;

    }

}
