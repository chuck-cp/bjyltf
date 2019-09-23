<?php

namespace cms\modules\sign\models\search;

use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignTeamBusinessCount;

/**
 * SignTeamBusinessCountSearch represents the model behind the search form of `cms\modules\sign\models\SignTeamBusinessCount`.
 */
class SignTeamBusinessCountSearch extends SignTeamBusinessCount
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
            [['id', 'team_id', 'total_sign_member_number', 'overtime_sign_member_number', 'no_sign_member_number', 'unqualified_member_number', 'total_sign_shop_number', 'repeat_sign_number', 'repeat_shop_number','leave_early_number'], 'integer'],
            [['repeat_sign_rate'], 'number'],
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
        $query = SignTeamBusinessCount::find()->joinWith('signTeam')->groupBy('team_id')->select('yl_sign_team_business_count.*,sum(yl_sign_team_business_count.total_sign_shop_number) as total_sign_shop_number_sum,sum(yl_sign_team_business_count.no_sign_member_number)as no_sign_member_number_sum,sum(yl_sign_team_business_count.overtime_sign_member_number)as overtime_sign_member_number_sum,sum(yl_sign_team_business_count.unqualified_member_number)as unqualified_member_number_sum,sum(yl_sign_team_business_count.leave_early_number)as leave_early_number_sum,sum(yl_sign_team_business_count.repeat_sign_number)as repeat_sign_number_sum,sum(yl_sign_team_business_count.repeat_shop_number)as repeat_shop_number_sum');

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
        if($this->total_sign_member_number==1){
            $query->orderBy('total_sign_shop_number_sum desc');
        }elseif ($this->total_sign_member_number==2){
            $query->orderBy('total_sign_shop_number_sum asc');
        }

        if($this->no_sign_member_number==1){
            $query->orderBy('no_sign_member_number_sum desc');
        }elseif ($this->no_sign_member_number==2){
            $query->orderBy('no_sign_member_number_sum asc');
        }

        if($this->repeat_sign_number==1){
            $query->orderBy('repeat_sign_number_sum desc');
        }elseif ($this->repeat_sign_number==2){
            $query->orderBy('repeat_sign_number_sum asc');
        }

        if($this->team_member_number==1){
            $query->orderBy('yl_sign_team.team_member_number_sum desc');
        }elseif ($this->team_member_number==2){
            $query->orderBy('yl_sign_team.team_member_number_sum asc');
        }

        if($this->unqualified_member_number==1){
            $query->orderBy('unqualified_member_number_sum desc');
        }elseif ($this->unqualified_member_number==2){
            $query->orderBy('unqualified_member_number_sum asc');
        }

        if($this->overtime_sign_member_number==1){
            $query->orderBy('overtime_sign_member_number_sum desc');
        }elseif ($this->overtime_sign_member_number==2){
            $query->orderBy('overtime_sign_member_number_sum asc');
        }

        if($this->repeat_sign_rate==1){
            $query->orderBy('repeat_sign_number_sum desc');
        }elseif ($this->repeat_sign_rate==2){
            $query->orderBy('repeat_sign_number_sum asc');
        }
        if($this->leave_early_number==1){
            $query->orderBy('leave_early_number_sum desc');
        }elseif ($this->leave_early_number==2){
            $query->orderBy('leave_early_number_sum asc');
        }

        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_team_business_count.create_at',$this->create_at]);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_team_business_count.create_at',$this->create_at_end]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'team_id' => $this->team_name,
        ]);
        $commandQuery = clone $query;
      //  echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
