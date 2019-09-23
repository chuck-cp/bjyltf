<?php

namespace cms\modules\withdraw\models\search;

use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\withdraw\models\MemberWithdraw;

/**
 * MemberWithdrawSearch represents the model behind the search form of `cms\modules\withdraw\models\MemberWithdraw`.
 */
class MemberWithdrawSearch extends MemberWithdraw
{
    public $offset;
    public $limit;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'member_id', 'status', 'price', 'poundage', 'account_balance', 'examine_status', 'account_type'], 'integer'],
            [['member_name', 'mobile', 'bank_name', 'bank_mobile', 'payee_name', 'create_at','create_at_end','update_at','update_at_end','examine_result'], 'safe'],
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
    public function search($params,$type,$export)
    {
        $query = MemberWithdraw::find()->joinWith('memberinfo');

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
            //'serial_number' => $this->serial_number,
            'yl_member_withdraw.member_id' => $this->member_id,
            'yl_member_withdraw.status' => $this->status,
            'yl_member_withdraw.price' => $this->price,
            //'poundage' => $this->poundage,
            //'account_balance' => $this->account_balance,
            'yl_member_withdraw.examine_status' => $this->examine_status,
            //'create_at' => $this->create_at,
            'yl_member_withdraw.examine_result' => $this->examine_result,
            'yl_member_withdraw.account_type' => $this->account_type,
        ]);
        $condition = '';
        $order = '';
        switch ($type){
            case 1:
                $condition = ['or',['=','yl_member_withdraw.examine_status',0],['and',['=','yl_member_withdraw.examine_status',1],['=','yl_member_withdraw.examine_result',1]]];
                break;
            case 2:
                $condition = ['or',['and',['=','yl_member_withdraw.examine_status',1],['=','yl_member_withdraw.examine_result',2]],['and',['=','yl_member_withdraw.examine_status',2],['=','yl_member_withdraw.examine_result',1]]];
                break;
            case 3:
                $condition = ['and',['=','yl_member_withdraw.examine_status',2],['=','yl_member_withdraw.examine_result',2]];
                break;
            case 4:
                $condition = ['=','yl_member_withdraw.examine_status',3];
                break;
        }
        if($condition){
            $query->andWhere($condition)->orderBy('examine_status asc, examine_result desc');
        }
        //提现申请时间搜索
        if($this->create_at){
            $query->andWhere(['>','create_at',$this->create_at]);
        }
        if($this->create_at_end){
            $query->andWhere(['<','create_at',$this->create_at_end.' 23:59:59']);
        }
        //提现时间
        if($this->update_at){
            $query->andWhere(['and',['>','create_at',$this->update_at],['=','yl_member_withdraw.examine_result',2],['=','yl_member_withdraw.examine_status',3]]);
        }
        if($this->update_at_end){
            $query->andWhere(['and',['<','create_at',$this->update_at_end],['=','yl_member_withdraw.examine_result',2],['=','yl_member_withdraw.examine_status',3]]);
        }
        //编号、银行、收款人、预留电话
        if($this->serial_number && $this->payee_name){
            switch ($this->serial_number){
                case 1://提现编号
                    $query->andWhere(['like','serial_number',$this->payee_name]);
                    break;
                case 2://收款人银行
                    $query->andWhere(['like','bank_name',$this->payee_name]);
                    break;
                case 3://收款人姓名
                    $query->andWhere(['like','payee_name',$this->payee_name]);
                    break;
                case 4://银行预留电话
                    $query->andWhere(['like','bank_mobile',$this->payee_name]);
                    break;
            }
        }else{
            if($this->payee_name){
                $query->andWhere(['or',['like', 'bank_name', $this->payee_name],['like', 'bank_mobile', $this->payee_name],['like', 'payee_name', $this->payee_name],['like', 'serial_number', $this->payee_name]]);
            }
        }
        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);
            //->andFilterWhere(['like', 'bank_name', $this->bank_name])
            //->andFilterWhere(['like', 'bank_mobile', $this->bank_mobile])
            //->andFilterWhere(['like', 'payee_name', $this->payee_name])
        $query->orderBy('id desc');
        if($export==0){
            return $query;
        }elseif ($export == 2){
            return $arr['data'] = $query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }
        return $dataProvider;
    }

    public function Cashsearch($params)
    {
        $query = MemberWithdraw::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if(!trim($this->create_at) && !trim($this->create_at_end) && !trim($this->update_at) && !trim($this->update_at_end) && !trim($this->serial_number) && !trim($this->bank_name) && !trim($this->payee_name) && !trim($this->bank_mobile) && !trim($this->member_name) && !trim($this->mobile) && !trim($this->account_type)){
            $query->andWhere(['id'=>0]);
        }else{
            //提现申请时间搜索
            if($this->create_at){
                $query->andWhere(['>','create_at',$this->create_at]);
            }
            if($this->create_at_end){
                $query->andWhere(['<','create_at',$this->create_at_end.' 23:59:59']);
            }
            //提现时间
            if($this->update_at){
                $query->andWhere(['and',['>','create_at',$this->update_at],['=','yl_member_withdraw.examine_result',2],['=','yl_member_withdraw.examine_status',3]]);
            }
            if($this->update_at_end){
                $query->andWhere(['and',['<','create_at',$this->update_at_end],['=','yl_member_withdraw.examine_result',2],['=','yl_member_withdraw.examine_status',3]]);
            }
            $query->andFilterWhere([
                'account_type' => $this->account_type,
            ]);
            $query->andFilterWhere(['like', 'member_name', $this->member_name])
                ->andFilterWhere(['like', 'mobile', $this->mobile])
                ->andFilterWhere(['like', 'bank_name', $this->bank_name])
                ->andFilterWhere(['like', 'bank_mobile', $this->bank_mobile])
                ->andFilterWhere(['like', 'payee_name', $this->payee_name]);
            $query->orderBy('id desc');
        }
        return $dataProvider;
    }
}
