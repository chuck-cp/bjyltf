<?php

namespace cms\modules\member\models\search;

use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\MemberTeamList;

/**
 * MemberSearchTeamList represents the model behind the search form of `cms\modules\member\models\MemberTeamList`.
 */
class MemberSearchTeamList extends MemberTeamList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'install_shop_number', 'install_screen_number', 'wait_shop_number'], 'integer'],
            [['member_name','mobile','team_id'], 'safe'],
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

        $query = MemberTeamList::find()->joinWith('memberMobile');

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

        // grid filtering conditions
        $query->andFilterWhere([
            'member_id' => $this->member_id,
            'install_shop_number' => $this->install_shop_number,
            'install_screen_number' => $this->install_screen_number,
            'wait_shop_number' => $this->wait_shop_number,
            'team_id' => $this->team_id,
        ]);
        $query->andFilterWhere(['like', 'yl_member.mobile', $this->mobile])
              ->andFilterWhere(['like', 'member_name', $this->member_name]);
        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }
}
