<?php

namespace cms\modules\shop\models\search;

use cms\modules\authority\models\AuthArea;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\shop\models\ShopUpdateRecord;

/**
 * ShopUpdateRecordSearch represents the model behind the search form of `cms\modules\shop\models\ShopUpdateRecord`.
 */
class ShopUpdateRecordSearch extends ShopUpdateRecord
{
    public $province;
    public $city;
    public $town;
    public $area;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'area_id', 'examine_status'], 'integer'],
            [['shop_name', 'apply_name', 'apply_mobile', 'identity_card_num', 'registration_mark', 'company_name', 'identity_card_front', 'identity_card_back', 'agent_identity_card_front', 'agent_identity_card_back', 'update_shop_name', 'update_apply_name', 'update_apply_mobile', 'update_identity_card_num', 'update_registration_mark', 'update_company_name', 'update_identity_card_front', 'update_identity_card_back', 'update_agent_identity_card_front', 'update_agent_identity_card_back', 'business_licence', 'update_business_licence', 'authorize_image', 'update_authorize_image', 'other_image', 'update_other_image', 'examine_at', 'create_user_name', 'create_at','province','city','area','town'], 'safe'],
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
        $query = ShopUpdateRecord::find();

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
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'area_id' => $this->area_id,
            'examine_status' => $this->examine_status,
            'examine_at' => $this->examine_at,
            'create_at' => $this->create_at,
        ]);

        //按地区搜索
        //增加地区限制
        $area = max($this->province,$this->city,$this->area,$this->town);
        //获取他的权限地区
        $userArea = AuthArea::findOne(['user_id'=>Yii::$app->user->identity->getId()]);
        $areaarray = explode(',',$userArea->area_id);

        if($areaarray[0]!=101){
            if(empty($area)){
                $area = $areaarray;//如果是没有搜索地区，就按照权限有的地区全显示
            }else{
                if(strlen($area)>= 7){//搜索的是市一下，包含市
                    if(!in_array(substr($area,0,7),$areaarray)){
                        $area = '2';
                        echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('你的账号未设置地区，请联系管理员！')</script>";
                    }
                }else{//搜索的是省，要吧地区权限符合这个省的都挑出来，其他的不是这个省的去掉
                    foreach ($areaarray as $ka=>$va) {
                        if(substr($va,0,5) != $area){
                            unset($areaarray[$ka]);
                        }
                    }
                    if(empty($areaarray)){
                        $area = '2';
                        echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('你的账号未设置地区，请联系管理员！')</script>";
                    }else{
                        $area = $areaarray;
                    }
                }
            }
        }
        if(!empty($area)){
            if(is_array($area)){
                if(in_array('101',$areaarray)){//区分全国
                    $query->andWhere(['in','left(yl_shop_update_record.area_id,3)',$areaarray]);
                }else{
                    $query->andWhere(['in','left(yl_shop_update_record.area_id,7)',$areaarray]);
                }
            }else{
                $query->andWhere(['left(yl_shop_update_record.area_id,'.strlen($area).')' => $area]);
            }
        }

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'apply_mobile', $this->apply_mobile])
            ->andFilterWhere(['like', 'identity_card_num', $this->identity_card_num])
            ->andFilterWhere(['like', 'registration_mark', $this->registration_mark])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'identity_card_front', $this->identity_card_front])
            ->andFilterWhere(['like', 'identity_card_back', $this->identity_card_back])
            ->andFilterWhere(['like', 'agent_identity_card_front', $this->agent_identity_card_front])
            ->andFilterWhere(['like', 'agent_identity_card_back', $this->agent_identity_card_back])
            ->andFilterWhere(['like', 'update_shop_name', $this->update_shop_name])
            ->andFilterWhere(['like', 'update_apply_name', $this->update_apply_name])
            ->andFilterWhere(['like', 'update_apply_mobile', $this->update_apply_mobile])
            ->andFilterWhere(['like', 'update_identity_card_num', $this->update_identity_card_num])
            ->andFilterWhere(['like', 'update_registration_mark', $this->update_registration_mark])
            ->andFilterWhere(['like', 'update_company_name', $this->update_company_name])
            ->andFilterWhere(['like', 'update_identity_card_front', $this->update_identity_card_front])
            ->andFilterWhere(['like', 'update_identity_card_back', $this->update_identity_card_back])
            ->andFilterWhere(['like', 'update_agent_identity_card_front', $this->update_agent_identity_card_front])
            ->andFilterWhere(['like', 'update_agent_identity_card_back', $this->update_agent_identity_card_back])
            ->andFilterWhere(['like', 'business_licence', $this->business_licence])
            ->andFilterWhere(['like', 'update_business_licence', $this->update_business_licence])
            ->andFilterWhere(['like', 'authorize_image', $this->authorize_image])
            ->andFilterWhere(['like', 'update_authorize_image', $this->update_authorize_image])
            ->andFilterWhere(['like', 'other_image', $this->other_image])
            ->andFilterWhere(['like', 'update_other_image', $this->update_other_image])
            ->andFilterWhere(['like', 'create_user_name', $this->create_user_name]);

        $query->orderBy('id desc');
        return $dataProvider;
    }
}
