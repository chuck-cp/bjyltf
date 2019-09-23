<?php

namespace cms\modules\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\MemberShopApplyCount;
use yii\data\Pagination;
/**
 * MemberSearch represents the model behind the search form about `cms\modules\member\models\Member`.
 */
class MemberShopApplyCountSearch extends MemberShopApplyCount
{
    public $province;
    public $city;
    public $area;
    public $town;
    public $create_at_end;
    public $name;
    public $mobile;
    public $type;
    public $totalshop;
    public $totalscreen;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'shop_number', 'screen_number', 'create_at','province','city','area','town','create_at_start','create_at_end','name','mobile','type'],'safe'],
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
    public function search($params,$export=0)
    {
        $query = MemberShopApplyCount::find()->joinWith('member')->joinWith('memberShopApplyRank');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->groupBy('yl_member_shop_apply_count.member_id');
        $query->andFilterWhere(['yl_member.inside'=>1]);
        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(area,'.strlen($area).')' => $area]);
        }

        //统计日期搜索
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_member_shop_apply_count.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_member_shop_apply_count.create_at',$this->create_at_end.' 23:59:59']);
        }

        $query->andFilterWhere(['like', 'yl_member.name', $this->name])
              ->andFilterWhere(['like', 'yl_member.mobile', $this->mobile]);

        //算总数
        $query->select('yl_member_shop_apply_count.*,sum(yl_member_shop_apply_count.shop_number) as totalshop,sum(yl_member_shop_apply_count.screen_number) as totalscreen')->orderBy('totalshop desc');

        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }

}

