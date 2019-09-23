<?php
namespace cms\modules\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\MemberTeam;

/**
 * MembeTeamSearch represents the model behind the search form of `cms\modules\member\models\MemberTeam`.
 */
class MemberTeamSearch extends MemberTeam
{
    public $groupstatus;
    public $province;
    public $city;
    public $area;
    public $town;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_member_id', 'live_area_id', 'company_area_id', 'install_shop_number', 'not_install_shop_number', 'not_assign_shop_number'], 'integer'],
            [['team_member_name', 'team_name', 'live_area_name', 'live_address', 'company_name', 'company_area_name', 'company_address', 'phone','mobile','groupstatus','province','city','area','town'], 'safe'],
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
    public function search($params,$export = 0)
    {
        $query = MemberTeam::find()->joinWith('memberMobile');
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
        if($this->groupstatus==1){
            $query->andWhere(['=','yl_member_team.status',$this->groupstatus]);
        }
        $town = max($this->province,$this->city,$this->area,$this->town);
        if($town){
            $query->andWhere(['left(yl_member_team.live_area_id,'.strlen($town).')' => $town]);
        }
        $query->orderBy('id desc');
        // grid filtering conditions
        $query->andFilterWhere([
            'team_member_id' => $this->team_member_id,
            'live_area_id' => $this->live_area_id,
            'company_area_id' => $this->company_area_id,
            'install_shop_number' => $this->install_shop_number,
            'not_install_shop_number' => $this->not_install_shop_number,
            'not_assign_shop_number' => $this->not_assign_shop_number,
        ]);

        $query->andFilterWhere(['like', 'team_member_name', $this->team_member_name])
            ->andFilterWhere(['like', 'team_name', $this->team_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->phone])
            ->andFilterWhere(['like', 'live_area_name', $this->live_area_name])
            ->andFilterWhere(['like', 'live_address', $this->live_address])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'company_area_name', $this->company_area_name])
            ->andFilterWhere(['like', 'company_address', $this->company_address]);
        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }
}
