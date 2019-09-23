<?php

namespace cms\modules\shop\models\search;

use cms\modules\authority\models\AuthArea;
use cms\modules\authority\models\AuthAssignment;
use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\shop\models\Shop;
use yii\helpers\Url;

/**
 * ShopSearch represents the model behind the search form about `cms\modules\shop\models\Shop`.
 */
class ShopSearch extends Shop
{
    public $default_status;
    public $create_at_start;
    public $create_at_end;
    public $install_finish_at_start;
    public $install_finish_at_end;
    public $shop_examine_at_start;
    public $shop_examine_at_end;
    public $apply_name;
    public $apply_mobile;
    public $contacts_name;
    public $contacts_mobile;
    public $assign_status;
    public $store_type;//店铺统计数据的店铺类型
    public $areas;
    public $shop_contract;
    public $contract_start;//合同审合通过时间
    public $contract_end;//合同审合通过时间
    public $offset;
    public $limit;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'admin_member_id', 'area', 'apply_screen_number', 'screen_number', 'error_screen_number', 'screen_status', 'apply_client','delivery_status','install_member_id','shop_operate_type'], 'integer'],
            [['id','member_name',  'shop_image', 'name','province','city','town','way', 'area_name', 'create_at','create_at_end','mirror_account','status', 'apply_code','examine_user_name','examine_user_group', 'install_member_name','mobile','create_at_start','create_at_end','apply_name','apply_mobile','contacts_name','contacts_mobile','member_mobile','assign_status','install_finish_at_start','install_finish_at_end','shop_examine_at_start','shop_examine_at_end','agreed','member_inside','store_type','areas','address','shop_contract','contract_start','contract_end','install_mobile'], 'safe'],
            [['acreage'], 'number'],
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
//        $query = Shop::find()->joinWith('apply')->joinWith('admin');
        $query = isset($params['ShopSearch']['mobile']) ? Shop::find()->joinWith('apply')->joinWith('member') : Shop::find()->joinWith('apply')->joinWith('shopContract')->joinWith('admin');//
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if($this->status == -10){
            $this->status = [0,1,2,3,4];
        }
        //入驻方式
        if($this->way){
            if($this->way == 1){
                $query->andWhere(['>','member_id',0]);
            }else{
                $query->andWhere(['=','member_id',0]);
            }
        }

        /***创建时间***/
        if(isset($this->create_at_start) && $this->create_at_start){
            $query->andWhere(['>=','yl_shop.create_at',$this->create_at_start.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_shop.create_at',$this->create_at_end.' 23:59:59']);
        }

        /***店铺申请审核通过时间***/
        if(isset($this->shop_examine_at_start) && $this->shop_examine_at_start){
            $query->andWhere(['>=','yl_shop.shop_examine_at',$this->shop_examine_at_start.' 00:00:00']);
        }
        if(isset($this->shop_examine_at_end) && $this->shop_examine_at_end){
            $query->andWhere(['<=','yl_shop.shop_examine_at',$this->shop_examine_at_end.' 23:59:59']);
        }

        /***店铺安装完成时间***/
        if(isset($this->install_finish_at_start) && $this->install_finish_at_start){
            $query->andWhere(['>=','yl_shop.install_finish_at',$this->install_finish_at_start.' 00:00:00']);
        }
        if(isset($this->install_finish_at_end) && $this->install_finish_at_end){
            $query->andWhere(['<=','yl_shop.install_finish_at',$this->install_finish_at_end.' 23:59:59']);
        }

        /***店铺合同通过时间***/
        if(isset($this->contract_start) && $this->contract_start){
            $query->andWhere(['>=','yl_shop_contract.examine_at',$this->contract_start.' 00:00:00']);
        }
        if(isset($this->contract_end) && $this->contract_end){
            $query->andWhere(['<=','yl_shop_contract.examine_at',$this->contract_end.' 23:59:59']);
        }

        //按店铺面积排序
        $order = '';
        if($this->acreage != ''){
            if($this->acreage == 0){
                $order .= 'acreage desc,';
            }else{
                $order .= 'acreage asc,';
            }
        }
        //按申请数量排序
        if($this->apply_screen_number != ''){
            if($this->apply_screen_number == 0){
                $order .= 'apply_screen_number desc,';
            }else{
                $order .= 'apply_screen_number asc,';
            }
        }
        if($order){
            //$order = rtrim($order,',');
            $query->orderBy($order.'yl_shop.create_at desc');
        }else{
            $query->orderBy('yl_shop.create_at desc');
        }

        //判断合同
        if($this->shop_contract == 5){
            $query->andWhere(['yl_shop.contract_id'=>0]);
        }elseif($this->shop_contract == 1){
            $query->andWhere(['yl_shop_contract.examine_status'=>[0]]);
            $query->andWhere(['yl_shop_contract.status'=>1]);
        }elseif($this->shop_contract == 2){
            $query->andWhere(['yl_shop_contract.examine_status'=>2]);
            $query->andWhere(['yl_shop_contract.status'=>1]);
        }elseif($this->shop_contract == 3){
            $query->andWhere(['yl_shop_contract.examine_status'=>1]);
            $query->andWhere(['yl_shop_contract.status'=>1]);
        }elseif($this->shop_contract == 4){
            $query->andWhere(['yl_shop_contract.status'=>2]);
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
                    $query->andWhere(['in','left(yl_shop.area,3)',$areaarray]);
                }else{
                    $query->andWhere(['in','left(yl_shop.area,7)',$areaarray]);
                }
            }else{
                $query->andWhere(['left(yl_shop.area,'.strlen($area).')' => $area]);
            }
        }
        if($this->agreed==1){
            $query->andWhere(['agreed'=>1]);
        }if($this->agreed==2){
            $query->andWhere(['agreed'=>0]);
        }
        $query->andFilterWhere([
            'yl_shop.install_team_id' => $this->install_team_id,
            'yl_shop.install_member_id' => $this->install_member_id,
            'yl_shop.install_mobile' => $this->install_mobile,
            'yl_shop.member_id' => $this->member_id,
            'yl_shop.member_mobile' => $this->member_mobile,
            'yl_shop.install_status' => $this->install_status,
            'yl_shop.screen_number' => $this->screen_number,
            'yl_shop.error_screen_number' => $this->error_screen_number,
            'yl_shop.status' => $this->status,
            'yl_shop.screen_status' => $this->screen_status,
            'yl_shop.member_inside' => $this->member_inside,
            'yl_shop.apply_client' => $this->apply_client,
            'yl_shop.shop_operate_type' => $this->shop_operate_type,
        ]);

        if($this->default_status==1){//待安装状态
            if($this->status == ''){
                $status = [3,4];
            }else{
                $status = $this->status;
            }
            $query->andFilterWhere([
                'yl_shop.status' => $status,
            ]);
        }elseif($this->default_status==2){//线上店铺审核
            if($this->status == ''){
                $status = [0,1];
            }else{
                $status = $this->status;
            }
            $query->andWhere(['yl_shop.status' => $status,]);
        }elseif($this->default_status==3) {//配发货,待安装状态
            if($this->status == ''){
                $status = [2];
            }else{
                $status = $this->status;
            }
            if($this->assign_status==1){
                $query->andWhere(['or',['>','yl_shop.install_team_id','0'],['>','yl_shop.install_member_id','0']]);
            }else if($this->assign_status==2){
                $query->andWhere(['and',['yl_shop.install_team_id'=>'0'],['yl_shop.install_member_id'=>'0']]);
            }
            $query->andFilterWhere([
                'yl_shop.status' => $status,
            ]);
        }elseif($this->default_status==4) {//线下店铺审核
            if($this->status == ''){
                $status = [0,1,2];
            }else{
                $status = $this->status;
            }
            $query->andFilterWhere([
                'yl_shop.status' => $status,
            ]);
        }elseif($this->default_status==6){
            if($this->status == ''){
                $status = [3,4,5];
            }else{
                $status = $this->status;
            }
            $query->andWhere([
                'yl_shop.status' => $status,
            ]);
        }else{
            if($this->status == ''){
                $status = [0,1,2,3,4,5,6];
            }else{
                $status = $this->status;
            }
            $query->andWhere([
                'yl_shop.status' => $status,
            ]);
        }

        if(!empty($this->delivery_status)){
            $query->andFilterWhere([
                'yl_shop.delivery_status' => $this->delivery_status,
            ]);
        }
//        $auths=AuthAssignment::find()->where(['user_id'=>Yii::$app->user->identity->getId()])->select('item_name')->asArray()->all();
//        $juse = array_column($auths,'item_name');
//        if(in_array('商家审核',$juse)){
        if($this->default_status == 5){
            $query->andWhere([
                'yl_shop.status' => [0,1],
                'yl_shop.examine_user_group' => '',
                'yl_shop.examine_user_name' => '',
            ]);
        }else{
            if(Yii::$app->user->identity->member_group>0){
                $query->andFilterWhere(['yl_shop.examine_user_group'=> Yii::$app->user->identity->member_group]);
            }
        }
        $query->andFilterWhere(['like', 'yl_shop.member_name', $this->member_name])
            ->andFilterWhere(['like', 'yl_shop_apply.apply_code', $this->apply_code])
            ->andFilterWhere(['like', 'yl_shop.name', $this->name])
            ->andFilterWhere(['like', 'yl_shop.id', $this->id])
            ->andFilterWhere(['like', 'yl_shop.install_member_name', $this->install_member_name])
            ->andFilterWhere(['like', 'yl_shop.install_price', $this->install_price])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->mobile])
            ->andFilterWhere(['like', 'yl_shop_apply.apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'yl_shop_apply.apply_mobile', $this->apply_mobile])
            ->andFilterWhere(['like', 'yl_shop_apply.contacts_name', $this->contacts_name])
            ->andFilterWhere(['like', 'yl_shop_apply.contacts_mobile', $this->contacts_mobile])
            ->andFilterWhere(['like', 'yl_shop.area_name', $this->area_name]);

//         $commandQuery = clone $query;
     //    echo $commandQuery->createCommand()->getRawSql();
//        echo "<br />";
//        die;

        if($export == 1){
            return $query;
        }else if($export == 2){
            return $arr['data'] = $query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }
        return $dataProvider;
    }

    //店铺统计查询
    public function StatisticsSearch($params){
        $query = Shop::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(yl_shop.area,'.strlen($area).')' => $area]);
        }

        if(!$this->store_type){
            $this->create_at_start=date('Y-m-d');
            $this->create_at_end=date('Y-m-d');
        }else{
            if(!$this->create_at_start){
                $this->create_at_start=date('Y-m-d');
            }
            if(!$this->create_at_end){
                $this->create_at_end=date('Y-m-d');
            }
        }

        //查询店铺安装总量->地图显示数据
        $res['stat']['total_shop_array']=$query->where(['and',['status'=>5],$area?['left(yl_shop.area,'.strlen($area).')' => $area]:[],['not like','name','测试'],['<>','longitude',''],['<>','latitude','']])->select('id,name,area_name,address,screen_number,longitude,latitude,mirror_account')->asArray()->all();
        $markerArr = [];
        foreach ($res['stat']['total_shop_array'] as $key1=>$value1){
            $markerArr[$key1]['title'] ='';
            $markerArr[$key1]['name'] = "<b>店铺编号:</b> ".$value1['id']." <div class='guanbi' onclick='feng()'>X</div></br><b>店名:</b> ".$value1['name']."</br><b>地区:</b> ".$value1['area_name']." </br><b>详址:</b> ".$value1['address']." </br><b>安装台数:</b> ".$value1['screen_number']."</br><b>镜面数量:</b>".$value1['mirror_account'];
            $markerArr[$key1]['lnglat'][] = $value1['longitude'];
            $markerArr[$key1]['lnglat'][] = $value1['latitude'];
            $markerArr[$key1]['name2'] = $value1['address'];
            $markerArr[$key1]['style'] = 0;

        }
        //查询店铺安装总量
        $res['stat']['total_shop'] = $query->where(['and',['status'=>5],$area?['left(yl_shop.area,'.strlen($area).')' => $area]:[],['not like','name','测试']])->count();

        //未安装店铺总量
        $res['stat']['not_install_total_shop_array']=$query->where(['and',['status'=>['2','3','4']],$area?['left(yl_shop.area,'.strlen($area).')' => $area]:[],['<','yl_shop.shop_examine_at',$this->create_at_end.' 00:00:00'],['not like','name','测试'],['<>','longitude',''],['<>','latitude','']])->select('id,name,area_name,address,screen_number,longitude,latitude,mirror_account')->asArray()->all();

        $markerArr2 = [];
        foreach ($res['stat']['not_install_total_shop_array'] as $key2=>$value2){
            $markerArr2[$key2]['name'] = "<b>店铺编号:</b> ".$value2['id']." <div class='guanbi' onclick='feng()'>X</div></br><b>店名:</b> ".$value2['name']."</br><b>地区:</b> ".$value2['area_name']." </br><b>详址:</b> ".$value2['address']." </br><b>安装台数:</b> ".$value2['screen_number']."</br><b>镜面数量:</b>".$value2['mirror_account'];
            $markerArr2[$key2]['title'] ='';
            $markerArr2[$key2]['lnglat'][] = $value2['longitude'];
            $markerArr2[$key2]['lnglat'][] = $value2['latitude'];
            $markerArr2[$key2]['name2'] = $value2['address'];
            $markerArr2[$key2]['style'] = 1;
        }
        $res['stat']['not_install_total_shop'] = count($res['stat']['not_install_total_shop_array']);

        //新增店铺签约量

        $res['stat']['new_signing_total_shop_array']=$query->where(['and',['status'=>[2,3,4,5]],$area?['left(yl_shop.area,'.strlen($area).')' => $area]:[],['>=','yl_shop.shop_examine_at',$this->create_at_start.' 00:00:00'],['<=','yl_shop.shop_examine_at',$this->create_at_end.' 23:59:59'],['not like','name','测试'],['<>','longitude',''],['<>','latitude','']])->select('id,name,area_name,address,screen_number,longitude,latitude,mirror_account')->asArray()->all();
        $markerArr3 = [];
        foreach ($res['stat']['new_signing_total_shop_array'] as $key3=>$value3){
            $markerArr3[$key3]['name'] = "<b>店铺编号:</b> ".$value3['id']." <div class='guanbi' onclick='feng()'>X</div></br><b>店名:</b> ".$value3['name']."</br><b>地区:</b> ".$value3['area_name']." </br><b>详址:</b> ".$value3['address']." </br><b>安装台数:</b> ".$value3['screen_number']."</br><b>镜面数量:</b>".$value3['mirror_account'];
            $markerArr3[$key3]['lnglat'][]= $value3['longitude'];
            $markerArr3[$key3]['lnglat'][] = $value3['latitude'];
            $markerArr3[$key3]['name2'] = $value3['address'];
            $markerArr3[$key3]['style'] = 2;
        }
        $res['stat']['new_signing_total_shop'] = count($res['stat']['new_signing_total_shop_array']);

        //新增店铺安装量
        $res['stat']['new_install_total_shop_array']=$query->where(['and',['status'=>5],$area?['left(yl_shop.area,'.strlen($area).')' => $area]:[],['>=','yl_shop.install_finish_at',$this->create_at_start.' 00:00:00'],['<=','yl_shop.install_finish_at',$this->create_at_end.' 23:59:59'],['not like','name','测试'],['<>','longitude',''],['<>','latitude','']])->select('id,name,area_name,address,screen_number,longitude,latitude,mirror_account')->asArray()->all();
        $markerArr4 = [];
        foreach ($res['stat']['new_signing_total_shop_array'] as $key4=>$value4){
            $markerArr4[$key4]['name'] = "<b>店铺编号:</b> ".$value4['id']." <div class='guanbi' onclick='feng()'>X</div></br><b>店名:</b> ".$value4['name']."</br><b>地区:</b> ".$value4['area_name']." </br><b>详址:</b> ".$value4['address']." </br><b>安装台数:</b> ".$value4['screen_number']."</br><b>镜面数量:</b>".$value4['mirror_account'];
            $markerArr4[$key4]['lnglat'][] = $value4['longitude'];
            $markerArr4[$key4]['lnglat'][] = $value4['latitude'];
            $markerArr4[$key4]['name2'] = $value4['address'];
            $markerArr4[$key4]['style'] = 3;
        }
        $res['stat']['new_install_total_shop'] = count($res['stat']['new_install_total_shop_array']);
        $res['data'] = $dataProvider;
        $citys = array_merge_recursive($markerArr,$markerArr2,$markerArr3,$markerArr4);
      //  echo json_encode($markerArr);
      //  ToolsClass::p($markerArr);
        $res['stat']['citys']=json_encode($citys);
        return $res;
    }
    public function StatisticsViewSearch($params,$export=0){
        $query = Shop::find()->joinWith('apply');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        /***创建时间***/
        if($this->store_type==1){
            if(isset($this->create_at_start) && $this->create_at_start){
                $query->andWhere(['>=','yl_shop.shop_examine_at',$this->create_at_start.' 00:00:00']);
            }
            if(isset($this->create_at_end) && $this->create_at_end){
                $query->andWhere(['<=','yl_shop.shop_examine_at',$this->create_at_end.' 23:59:59']);
            }
            $query->andWhere(['in','status',[2,3,4,5]]);
        }elseif ($this->store_type==2){
            if(isset($this->create_at_start) && $this->create_at_start){
                $query->andWhere(['>=','yl_shop.install_finish_at',$this->create_at_start.' 00:00:00']);
            }
            if(isset($this->create_at_end) && $this->create_at_end){
                $query->andWhere(['<=','yl_shop.install_finish_at',$this->create_at_end.' 23:59:59']);
            }
            $query->andWhere(['status'=>5]);
        }
        $query->andWhere(['not like','yl_shop.name','测试']);

        $query->andFilterWhere([
            'yl_shop.shop_operate_type' => $this->shop_operate_type,
        ]);
        if($this->province && isset($this->province)){
            $area = max($this->province,$this->city,$this->area,$this->town);
        }else{
            $area=$this->areas;
        }
        if($area && $area!==0){
            $query->andWhere(['left(yl_shop.area,'.strlen($area).')' => $area]);
        }
        $query->andFilterWhere(['like', 'yl_shop.name', $this->name])
            ->andFilterWhere(['like', 'yl_shop_apply.contacts_name', $this->contacts_name])
            ->andFilterWhere(['like', 'yl_shop_apply.contacts_mobile', $this->contacts_mobile])
            ->andFilterWhere(['like', 'yl_shop.member_name', $this->member_name])
            ->andFilterWhere(['like', 'yl_shop.install_member_name', $this->install_member_name])
            ->andFilterWhere(['like', 'yl_shop.address', $this->address]);
        $commandQuery = clone $query;
       // echo $commandQuery->createCommand()->getRawSql();
        if($export==1){
            return $query;
        }
        return $dataProvider;
    }
}
