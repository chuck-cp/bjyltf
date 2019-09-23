<?php

namespace cms\modules\guest\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\examine\models\ActivityDetail;
use cms\modules\authority\models\AuthArea;
/**
 * ActivityDetailSearch represents the model behind the search form of `cms\modules\examine\models\ActivityDetail`.
 */
class ActivityDetailSearch extends ActivityDetail
{
    public $member_name;
    public $member_mobile;
    public $province;
    public $city;
    public $area;
    public $town;
    public $create_at_end;
    public $custom_member_mobile;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'activity_id', 'custom_member_id', 'area_id', 'mirror_account', 'status'], 'integer'],
            [['custom_member_name', 'shop_name', 'apply_name', 'apply_mobile', 'area_name', 'address', 'shop_image', 'create_at','member_name','member_mobile','province','city','area','town','create_at_end','custom_member_mobile'], 'safe'],
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
        $query = ActivityDetail::find()->joinWith('activity')->joinWith('memberMobile');

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
        if(!trim($this->member_name) && !trim($this->member_mobile) && !trim($this->apply_name) && !trim($this->apply_mobile) && !trim($this->custom_member_name) && !trim($this->custom_member_mobile) && !trim($this->shop_name)){
            $query->andWhere(['yl_activity_detail.id'=>0]);
        }else {
            //按地区搜索
            /*$area = max($this->province,$this->city,$this->area,$this->town);
            if($area){
                $query->andWhere(['left(area_id,'.strlen($area).')' => $area]);
            }*/

            //按地区搜索
            //增加地区限制
            $area = max($this->province, $this->city, $this->area, $this->town);
            //获取他的权限地区
            $userArea = AuthArea::findOne(['user_id' => Yii::$app->user->identity->getId()]);
            $areaarray = explode(',', $userArea->area_id);
            if ($areaarray[0] != 101) {
                if (empty($area)) {
                    $area = $areaarray;//如果是没有搜索地区，就按照权限有的地区全显示
                } else {
                    if (strlen($area) >= 7) {//搜索的是市一下，包含市
                        if (!in_array(substr($area, 0, 7), $areaarray)) {
                            $area = '2';
                            echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('你的账号未设置地区，请联系管理员！')</script>";
                        }
                    } else {//搜索的是省，要吧地区权限符合这个省的都挑出来，其他的不是这个省的去掉
                        foreach ($areaarray as $ka => $va) {
                            if (substr($va, 0, 5) != $area) {
                                unset($areaarray[$ka]);
                            }
                        }
                        if (empty($areaarray)) {
                            $area = '2';
                            echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('你的账号未设置地区，请联系管理员！')</script>";
                        } else {
                            $area = $areaarray;
                        }
                    }
                }
            }
            if (!empty($area)) {
                if (is_array($area)) {
                    if(in_array('101',$areaarray)){//区分全国
                        $query->andWhere(['in', 'left(area_id,3)', $areaarray]);
                    } else {
                        $query->andWhere(['in', 'left(area_id,7)', $areaarray]);
                    }
                } else {
                    $query->andWhere(['left(area_id,' . strlen($area) . ')' => $area]);
                }
            }

            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
                'activity_id' => $this->activity_id,
                'custom_member_id' => $this->custom_member_id,
                'area_id' => $this->area_id,
                'mirror_account' => $this->mirror_account,
                'yl_activity_detail.status' => $this->status,
                'apply_mobile' => $this->apply_mobile,
                'yl_activity.member_mobile' => $this->member_mobile,
                'yl_member.mobile' => $this->custom_member_mobile,
            ]);
            /***创建时间***/
            if (isset($this->create_at) && $this->create_at) {
                $query->andWhere(['>=', 'yl_activity_detail.create_at', $this->create_at . ' 00:00:00']);
            }
            if (isset($this->create_at_end) && $this->create_at_end) {
                $query->andWhere(['<=', 'yl_activity_detail.create_at', $this->create_at_end . ' 23:59:59']);
            }
            $query->andFilterWhere(['like', 'custom_member_name', $this->custom_member_name])
                ->andFilterWhere(['like', 'shop_name', $this->shop_name])
                ->andFilterWhere(['like', 'apply_name', $this->apply_name])
                ->andFilterWhere(['like', 'yl_activity.member_name', $this->member_name]);
            $query->orderBy('id desc');
            $commandQuery = clone $query;
            //  echo $commandQuery->createCommand()->getRawSql();

        }
        return $dataProvider;
    }
}
