<?php

namespace cms\modules\examine\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\examine\models\ShopAdvertImage;
use cms\modules\authority\models\AuthArea;
/**
 * ShopAdvertImageSearch represents the model behind the search form of `cms\modules\examine\models\ShopAdvertImage`.
 */
class ShopAdvertImageSearch extends ShopAdvertImage
{
    public $name;
    public $company_name;
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
            [['id', 'shop_id', 'shop_type', 'image_size', 'status', 'sort'], 'integer'],
            [['image_url', 'image_sha', 'name','company_name', 'province', 'city', 'area', 'town'], 'safe'],
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
    //商家广告搜索
    public function search($params,$export=0)
    {
        $query = ShopAdvertImage::find()->joinWith('shop');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->groupBy('yl_shop_advert_image.shop_id')->select('yl_shop_advert_image.*,count(yl_shop_advert_image.shop_id) as imgnum');
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'yl_shop_advert_image.shop_type' => $this->shop_type,
            'image_size' => $this->image_size,
            'status' => $this->status,
            'sort' => $this->sort,
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
                    $query->andWhere(['in','left(yl_shop.area,3)',$areaarray]);
                }else{
                    $query->andWhere(['in','left(yl_shop.area,7)',$areaarray]);
                }
            }else{
                $query->andWhere(['left(yl_shop.area,'.strlen($area).')' => $area]);
            }
        }

        $query->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'yl_shop.name', $this->name])
            ->andFilterWhere(['like', 'image_sha', $this->image_sha]);

                $commandQuery = clone $query;
            //    echo $commandQuery->createCommand()->getRawSql();

        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }
    //总部广告搜索
    public function headsearch($params,$export=0)
    {
        $query = ShopAdvertImage::find()->joinWith('head');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->groupBy('yl_shop_advert_image.shop_id')->select('yl_shop_advert_image.*,count(yl_shop_advert_image.shop_id) as imgnum');
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'yl_shop_advert_image.shop_type' => $this->shop_type,
            'image_size' => $this->image_size,
            'status' => $this->status,
            'sort' => $this->sort,
        ]);
        //按地区搜索
        //增加地区限制
        $area = max($this->province,$this->city,$this->area,$this->town);
        //获取他的权限地区
        $userArea = AuthArea::findOne(['user_id'=>Yii::$app->user->identity->getId()]);
        $areaarray = explode(',',$userArea->area_id);
        if($areaarray[0]!=101){
            if(empty($area)){
                $area = $areaarray;
            }else{
                if(!in_array(substr($area,0,5),$areaarray)){
                    $area = '2';
                    echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('你的账号未设置地区，请联系管理员！')</script>";
                }
            }
        }
        if(!empty($area)){
            if(is_array($area)){
                if(in_array('101',$areaarray)){//区分全国
                    $query->andWhere(['in','left(yl_shop_headquarters.company_area_id,3)',$areaarray]);
                }else{
                    $query->andWhere(['in','left(yl_shop_headquarters.company_area_id,5)',$areaarray]);
                }
            }else{
                $query->andWhere(['left(yl_shop_headquarters.company_area_id,'.strlen($area).')' => $area]);
            }
        }
        $query->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'yl_shop_headquarters.company_name', $this->company_name])
            ->andFilterWhere(['like', 'image_sha', $this->image_sha]);
        if($export==1){
            return $query;
        }
        $commandQuery = clone $query;
      //  echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
