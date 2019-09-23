<?php

namespace cms\modules\sign\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignTeamCountMemberDetail;

/**
 * SignTeamCountMemberDetailSearch represents the model behind the search form of `cms\modules\sign\models\SignTeamCountMemberDetail`.
 */
class SignTeamCountMemberDetailSearch extends SignTeamCountMemberDetail
{
    public $member_name;
    public $member_mobile;
    public $team_member_type;
    public $create_at_end;
    public $shop_type;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'team_type', 'member_id', 'member_type'], 'integer'],
            [['create_at','member_name','member_mobile','team_member_type','create_at_end','shop_type'], 'safe'],
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
        $query = SignTeamCountMemberDetail::find()->joinWith('member')->joinWith('signTeam')->joinWith('signTeamMember');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //签到时间
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_team_count_member_detail.create_at',$this->create_at]);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_team_count_member_detail.create_at',$this->create_at_end]);
        }
        //人员信息
        if(isset($this->member_name) && $this->member_name){
            $query->andFilterWhere(['like', 'yl_member.name', $this->member_name]);
        }
        if(isset($this->member_mobile) && $this->member_mobile){
            $query->andFilterWhere(['like', 'yl_member.mobile', $this->member_mobile]);
        }
        //职务
        if(isset($this->team_member_type) && $this->team_member_type){
            $query->andWhere(['yl_sign_team_member.member_type'=>$this->team_member_type]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'yl_sign_team_count_member_detail.team_id' => $this->team_id,
            'team_type' => $this->team_type,
            'yl_sign_team_count_member_detail.member_id' => $this->member_id,
            'yl_sign_team_count_member_detail.member_type' => $this->member_type,
        ]);

//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }

    public function oversearch($params)
    {
        $query = SignTeamCountMemberDetail::find()->joinWith('member');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //签到时间
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_team_count_member_detail.create_at',$this->create_at]);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_team_count_member_detail.create_at',$this->create_at_end]);
        }
        //人员信息
        if(isset($this->member_name) && $this->member_name){
            $query->andFilterWhere(['like', 'yl_member.name', $this->member_name]);
        }
        if(isset($this->member_mobile) && $this->member_mobile){
            $query->andFilterWhere(['like', 'yl_member.mobile', $this->member_mobile]);
        }
        //职务
//        if(isset($this->team_member_type) && $this->team_member_type){
//            $query->andWhere(['yl_sign_team_member.member_type'=>$this->team_member_type]);
//        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'yl_sign_team_count_member_detail.team_id' => $this->team_id,
            'team_type' => $this->team_type,
            'yl_sign_team_count_member_detail.member_id' => $this->member_id,
            'yl_sign_team_count_member_detail.member_type' => $this->member_type,
        ]);

        $commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
