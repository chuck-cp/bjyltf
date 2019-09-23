<?php

namespace cms\modules\sign\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignTeamMember;

/**
 * SignTeamMemberSearch represents the model behind the search form of `cms\modules\sign\models\SignTeamMember`.
 */
class SignTeamMemberSearch extends SignTeamMember
{
    public $create_at;
    public $create_at_end;
    public $mobile;
    public $name;
    public $sign_numbers;
    public $late_signs;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'member_id', 'member_type'], 'integer'],
            [['update_at','create_at','create_at_end','mobile','name','sign_numbers','late_signs'], 'safe'],
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
        $query = SignTeamMember::find()->joinWith('member')->joinWith('signMemberCount')->select('yl_sign_team_member.*,sum(yl_sign_member_count.sign_number) as sign_numbers,sum(yl_sign_member_count.late_sign) as late_signs');//
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        //签到次数
        if(isset($this->sign_numbers) && $this->sign_numbers) {
            if ($this->sign_numbers == 1) {
                $query->orderBy('sign_numbers desc');
            } else {
                $query->orderBy('sign_numbers');
            }
        }
        //超时签到次数
        if(isset($this->late_signs) && $this->late_signs) {
            if ($this->late_signs == 1) {
                $query->orderBy('late_signs desc');
            } else {
                $query->orderBy('late_signs');
            }
        }
        //当未传时间搜索就只搜索今天数据
        if (empty($this->create_at) && empty($this->create_at_end)) {
            $this->create_at = date('Y-m-d',time());
            $this->create_at_end = date('Y-m-d',time());
        }
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_member_count.create_at',$this->create_at]);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_member_count.create_at',$this->create_at_end]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'yl_sign_team_member.team_id' => $this->team_id,
            'yl_sign_team_member.member_id' => $this->member_id,
            'member_type' => $this->member_type,
            'yl_sign_team_member.update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'yl_member.mobile', $this->mobile])
            ->andFilterWhere(['like', 'yl_member.name', $this->name]);

        $query->groupBy('yl_sign_member_count.member_id');

        $commandQuery = clone $query;
      //  echo $commandQuery->createCommand()->getRawSql();

        return $dataProvider;
    }
}
