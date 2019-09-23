<?php

namespace cms\modules\screen\models\search;

use cms\modules\authority\models\AuthArea;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\screen\models\ShopScreenAdvertMaintain;

/**
 * ShopScreenAdvertMaintainSearch represents the model behind the search form of `cms\modules\screen\models\ShopScreenAdvertMaintain`.
 */
class ShopScreenAdvertMaintainSearch extends ShopScreenAdvertMaintain
{
    public $create_at_start;
    public $create_at_end;
    public $province;
    public $city;
    public $area;
    public $town;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'shop_area_id', 'screen_number', 'create_user_id', 'status', 'install_member_id'], 'integer'],
            [['mongo_id', 'apply_name', 'apply_mobile', 'shop_name', 'shop_image', 'shop_area_name', 'shop_address', 'create_user_name', 'install_member_name', 'install_finish_at', 'create_at', 'assign_at', 'assign_time', 'problem_description', 'images','create_at_start','create_at_end','province','city','town','area'], 'safe'],
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
        $query = ShopScreenAdvertMaintain::find();

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

        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(shop_area_id,'.strlen($area).')' => $area]);
        }

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
                    $query->andWhere(['in','left(yl_shop_screen_advert_maintain.shop_area_id,3)',$areaarray]);
                }else{
                    $query->andWhere(['in','left(yl_shop_screen_advert_maintain.shop_area_id,7)',$areaarray]);
                }
            }else{
                $query->andWhere(['left(yl_shop_screen_advert_maintain.shop_area_id,'.strlen($area).')' => $area]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'screen_number' => $this->screen_number,
            'create_user_id' => $this->create_user_id,
            'status' => $this->status,
            'install_member_id' => $this->install_member_id,
            'install_finish_at' => $this->install_finish_at,
            'create_at' => $this->create_at,
            'assign_at' => $this->assign_at,
            'assign_time' => $this->assign_time,
        ]);

        echo $this->shop_name;
        $query->andFilterWhere(['like', 'mongo_id', $this->mongo_id])
            ->andFilterWhere(['like', 'apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'apply_mobile', $this->apply_mobile])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'shop_image', $this->shop_image])
            ->andFilterWhere(['like', 'shop_area_name', $this->shop_area_name])
            ->andFilterWhere(['like', 'shop_address', $this->shop_address])
            ->andFilterWhere(['like', 'create_user_name', $this->create_user_name])
            ->andFilterWhere(['like', 'install_member_name', $this->install_member_name])
            ->andFilterWhere(['like', 'problem_description', $this->problem_description])
            ->andFilterWhere(['like', 'images', $this->images]);
        //$commandQuery = clone $query;
        //echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
