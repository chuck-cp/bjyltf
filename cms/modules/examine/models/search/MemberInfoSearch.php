<?php

namespace cms\modules\examine\models\search;

use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\MemberInfo;

/**
 * MemberInfoSearch represents the model behind the search form of `cms\modules\member\models\MemberInfo`.
 */
class MemberInfoSearch extends MemberInfo
{
    public $mobile;
    public $province;
    public $city;
    public $area;
    public $town;
    public $installer_status;
    public $electrician_status;
    public $auditassign;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'sex', 'examine_status'], 'integer'],
            [['name', 'id_number', 'id_front_image', 'id_back_image', 'id_hand_image','mobile','province','city','area','town','electrician_examine_status','installer_status','electrician_status','auditassign'], 'safe'],
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
        $query = MemberInfo::find()->joinWith('member')->joinWith('memCount')->joinWith('memTeam');

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
        if($this->installer_status==1){
            if($this->electrician_examine_status == ''){
                $status = [0,1,2];
            }else{
                $status = $this->electrician_examine_status;
            }
            $query->andWhere([
                'yl_member_info.electrician_examine_status' => $status,
            ]);
        }
        if($this->auditassign==1){
            $query->andWhere(['=','yl_member_info.electrician_examine_status',1]);
            $query->andWhere(['=','yl_member_info.join_team_id',0]);
            $query->andWhere(['=','yl_member.quit_status',0]);
            if(!$this->name && !$this->mobile){
                $query->andWhere(['yl_member_info.name'=>'不存在']);
                return $dataProvider;
            }else{
                $query->andFilterWhere(['like', 'yl_member_info.name', $this->name])
                    ->andFilterWhere(['like', 'yl_member.mobile', $this->mobile]);
                return $dataProvider;
            }
        }
        //按地区搜索
        if($this->electrician_status==1){
            $query->andWhere(['=','yl_member_info.electrician_examine_status',1]);
            $query->andWhere(['=','yl_member_info.join_team_id',0]);
            $query->andWhere(['=','yl_member.quit_status',0]);
            $town = max($this->province,$this->city,$this->area,$this->town);
            if($town){
                $query->andWhere(['left(yl_member_info.live_area_id,'.strlen($town).')' => $town]);
            }
        }else{
            $area = max($this->province,$this->city,$this->area);
            if($area){
                $query->andWhere(['left(yl_member.area,'.strlen($area).')' => $area]);
            }
        }
        $query->andFilterWhere([
            'member_id' => $this->member_id,
//            'sex' => $this->sex,
            'yl_member_info.examine_status' => $this->examine_status,
        ]);
        $query->andFilterWhere(['like', 'yl_member_info.name', $this->name])
                ->andFilterWhere(['like', 'yl_member.mobile', $this->mobile]);
        $query->orderBy('apply_at desc');
        return $dataProvider;

    }
}
