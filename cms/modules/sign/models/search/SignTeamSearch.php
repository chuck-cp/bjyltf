<?php

namespace cms\modules\sign\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignTeam;

/**
 * SignTeamSearch represents the model behind the search form of `cms\modules\sign\models\SignTeam`.
 */
class SignTeamSearch extends SignTeam
{
    public $create_at_end;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_member_number', 'team_manager_number', 'sign_interval_time', 'sign_qualified_number', 'team_type', 'team_member_id'], 'integer'],
            [['first_sign_time', 'team_name', 'team_member_name', 'create_at','create_at_end'], 'safe'],
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
     * $export  1为搜索 2为导出
     */
    public function search($params)
    {
        $query = SignTeam::find();

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
        //首次签到时间
        if(isset($this->first_sign_time) && $this->first_sign_time) {
            if ($this->first_sign_time == 1) {
                $query->orderBy('first_sign_time');
            } else {
                $query->orderBy('first_sign_time desc');
            }
        }
        //成员数量
        if(isset($this->team_member_number) && $this->team_member_number) {
            if ($this->team_member_number == 1) {
                $query->orderBy('team_member_number desc');
            } else {
                $query->orderBy('team_member_number');
            }
        }
        //团队负责人数量
        if(isset($this->team_manager_number) && $this->team_manager_number) {
            if ($this->team_manager_number == 1) {
                $query->orderBy('team_manager_number desc');
            } else {
                $query->orderBy('team_manager_number');
            }
        }
        //签到间隔时间
        if(isset($this->sign_interval_time) && $this->sign_interval_time) {
            if ($this->sign_interval_time == 1) {
                $query->orderBy('sign_interval_time desc');
            } else {
                $query->orderBy('sign_interval_time');
            }
        }
        //签到达标数
        if(isset($this->sign_qualified_number) && $this->sign_qualified_number) {
            if ($this->sign_qualified_number == 1) {
                $query->orderBy('sign_qualified_number desc');
            } else {
                $query->orderBy('sign_qualified_number');
            }
        }

        //创建时间搜索
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','create_at',$this->create_at_end.' 23:59:59']);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sign_qualified_number' => $this->sign_qualified_number,
            'team_type' => $this->team_type,
            'team_member_id' => $this->team_member_id,
        ]);

        $query->andFilterWhere(['like', 'team_name', $this->team_name])
            ->andFilterWhere(['like', 'team_member_name', $this->team_member_name]);
        $commandQuery = clone $query;
       // echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
