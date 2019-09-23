<?php

namespace cms\modules\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\MemberInvoice;

/**
 * MemberInvoiceSearch represents the model behind the search form of `cms\modules\member\models\MemberInvoice`.
 */
class MemberInvoiceSearch extends MemberInvoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'invoice_title_type', 'invoice_value', 'address_id', 'order_num', 'status'], 'integer'],
            [['member_name', 'member_phone', 'invoice_title', 'taxplayer_id', 'receiver', 'contact_phone', 'address_detail', 'remark', 'invoice_address', 'invoice_phone', 'bank_name', 'bank_account', 'tracking_number', 'logistics_name', 'create_at', 'update_at','starts_at','ends_at'], 'safe'],
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
    public function search($params)
    {
        $query = MemberInvoice::find();

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
        /*首付款日期查询*/
        if(isset($this->starts_at) && $this->starts_at){
            $query->andWhere(['>=','create_at',$this->starts_at.' 00:00:00']);
        }
        if(isset($this->ends_at) && $this->ends_at){
            $query->andWhere(['<=','create_at',$this->ends_at.' 23:59:59']);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
        ]);
        $query->orderBy('id desc');
        $query->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'member_phone', $this->member_phone])
            ->andFilterWhere(['like', 'invoice_title', $this->invoice_title]);
        return $dataProvider;
    }
}
