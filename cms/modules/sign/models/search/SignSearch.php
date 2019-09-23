<?php

namespace cms\modules\sign\models\search;

use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\Sign;
use cms\models\SystemAddress;
use yii\db\Expression;

/**
 * SignSearch represents the model behind the search form of `cms\modules\sign\models\Sign`.
 */
class SignSearch extends Sign
{
    public $shop_type;
    public $province;
    public $city;
    public $area;
    public $screen;
    public $create_at_end;
    public $evaluate;
    public $date;
    public $mongo_ids;
    public $RepeatShop;
    public $member_mobile;
    public $overtime;
    public $member_type;
    public $mobile;
    public $sign_ids;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'team_type', 'member_id', 'team_member_type', 'first_sign', 'late_sign', 'late_time'], 'integer'],
            [['team_name', 'member_name', 'member_avatar', 'shop_name', 'shop_address', 'create_at','shop_type','province','city','area','screen','create_at_end','evaluate','date','mongo_ids','RepeatShop','overtime','member_type','mobile','sign_ids'], 'safe'],
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
    //业务员签到管理搜索
    public function BusinessSearch($params,$export=0)
    {
        $query = Sign::find()->joinWith('signBusiness');

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
        //有无屏幕
        if($this->screen==2){
            $query->andWhere(['=','yl_sign_business.screen_number',0]);
        }elseif ($this->screen==1){
            $query->andWhere(['<>','yl_sign_business.screen_number',0]);
        }

        //按时间业务签到统计---重复店铺详情 搜索mongo_id
        if(!empty($this->mongo_ids)){
            $query->andWhere(['in','yl_sign_business.mongo_id',$this->mongo_ids]);
        }
        if($this->sign_ids){
            $left="(";
            foreach ($this->sign_ids as $v){
                $left.= $v.',';
            }
            $sign_id=trim($left,',').")";
            $query->andWhere(new Expression('yl_sign.id not in '.$sign_id));
        }
        /***创建时间***/
        if($this->date){
            $this->create_at = $this->date;
            $this->create_at_end = $this->date;
        }
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign.create_at',$this->create_at_end.' 23:59:59']);
        }
        //按地区搜索
        $area = max($this->province,$this->city,$this->area);
        if($area){
            $area_name=SystemAddress::find()->where(['id'=>$area])->select('name')->asArray()->one()['name'];
        }
        if(strlen($area)==5){
            $query->andWhere(['yl_sign_business.province'=>$area_name]);
        }else if(strlen($area)==7){
            $query->andWhere(['yl_sign_business.city'=>$area_name]);
        }else if(strlen($area)==9){
            $query->andWhere(['yl_sign_business.area'=>$area_name]);
        }
        if(!$this->team_name){
            if(Yii::$app->user->identity->sign_team != 0){
                $sign_team = explode(',',Yii::$app->user->identity->sign_team);
                $this->team_name = $sign_team;
            }
        }
        $query->andWhere(['team_type'=>1]);
        $query->andFilterWhere([
            'id' => $this->id,
            'team_id' => $this->team_name,
            'team_type' => $this->team_type,
            'member_id' => $this->member_id,
            'team_member_type' => $this->team_member_type,
            'first_sign' => $this->first_sign,
            'late_sign' => $this->late_sign,
            'late_time' => $this->late_time,
            'yl_sign_business.shop_type' => $this->shop_type,
        ]);
        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'member_avatar', $this->member_avatar])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'shop_address', $this->shop_address]);

        if($this->RepeatShop && $this->RepeatShop==1){
            $query->select('yl_sign.*,count(yl_sign_business.mongo_id) as totalmongo_id')->groupBy('yl_sign_business.mongo_id');
        }else{
            $query->orderBy('create_at desc');
        }
        //$commandQuery = clone $query;
        //echo $commandQuery->createCommand()->getRawSql();

//        die;
        if($export==1){
            return $query;
        }
        return $dataProvider;
    }

    //维护员签到管理搜索
    public function MaintainSearch($params ,$export=0)
    {
        $query = Sign::find()->joinWith('signMaintain');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        //有无屏幕
        if($this->screen==2){
            $query->andWhere(['=','yl_sign_maintain.screen_number',0]);
        }elseif ($this->screen==1){
            $query->andWhere(['<>','yl_sign_maintain.screen_number',0]);
        }
        /***创建时间***/
        if($this->date){
            $this->create_at = $this->date;
            $this->create_at_end = $this->date;
        }
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign.create_at',$this->create_at_end.' 23:59:59']);
        }
        //按地区搜索
        $area = max($this->province,$this->city,$this->area);
        if($area){
            $area_name=SystemAddress::find()->where(['id'=>$area])->select('name')->asArray()->one()['name'];
        }
        if(strlen($area)==5){
            $query->andWhere(['yl_sign_maintain.province'=>$area_name]);
        }else if(strlen($area)==7){
            $query->andWhere(['yl_sign_maintain.city'=>$area_name]);
        }else if(strlen($area)==9){
            $query->andWhere(['yl_sign_maintain.area'=>$area_name]);
        }
        if(!$this->team_id){
            if(Yii::$app->user->identity->sign_team != 0){
                $sign_team = explode(',',Yii::$app->user->identity->sign_team);
                $this->team_id = $sign_team;
            }
        }
        $query->andWhere(['team_type'=>2]);
        $query->andFilterWhere([
            'id' => $this->id,
            'team_id' => $this->team_id,
            'team_type' => $this->team_type,
            'member_id' => $this->member_id,
            'team_member_type' => $this->team_member_type,
            'first_sign' => $this->first_sign,
            'late_sign' => $this->late_sign,
            'late_time' => $this->late_time,
            'yl_sign_maintain.shop_type' => $this->shop_type,
            'yl_sign_maintain.evaluate' => $this->evaluate,
        ]);
        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'member_avatar', $this->member_avatar])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'shop_address', $this->shop_address]);
        $query->orderBy('create_at desc');
        //$commandQuery = clone $query;
        //echo $commandQuery->createCommand()->getRawSql();
        if($export==1){
            return $query;
        }
        return $dataProvider;
    }

    //按时间业务超时签到详情
    public function BusinessOvertimeSearch($params)
    {
        $query = Sign::find()->joinWith('signBusiness')->joinWith('memberMobile')->joinWith('memberType');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        /***创建时间***/
        $query->andWhere(['yl_sign.late_sign'=>1]);
        $query->andWhere(['yl_sign.team_type'=>1]);

        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign.create_at',$this->create_at_end.' 23:59:59']);
        }

        $query->andFilterWhere([
            'yl_sign.team_id' => $this->team_id,
            'yl_sign_team_member.member_type'=>$this->member_type,
        ]);
        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->mobile]);

        if(isset($this->overtime) && $this->overtime){
            if($this->overtime==1){
                $query->orderBy('late_time desc');
            }else if($this->overtime==2){
                $query->orderBy('late_time asc');
            }
        }else{
            $query->orderBy('create_at desc');
        }

        $commandQuery = clone $query;
        // echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
    //按时间维护超时签到详情
    public function MaintainOvertimeSearch($params)
    {
        $query = Sign::find()->joinWith('signBusiness')->joinWith('memberMobile')->joinWith('memberType');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andWhere(['yl_sign.late_sign'=>1]);
        $query->andWhere(['yl_sign.team_type'=>2]);

        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign.create_at',$this->create_at_end.' 23:59:59']);
        }

        $query->andFilterWhere([
            'yl_sign.team_id' => $this->team_id,
            'yl_sign_team_member.member_type'=>$this->member_type,
        ]);
        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->mobile]);

        if(isset($this->overtime) && $this->overtime){
            if($this->overtime==1){
                $query->orderBy('late_time desc');
            }else if($this->overtime==2){
                $query->orderBy('late_time asc');
            }
        }else{
            $query->orderBy('create_at desc');
        }

        $commandQuery = clone $query;
        // echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
