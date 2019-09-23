<?php

namespace cms\modules\sign\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignTeamMaintainCount;

/**
 * SignTeamMaintainCountSearch represents the model behind the search form of `cms\modules\sign\models\SignTeamMaintainCount`.
 */
class SignTeamMaintainCountSearch extends SignTeamMaintainCount
{
    public $create_at_end;
    public $team_name;
    public $team_member_number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'total_sign_number', 'total_sign_member_number', 'overtime_sign_member_number', 'no_sign_member_number', 'unqualified_member_number', 'total_evaluate_number', 'good_evaluate_number', 'middle_evaluate_number', 'bad_evaluate_number'], 'integer'],
            [['bad_evaluate_rate'], 'number'],
            [['create_at','create_at_end','team_name','team_member_number'], 'safe'],
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
        $query = SignTeamMaintainCount::find()->joinWith('maintainTeam')->groupBy('team_id')->select('yl_sign_team_maintain_count.*,sum(yl_sign_team_maintain_count.total_sign_number)as total_sign_number_sum,sum(yl_sign_team_maintain_count.no_sign_member_number)as no_sign_member_number_sum,sum(yl_sign_team_maintain_count.overtime_sign_member_number)as overtime_sign_member_number_sum,sum(yl_sign_team_maintain_count.unqualified_member_number)as unqualified_member_number_sum,sum(yl_sign_team_maintain_count.good_evaluate_number)as good_evaluate_number_sum,sum(yl_sign_team_maintain_count.middle_evaluate_number)as middle_evaluate_number_sum,sum(yl_sign_team_maintain_count.bad_evaluate_number)as bad_evaluate_number_sum,sum(yl_sign_team_maintain_count.total_evaluate_number)as total_evaluate_number_sum,sum(yl_sign_team_maintain_count.leave_early_number)as leave_early_number_sum');

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
        if($this->team_member_number==1){
            $query->orderBy('yl_sign_team.team_member_number desc');
        }elseif ($this->team_member_number==2){
            $query->orderBy('yl_sign_team.team_member_number asc');
        }

        //签到总数
        if($this->total_sign_number==1){
            $query->orderBy('total_sign_number_sum desc');
        }elseif ($this->total_sign_number==2){
            $query->orderBy('total_sign_number_sum asc');
        }

        //未签到数
        if($this->no_sign_member_number==1){
            $query->orderBy('no_sign_member_number_sum desc');
        }elseif ($this->no_sign_member_number==2){
            $query->orderBy('no_sign_member_number_sum asc');
        }

        //差评率

        //未达标数
        if($this->unqualified_member_number==1){
            $query->orderBy('unqualified_member_number_sum desc');
        }elseif ($this->unqualified_member_number==2){
            $query->orderBy('unqualified_member_number_sum asc');
        }

        //超时签到数
        if($this->overtime_sign_member_number==1){
            $query->orderBy('overtime_sign_member_number_sum desc');
        }elseif ($this->overtime_sign_member_number==2){
            $query->orderBy('overtime_sign_member_number_sum asc');
        }

        //好评数
        if($this->good_evaluate_number==1){
            $query->orderBy('good_evaluate_number_sum desc');
        }elseif ($this->good_evaluate_number==2){
            $query->orderBy('good_evaluate_number_sum asc');
        }

        //中评
        if($this->middle_evaluate_number==1){
            $query->orderBy('middle_evaluate_number_sum desc');
        }elseif ($this->middle_evaluate_number==2){
            $query->orderBy('middle_evaluate_number_sum asc');
        }

        //差评
        if($this->bad_evaluate_number==1){
            $query->orderBy('bad_evaluate_number_sum desc');
        }elseif ($this->bad_evaluate_number==2){
            $query->orderBy('bad_evaluate_number_sum asc');
        }

        //早退
        if($this->leave_early_number==1){
            $query->orderBy('leave_early_number_sum desc');
        }elseif ($this->leave_early_number==2){
            $query->orderBy('leave_early_number_sum asc');
        }

        //签到时间
        /*if(empty($this->create_at) && empty($this->create_at_end)){
            $this->create_at = date('Y-m-d',time()-24*3600*7);
            $this->create_at_end = date('Y-m-d');
        }*/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_team_maintain_count.create_at',$this->create_at]);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_team_maintain_count.create_at',$this->create_at_end]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'team_id' => $this->team_name,
            /*'total_sign_number' => $this->total_sign_number,
            'total_sign_member_number' => $this->total_sign_member_number,
            'overtime_sign_member_number' => $this->overtime_sign_member_number,
            'no_sign_member_number' => $this->no_sign_member_number,
            'unqualified_member_number' => $this->unqualified_member_number,
            'total_evaluate_number' => $this->total_evaluate_number,
            'good_evaluate_number' => $this->good_evaluate_number,
            'middle_evaluate_number' => $this->middle_evaluate_number,
            'bad_evaluate_number' => $this->bad_evaluate_number,
            'bad_evaluate_rate' => $this->bad_evaluate_rate,*/
        ]);

        return $dataProvider;
    }
}
