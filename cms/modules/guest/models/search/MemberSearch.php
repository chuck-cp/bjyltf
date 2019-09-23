<?php

namespace cms\modules\guest\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\guest\models\Member;

/**
 * MemberSearch represents the model behind the search form of `cms\modules\guest\models\Member`.
 */
class MemberSearch extends Member
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'admin_area', 'member_type', 'area', 'quit_status', 'status', 'inside', 'team', 'sign_team_id', 'sign_team_admin'], 'integer'],
            [['name', 'name_prefix', 'avatar', 'mobile', 'school', 'education', 'area_name', 'address', 'emergency_contact_name', 'emergency_contact_mobile', 'emergency_contact_relation', 'create_at', 'update_at'], 'safe'],
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
        $query = Member::find();

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
        if(!trim($this->name) && !trim($this->mobile) ){
            $query->Where(['yl_member.id'=>0]);
        }else {
            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
                'parent_id' => $this->parent_id,
                'mobile' => $this->mobile,
                'admin_area' => $this->admin_area,
                'member_type' => $this->member_type,
                'area' => $this->area,
                'quit_status' => $this->quit_status,
                'status' => $this->status,
                'inside' => $this->inside,
                'team' => $this->team,
                'sign_team_id' => $this->sign_team_id,
                'sign_team_admin' => $this->sign_team_admin,
                'create_at' => $this->create_at,
                'update_at' => $this->update_at,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'name_prefix', $this->name_prefix])
                ->andFilterWhere(['like', 'avatar', $this->avatar])
                ->andFilterWhere(['like', 'school', $this->school])
                ->andFilterWhere(['like', 'education', $this->education])
                ->andFilterWhere(['like', 'area_name', $this->area_name])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'emergency_contact_name', $this->emergency_contact_name])
                ->andFilterWhere(['like', 'emergency_contact_mobile', $this->emergency_contact_mobile])
                ->andFilterWhere(['like', 'emergency_contact_relation', $this->emergency_contact_relation]);
        }
        return $dataProvider;
    }
}
