<?php

namespace cms\modules\sign\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignBusinessCount;

/**
 * SignBusinessCountSearch represents the model behind the search form of `cms\modules\sign\models\SignBusinessCount`.
 */
class SignBusinessCountSearch extends SignBusinessCount
{
    public $create_at_end;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'total_sign_member_number', 'overtime_sign_member_number', 'no_sign_member_number', 'unqualified_member_number', 'total_sign_shop_number', 'repeat_sign_number', 'repeat_shop_number'], 'integer'],
            [['repeat_sign_rate'], 'number'],
            [['create_at','create_at_end'], 'safe'],
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
        $query = SignBusinessCount::find();

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
        //签到时间
        if(empty($this->create_at) && empty($this->create_at_end)){
            $this->create_at = date('Y-m-d',time()-24*3600*7);
            $this->create_at_end = date('Y-m-d');
        }
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','create_at',$this->create_at]);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','create_at',$this->create_at_end]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'total_sign_member_number' => $this->total_sign_member_number,
            'overtime_sign_member_number' => $this->overtime_sign_member_number,
            'no_sign_member_number' => $this->no_sign_member_number,
            'unqualified_member_number' => $this->unqualified_member_number,
            'total_sign_shop_number' => $this->total_sign_shop_number,
            'repeat_sign_number' => $this->repeat_sign_number,
            'repeat_sign_rate' => $this->repeat_sign_rate,
            'repeat_shop_number' => $this->repeat_shop_number,
        ]);

        $query->orderBy('create_at desc');
        $res['data'] = $dataProvider;

        $res['stat']['total_sign_shop_number'] = 0;
        $res['stat']['no_sign_member_number'] = 0;
        $res['stat']['overtime_sign_member_number'] = 0;
        $res['stat']['unqualified_member_number'] = 0;
        $res['stat']['repeat_sign_number'] = 0;
        $res['stat']['repeat_shop_number'] = 0;
        $res['stat']['leave_early_number'] = 0;
        $stat = SignBusinessCount::find()->where(['and',['>=','create_at',$this->create_at],['<=','create_at',$this->create_at_end]])->asArray()->all();
        foreach($stat as $key=>$value){
            $res['stat']['total_sign_shop_number'] += $value['total_sign_shop_number'];
            $res['stat']['no_sign_member_number'] += $value['no_sign_member_number'];
            $res['stat']['overtime_sign_member_number'] += $value['overtime_sign_member_number'];
            $res['stat']['unqualified_member_number'] += $value['unqualified_member_number'];
            $res['stat']['repeat_sign_number'] += $value['repeat_sign_number'];
            $res['stat']['repeat_shop_number'] += $value['repeat_shop_number'];
            $res['stat']['leave_early_number'] += $value['leave_early_number'];
        }
        if($res['stat']['total_sign_shop_number'] == 0){
            $res['stat']['repeat_sign_rate'] = 0.00;
        }else{
            $res['stat']['repeat_sign_rate'] = round($res['stat']['repeat_sign_number']/$res['stat']['total_sign_shop_number'],2);
        }
        return $res;
    }
}
