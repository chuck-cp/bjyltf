<?php

namespace cms\modules\examine\models\search;

use cms\modules\authority\models\AuthArea;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\examine\models\ShopScreenReplace;

/**
 * ShopScreenReplaceSearch represents the model behind the search form of `cms\modules\examine\models\ShopScreenReplace`.
 */
class ShopScreenReplaceSearch extends ShopScreenReplace
{
    public $replace;
    public $install_member_mobile;
    public $create_at_start;
    public $create_at_end;
    public $install_finish_at_start;
    public $install_finish_at_end;
    public $zhipai;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','maintain_type', 'shop_id', 'shop_area_id', 'install_member_id', 'install_price', 'replace_screen_number', 'create_user_id', 'status','zhipai'], 'integer'],
            [['shop_name', 'shop_address', 'install_member_name', 'install_finish_at', 'create_user_name', 'create_at', 'assign_at','province','city','area','town','zhipai_status','install_member_mobile','create_at_start','create_at_end','install_finish_at_start','install_finish_at_end'], 'safe'],
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
        $query = ShopScreenReplace::find()->joinWith('member');

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

        if($this->zhipai == 1){
            if($this->status == ''){
                $status = [0,1];
            }else{
                $status = $this->status;
            }
            $query->andFilterWhere([
                'yl_shop_screen_replace.status' => $status,
                'yl_shop_screen_replace.maintain_type' => [2,3,4],
            ]);
        }elseif($this->zhipai == 2){
            if($this->status == ''){
                $status = [2,3,4];
            }else{
                $status = $this->status;
            }
            $query->andFilterWhere([
                'yl_shop_screen_replace.status' => $status,
                'yl_shop_screen_replace.maintain_type' => [2,3,4],
            ]);
        }
        if($this->replace == 1){
            $query->andFilterWhere([
                'yl_shop_screen_replace.maintain_type' => [2,3,4],
                'yl_shop_screen_replace.status' => [4],
            ]);
        }elseif($this->replace == 2){
            $query->andFilterWhere([
                'yl_shop_screen_replace.maintain_type' => [1,2,3,4],
                'yl_shop_screen_replace.status' => [4],
            ]);
        }

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
                    $query->andWhere(['in','left(shop_area_id,3)',$areaarray]);
                }else{
                    $query->andWhere(['in','left(shop_area_id,7)',$areaarray]);
                }
            }else{
                $query->andWhere(['left(shop_area_id,'.strlen($area).')' => $area]);
            }
        }

        //指派搜索（已指派/未指派）
        if($this->zhipai_status==1){//已指派
            //$query->andWhere(['>','install_member_id','0']);
            $query->andWhere('install_member_id>0');
        }else if($this->zhipai_status==2){//未指派
            $query->andWhere(['install_member_id'=>'0']);
        }

        //换屏申请时间
        if(isset($this->create_at_start) && $this->create_at_start){
            $query->andWhere(['>=','yl_shop_screen_replace.create_at',$this->create_at_start.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_shop_screen_replace.create_at',$this->create_at_end.' 23:59:59']);
        }
        //换屏审核完成时间
        if(isset($this->install_finish_at_start) && $this->install_finish_at_start){
            $query->andWhere(['>=','yl_shop_screen_replace.install_finish_at',$this->install_finish_at_start.' 00:00:00']);
        }
        if(isset($this->install_finish_at_end) && $this->install_finish_at_end){
            $query->andWhere(['<=','yl_shop_screen_replace.install_finish_at',$this->install_finish_at_end.' 23:59:59']);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'maintain_type' => $this->maintain_type,
            'shop_id' => $this->shop_id,
//            'shop_area_id' => $this->shop_area_id,
            'install_member_id' => $this->install_member_id,
//            'install_finish_at' => $this->install_finish_at,
            'install_price' => $this->install_price,
            'replace_screen_number' => $this->replace_screen_number,
//            'create_user_id' => $this->create_user_id,
//            'status' => $this->status,
//            'create_at' => $this->create_at,
            'assign_at' => $this->assign_at,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->install_member_mobile])
            ->andFilterWhere(['like', 'shop_address', $this->shop_address])
            ->andFilterWhere(['like', 'install_member_name', $this->install_member_name])
            ->andFilterWhere(['like', 'create_user_name', $this->create_user_name]);

        $query->orderBy('yl_shop_screen_replace.create_at desc');

//        $commandQuery = clone $query;
//         echo $commandQuery->createCommand()->getRawSql();

        if($export == 1){
            return $query;
        }
        return $dataProvider;
    }
}
