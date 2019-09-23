<?php

namespace cms\modules\examine\models\search;

use cms\modules\member\models\Member;
use cms\modules\member\models\MemberInfo;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\examine\models\ShopHeadquarters;

/**
 * ShopHeadSearch represents the model behind the search form of `cms\modules\examine\models\ShopHeadquarters`.
 */
class ShopHeadSearch extends ShopHeadquarters
{
    /**
     * @inheritdoc
     */
    public $member_name;
    public $branch_shop_name;
    public function rules()
    {
        return [
            [['id', 'member_id', 'company_area_id','examine_status'], 'integer'],
            [['name', 'mobile', 'identity_card_num', 'identity_card_front', 'identity_card_back', 'company_name', 'company_area_name', 'company_address', 'registration_mark', 'business_licence', 'agreement_name', 'create_at','province','city','area','town','member_name','agreed','branch_shop_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = ShopHeadquarters::find()->joinWith('headquartersList')->groupBy('yl_shop_headquarters.id');

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
            $query->andWhere(['left(company_area_id,'.strlen($area).')' => $area]);
        }
        //业务人员姓名
        if($this->member_name){
            $memberid = MemberInfo::find()->where(['like','yl_member_info.name',$this->member_name])->select('member_id,name')->asArray()->all();
            if(!empty($memberid)){
                $mid = array_column($memberid,'member_id');
                $query->andFilterWhere(['in', 'member_id', $mid]);
            }else{
                $query->andFilterWhere(['member_id' => 0]);
            }
        }
        $query->andFilterWhere([
            'yl_shop_headquarters.id' => $this->id,
//            'create_at' => $this->create_at,
            'yl_shop_headquarters.examine_status' => $this->examine_status,
        ]);
        if($this->agreed==1){
            $query->andWhere(['yl_shop_headquarters.agreed'=>1]);
        }elseif($this->agreed==2){
            $query->andWhere(['yl_shop_headquarters.agreed'=>0]);
        }else{
            $query->andWhere(['yl_shop_headquarters.agreed'=>[0,1]]);
        }
        $query->andFilterWhere(['like', 'yl_shop_headquarters.name', $this->name])
            ->andFilterWhere(['like', 'yl_shop_headquarters.mobile', $this->mobile])
            ->andFilterWhere(['like', 'yl_shop_headquarters.identity_card_num', $this->identity_card_num])
            ->andFilterWhere(['like', 'yl_shop_headquarters.identity_card_front', $this->identity_card_front])
            ->andFilterWhere(['like', 'yl_shop_headquarters.identity_card_back', $this->identity_card_back])
            ->andFilterWhere(['like', 'yl_shop_headquarters.company_name', $this->company_name])
            ->andFilterWhere(['like', 'yl_shop_headquarters.company_area_name', $this->company_area_name])
            ->andFilterWhere(['like', 'yl_shop_headquarters.company_address', $this->company_address])
            ->andFilterWhere(['like', 'yl_shop_headquarters.registration_mark', $this->registration_mark])
            ->andFilterWhere(['like', 'yl_shop_headquarters.business_licence', $this->business_licence])
            ->andFilterWhere(['like', 'yl_shop_headquarters.agreement_name', $this->agreement_name])
            ->andFilterWhere(['like', 'yl_shop_headquarters_list.branch_shop_name', $this->branch_shop_name]);

        $query->orderBy('create_at desc');
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
//        echo "<br />";
//        die;
        return $dataProvider;
    }
}
