<?php

namespace cms\modules\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\MemberInstallSubsidy;

/**
 * MemberInstallSubsidySearch represents the model behind the search form of `cms\modules\member\models\MemberInstallSubsidy`.
 */
class MemberInstallSubsidySearch extends MemberInstallSubsidy
{
    public $name;
    public $mobile;
    public $province;
    public $city;
    public $area;
    public $town;
    public $income_price_at;
    public $income_price_end;
    public $type;
    public $js_create_at;
    public $js_create_end;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'install_member_id', 'install_shop_number', 'install_screen_number', 'assign_shop_number', 'assign_screen_number', 'income_price', 'subsidy_price'], 'integer'],
            [['create_at','name','mobile','province','city','area','town','income_price_at','income_price_end','type','js_create_at','js_create_end'], 'safe'],
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
        $query = MemberInstallSubsidy::find()->joinWith('memberArea')->joinWith('memberNameMobile');;

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
        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(yl_member_info.live_area_id,'.strlen($area).')' => $area]);
        }

        //每日收入搜索
        if($this->income_price_at){
            $query->andWhere(['>=','yl_member_install_subsidy.income_price',$this->income_price_at*100]);
        }
        if($this->income_price_end){
            $query->andWhere(['<=','yl_member_install_subsidy.income_price',$this->income_price_end*100]);
        }


        //按照时间搜索
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['yl_member_install_subsidy.create_at' => $this->create_at]);
        }else{
            if($this->type==1){
                //查询前一天的数据
                $query->andWhere(['yl_member_install_subsidy.create_at' => date('Y-m-d',strtotime('-1 day'))]);
            }
        }

        //结算中心的申请补贴日期搜索
        if($this->js_create_at){
            $query->andWhere(['>=','yl_member_install_subsidy.create_at',$this->js_create_at.' 00:00:00']);
        }
        if($this->js_create_end){
            $query->andWhere(['<=','yl_member_install_subsidy.create_at',$this->js_create_end.' 23:59:59']);
        }

        $query->andFilterWhere(['like', 'yl_member.mobile', $this->mobile])
            ->andFilterWhere(['like', 'yl_member.name', $this->name]);
        $query->orderBy('yl_member_install_subsidy.id desc');
        if($export==1){
            return $query;
        }
        return $dataProvider;
    }
}
