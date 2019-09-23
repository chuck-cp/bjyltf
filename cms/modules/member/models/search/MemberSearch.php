<?php

namespace cms\modules\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\Member;

/**
 * MemberSearch represents the model behind the search form about `cms\modules\member\models\Member`.
 */
class MemberSearch extends Member
{
    public $province;
    public $city;
    public $electrician;
    public $company_electrician;
    public $create_at_end;
    public $invite_id;
    public $offset;
    public $limit;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'admin_area', 'member_type', 'status', 'parent_id','inside'], 'integer'],
            [['token', 'parent_number', 'parent_list', 'name', 'avatar', 'mobile', 'school', 'education', 'address', 'emergency_contact_name', 'emergency_contact_mobile', 'emergency_contact_relation', 'create_at', 'area','update_at','count_price', 'examine_status','town','province','city','area','inside','electrician','company_electrician','invite_id'], 'safe'],
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
        $query = Member::find()->joinWith('memIdcardInfo')->joinWith('memberAccount');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(area,'.strlen($area).')' => $area]);
        }
//        //地区筛选
//        if($this->area){
//            $query->andWhere(['left(area,'.strlen($this->area).')' => $this->area]);
//        }

        //是否为合作推广人
        if(isset($this->invite_id) && $this->invite_id) {
            if ($this->invite_id==1) {
                $query->andWhere(['and',['<>', 'inside', 1], ['>', 'parent_id', 0]]);
            }else{
                $query->andWhere(['or',['inside'=> 1], ['parent_id'=>0]]);
            }
        }

        //业务地区
        if($this->admin_area){
            $query->andWhere(['admin_area' => $this->admin_area]);
        }
        //工号
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        //内部人员
        $query->andFilterWhere([
            'inside' => $this->inside,
        ]);


        //按收业务人员联系商家数排序
        if($this->inside){
            $order = $this->inside == 1 ? 'yl_member_account.shop_number desc' : 'yl_member_account.shop_number asc';
            $query->orderBy($order);
        }else{
            $query->orderBy('id desc');
        }

        //按收益金额排序
        if($this->count_price){
            echo $order = $this->count_price == 1 ? 'count_price desc' : 'count_price asc';

            $query->orderBy($order);
        }else{

            $query->orderBy('id desc');
        }



        $query->andFilterWhere([
            'parent_id' => $this->parent_id,
            //'left(area,'.strlen($this->area).')' => intval($this->area),
            'examine_status' => $this->examine_status,
        ]);

        //搜索指派人员
        if($this->member_type){
            $query->andFilterWhere([
                'member_type' => $params['member_type'],
            ]);
            $query->andFilterWhere(['like', 'yl_member.admin_area', $params['area']]);
        }

        //是否为电工
        if($this->electrician==1){
            $query->andFilterWhere([
                'yl_member_info.electrician_examine_status' => $this->electrician,
            ]);
        }elseif($this->electrician==2){
            $query->andFilterWhere([
                'yl_member_info.electrician_examine_status' => [-1,0,2],
            ]);
        }

        $query->andFilterWhere([
            'yl_member_info.company_electrician' => $this->company_electrician,
        ]);

        $query->andFilterWhere(['like', 'yl_member.name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'emergency_contact_mobile', $this->emergency_contact_mobile]);
      /*  $commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();*/
        if($export == 1){
            return $query;
        }else if($export == 2){
            return $arr['data'] = $query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }
        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function screenSearch($params)
    {
        $query = Member::find()->joinWith('memberCount');

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
        //按地区搜索
        $area = max($this->province,$this->city,$this->area);
        if($area){
            $query->andWhere(['left(admin_area,'.strlen($area).')' => $area]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'member_type' => $this->member_type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'admin_area', $this->admin_area]);
        return $dataProvider;
    }

    //业绩排行搜索
    public function ranksearch($params,$export=0)
    {
        $query = Member::find()->joinWith('memberShopApplyCount')->joinWith('memberShopApplyRank');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(area,'.strlen($area).')' => $area]);
        }

        $query->andFilterWhere([
            'parent_id' => $this->parent_id,
            'examine_status' => $this->examine_status,
        ]);

        $query->andFilterWhere(['like', 'yl_member.name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);

        if($export==1){
            return $query;
        }
//        $query->asArray()->count();
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
//        echo "<br />";
//        die;
        return $dataProvider;
    }

    //店铺推荐信息
    public function activitysearch($params)
    {
        $query = Member::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andWhere(['inside'=>1]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);

//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();

        return $dataProvider;
    }
}
