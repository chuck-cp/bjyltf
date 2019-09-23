<?php

namespace cms\modules\account\models\search;

use cms\modules\member\models\MemberInstallSubsidyList;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MemberInstallSubsidySearch represents the model behind the search form of `cms\modules\member\models\MemberInstallSubsidy`.
 */
class MemberInstallSubsidyListSearch extends MemberInstallSubsidyList
{
    public $create_at_start;
    public $create_at_end;
    public $mobile;
    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','mobile','create_at_start','create_at_end','install_member_id'], 'safe'],
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
        $query = MemberInstallSubsidyList::find()->joinWith('memberIncomePrice')->joinWith('memberNameMobile');;

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
        //结算中心的申请补贴日期搜索
        if($this->create_at_start){
            $query->andWhere(['>=','yl_member_install_subsidy_list.create_at',$this->create_at_start.' 00:00:00']);
        }
        if($this->create_at_end){
            $query->andWhere(['<=','yl_member_install_subsidy_list.create_at',$this->create_at_end.' 23:59:59']);
        }

        $query->andFilterWhere([
            'yl_member_install_subsidy_list.install_member_id' => $this->install_member_id,
        ]);

        $query->andFilterWhere(['like', 'yl_member.mobile', $this->mobile])
            ->andFilterWhere(['like', 'yl_member.name', $this->name]);
        $query->orderBy('yl_member_install_subsidy_list.create_at desc');
        if($export==1){
            return $query;
        }
        return $dataProvider;
    }
}
