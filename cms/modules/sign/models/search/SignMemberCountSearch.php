<?php

namespace cms\modules\sign\models\search;

use cms\modules\sign\models\SignBusiness;
use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignMemberCount;
use yii\data\Pagination;
use cms\modules\sign\models\SignMaintain;
use cms\modules\sign\models\Sign;
/**
 * SignMemberCountSearch represents the model behind the search form of `cms\modules\sign\models\SignMemberCount`.
 */
class SignMemberCountSearch extends SignMemberCount
{
    public $member_name;
    public $member_mobile;
    public $create_at_end;
    public $member_type;
    public $overtime;
    public $number;
    public $team_type;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'member_id', 'late_sign', 'qualified', 'sign_number'], 'integer'],
            [['update_at', 'create_at','create_at_end','member_name','member_mobile','member_type','overtime','number','team_type'], 'safe'],
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
    //未签到成员详情
    public function search($params)
    {
        $query = SignMemberCount::find()->joinWith('member')->joinWith('signTeam')->joinWith('memberType');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andWhere(['sign_number'=>0]);
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_member_count.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_member_count.create_at',$this->create_at_end.' 23:59:59']);
        }
        if($this->team_type==1){
            $query->andWhere(['yl_sign_team.team_type'=>1]);
        }elseif($this->team_type==2){
            $query->andWhere(['yl_sign_team.team_type'=>2]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'yl_sign_member_count.team_id' => $this->team_id,
            'member_id' => $this->member_id,
            'late_sign' => $this->late_sign,
            'qualified' => $this->qualified,
            'sign_number' => $this->sign_number,
            'update_at' => $this->update_at,
            'yl_sign_team_member.member_type' => $this->member_type,
        ]);
        $query->andFilterWhere(['like', 'yl_member.name', $this->member_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->member_mobile]);
        $commandQuery = clone $query;
        //  echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }

    //超时签到详情
    public function overseasrch($params){
        $query = SignMemberCount::find()->joinWith('member')->joinWith('signTeam')->joinWith('memberType');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andWhere(['<>','sign_number',0]);
        $query->andWhere(['late_sign'=>1]);
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_member_count.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_member_count.create_at',$this->create_at_end.' 23:59:59']);
        }
        if(isset($this->overtime) && $this->overtime){
            if($this->overtime==1){
                $query->orderBy('update_at desc');
            }else if($this->overtime==2){
                $query->orderBy('update_at asc');
            }
        }
        if($this->team_type==1){
            $query->andWhere(['yl_sign_team.team_type'=>1]);
        }elseif($this->team_type==2){
            $query->andWhere(['yl_sign_team.team_type'=>2]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'yl_sign_member_count.team_id' => $this->team_id,
            'member_id' => $this->member_id,
            'late_sign' => $this->late_sign,
            'qualified' => $this->qualified,
            'sign_number' => $this->sign_number,
            'yl_sign_team_member.member_type' => $this->member_type,
        ]);
        $query->andFilterWhere(['like', 'yl_member.name', $this->member_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->member_mobile]);
        $commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }

    //未达标搜索
    public function unqualifiedsearch($params){
        $query = SignMemberCount::find()->joinWith('member')->joinWith('signTeam')->joinWith('memberType');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        /*  $query->andWhere(['<>','sign_number',0]);*/
        $query->andWhere(['qualified'=>0]);
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_member_count.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_member_count.create_at',$this->create_at_end.' 23:59:59']);
        }
        if(isset($this->number) && $this->number){
            if($this->number==1){
                $query->orderBy('sign_number desc');
            }else if($this->number==2){
                $query->orderBy('sign_number asc');
            }
        }
        if($this->team_type==1){
            $query->andWhere(['yl_sign_team.team_type'=>1]);
        }elseif($this->team_type==2){
            $query->andWhere(['yl_sign_team.team_type'=>2]);
        }
        $query->andFilterWhere([
            'yl_sign_member_count.team_id' => $this->team_id,
            'yl_sign_team_member.member_type' => $this->member_type,
        ]);
        $query->andFilterWhere(['like', 'yl_member.name', $this->member_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->member_mobile]);
        $commandQuery = clone $query;
        //echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }

    //早退搜索
    public function leaveearlysearch($params){
        $query = SignMemberCount::find()->joinWith('member')->joinWith('signTeam')->joinWith('memberType');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        /*  $query->andWhere(['<>','sign_number',0]);*/
        $query->andWhere(['leave_early'=>1]);
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_member_count.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_member_count.create_at',$this->create_at_end.' 23:59:59']);
        }
        if(isset($this->number) && $this->number){
            if($this->number==1){
                $query->orderBy('sign_number desc');
            }else if($this->number==2){
                $query->orderBy('sign_number asc');
            }
        }
        if($this->team_type==1){
            $query->andWhere(['yl_sign_team.team_type'=>1]);
        }elseif($this->team_type==2){
            $query->andWhere(['yl_sign_team.team_type'=>2]);
        }
        $query->andFilterWhere([
            'yl_sign_member_count.team_id' => $this->team_id,
            'yl_sign_team_member.member_type' => $this->member_type,
        ]);
        $query->andFilterWhere(['like', 'yl_member.name', $this->member_name])
            ->andFilterWhere(['like', 'yl_member.mobile', $this->member_mobile]);
        $commandQuery = clone $query;
        //echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }

    //按团队业务签到统计---查看详情 搜索
    public function BusinessTeamViewSearch($params){
        $query = SignMemberCount::find()->joinWith('member')->joinWith('signTeam')->select('yl_sign_member_count.*,sum(yl_sign_member_count.sign_number)as sign_number_sum,sum(yl_sign_member_count.late_sign)as late_sign_sum');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_member_count.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_member_count.create_at',$this->create_at_end.' 23:59:59']);
        }

        $query->andWhere(['yl_sign_team.team_type'=>1]);
        $query->andWhere(['yl_sign_member_count.team_id'=>$this->team_id]);

        $arr['counts'] = $query->groupBy('member_id')->count();
        $arr['pages'] = new Pagination(['totalCount' =>$arr['counts'],'pageSize'=>'20']);
        $arr['data'] = $query->offset($arr['pages']->offset)->limit($arr['pages']->limit)->groupBy('member_id')->asArray()->all();

        $BusinessQuery=Sign::find()->joinWith('signBusiness');

        //未签到天数的查询条件
        $NoSsignModel = SignMemberCount::find();

        //未达标数的查询条件
        $qualified = SignMemberCount::find();

        foreach($arr['data']as $k=>$v){
            //未签到天数
            $arr['data'][$k]['no_sign_number']=$NoSsignModel->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_id'=>$this->team_id],['team_type'=>1],['member_id'=>$v['member_id']],['sign_number'=>0]])->count();
            //未达标天数
            $arr['data'][$k]['no_qualified']=$qualified->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_id'=>$this->team_id],['team_type'=>1],['member_id'=>$v['member_id']],['qualified'=>0]])->count();
            //早退的天数
            $arr['data'][$k]['leave_early_num']=$qualified->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_id'=>$this->team_id],['team_type'=>1],['member_id'=>$v['member_id']],['leave_early'=>1]])->count();

            $Arrs=$BusinessQuery->where(['and',['>=','yl_sign.create_at',$this->create_at.' 00:00:00'],['<=','yl_sign.create_at',$this->create_at_end.' 23:59:59'],['yl_sign.team_id'=>$this->team_id],['yl_sign.team_type'=>1],['yl_sign.member_id'=>$v['member_id']]])->asArray()->all();

            $mongo_id_arr=[];
            foreach($Arrs as $key=>$value){
                $mongo_id_arr[]=$value['signBusiness']['mongo_id'];
            }
            $mongo_ids=array_count_values($mongo_id_arr);
            $i=0;
            $repeat_sign_sum_arr=[];
            foreach($mongo_ids as $vv){
                if($vv>1){
                    $repeat_sign_sum_arr[]=$vv-1;
                    $i+=1;
                }
            }
            $arr['data'][$k]['repeat_sign_sum']=array_sum($repeat_sign_sum_arr);
            $arr['data'][$k]['repeat_shop_sum']=$i;
        }
        return $arr;

        $commandQuery = clone $query;
        //echo $commandQuery->createCommand()->getRawSql();
       // return $dataProvider;
    }


    //按团队维护签到统计---查看详情 搜索
    public function MaintainTeamViewSearch($params){
        $query = SignMemberCount::find()->joinWith('member')->joinWith('signTeam')->select('yl_sign_member_count.*,sum(yl_sign_member_count.sign_number)as sign_number_sum,sum(yl_sign_member_count.late_sign)as late_sign_sum');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_member_count.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_member_count.create_at',$this->create_at_end.' 23:59:59']);
        }

        $query->andWhere(['yl_sign_team.team_type'=>2]);
        $query->andWhere(['yl_sign_member_count.team_id'=>$this->team_id]);

        $arr['counts'] = $query->groupBy('member_id')->count();
        $arr['pages'] = new Pagination(['totalCount' =>$arr['counts'],'pageSize'=>'20']);
        $arr['data'] = $query->offset($arr['pages']->offset)->limit($arr['pages']->limit)->groupBy('member_id')->asArray()->all();

        $MaintainQuery=Sign::find()->joinWith('signMaintain');

        //未签到天数的查询条件
        $SignMemberCountModel = SignMemberCount::find();



        foreach($arr['data']as $k=>$v){
            //未签到的天数
            $arr['data'][$k]['no_sign_number']= $SignMemberCountModel->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_id'=>$this->team_id],['team_type'=>2],['sign_number'=>0],['member_id'=>$v['member_id']]])->count();

            //未达标的天数
            $arr['data'][$k]['qualified_sum']= $SignMemberCountModel->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_id'=>$this->team_id],['team_type'=>2],['qualified'=>0],['member_id'=>$v['member_id']]])->count();

            //早退成员数
            $arr['data'][$k]['leave_early_sum']= $SignMemberCountModel->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_id'=>$this->team_id],['team_type'=>2],['leave_early'=>1],['member_id'=>$v['member_id']]])->count();

            //同意好评数
            $arr['data'][$k]['praise_sum']=$MaintainQuery->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_type'=>2],['member_id'=>$v['member_id']],['team_id'=>$this->team_id],['yl_sign_maintain.evaluate'=>1]])->count();//好评数

            //统计中评数
            $arr['data'][$k]['review_sum']=$MaintainQuery->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_type'=>2],['member_id'=>$v['member_id']],['team_id'=>$this->team_id],['yl_sign_maintain.evaluate'=>2]])->count();//中评数

            //统计差评数
            $arr['data'][$k]['negative_sum']=$MaintainQuery->where(['and',['>=','create_at',$this->create_at.' 00:00:00'],['<=','create_at',$this->create_at_end.' 23:59:59'],['team_type'=>2],['member_id'=>$v['member_id']],['team_id'=>$this->team_id],['yl_sign_maintain.evaluate'=>3]])->count();//差评数


            //评价总数
            $arr['data'][$k]['evaluate_sum']=$arr['data'][$k]['praise_sum']+$arr['data'][$k]['review_sum']+$arr['data'][$k]['negative_sum'];
        }

        return $arr;

      //  $commandQuery = clone $query;
        //echo $commandQuery->createCommand()->getRawSql();
        // return $dataProvider;
    }
}

