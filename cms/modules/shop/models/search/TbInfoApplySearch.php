<?php

namespace cms\modules\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\shop\models\TbInfoApply;

/**
 * TbInfoApplySearch represents the model behind the search form about `cms\modules\shop\models\TbInfoApply`.
 */
class TbInfoApplySearch extends TbInfoApply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'reference_id', 'province', 'city', 'area', 'led_account', 'mirror_account', 'channel', 'status', 'profit', 'mod_count'], 'integer'],
            [['code', 'introducer', 'user_name', 'identity_card_num', 'mobile', 'address', 'shop_name', 'registration_mark', 'company_name', 'identity_card_front', 'identity_card_back', 'business_licence', 'shop_image', 'install_image', 'apply_time', 'install_name', 'install_mobile', 'check_name', 'check_mobile', 'install_time', 'install_position', 'auditing_user', 'auditing_time', 'fail_reason'], 'safe'],
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
    public function search($params)
    {
        $query = TbInfoApply::find();

//        echo '<pre/>';
//        print_r($params);

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
            'user_id' => $this->user_id,
            //'reference_id' => $this->reference_id,
            //'province' => $this->province,
            //'city' => $this->city,
            //'area' => $this->area,
            //'acreage' => $this->acreage,
            //'led_account' => $this->led_account,
            //'mirror_account' => $this->mirror_account,
            'channel' => $this->channel,
            //'apply_time' => $this->apply_time,
            'status' => $this->status,
            //'profit' => $this->profit,
            //'install_time' => $this->install_time,
            //'auditing_time' => $this->auditing_time,
            //'mod_count' => $this->mod_count,
        ]);
        //店铺面积、镜面数量、申请数量排序
        $order = '';
        if($this->acreage !== ''){
            if($this->acreage == 0){
                $order .= 'acreage desc,';
            }else{
                $order .= 'acreage asc,';
            }
        }
        if($this->mirror_account !== ''){
            if($this->mirror_account == 0){
                $order .= 'mirror_account desc,';
            }else{
                $order .= 'mirror_account asc,';
            }
        }
        if($this->led_account !== ''){
            if($this->led_account == 0){
                $order .= 'led_account desc,';
            }else{
                $order .= 'led_account asc,';
            }
        }
        if($order){
            $order = rtrim($order,',');
            $query->orderBy($order);
        }
        //入驻方式
        if($this->reference_id){
            if($this->reference_id == 1){
                $query->andWhere(['not',['reference_id'=>null]]);
            }else{
                $query->andWhere(['reference_id'=>null]);
            }
        }
        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'introducer', $this->introducer])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'identity_card_num', $this->identity_card_num])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'registration_mark', $this->registration_mark])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'identity_card_front', $this->identity_card_front])
            ->andFilterWhere(['like', 'identity_card_back', $this->identity_card_back])
            ->andFilterWhere(['like', 'business_licence', $this->business_licence])
            ->andFilterWhere(['like', 'shop_image', $this->shop_image])
            ->andFilterWhere(['like', 'install_image', $this->install_image])
            ->andFilterWhere(['like', 'install_name', $this->install_name])
            ->andFilterWhere(['like', 'install_mobile', $this->install_mobile])
            ->andFilterWhere(['like', 'check_name', $this->check_name])
            ->andFilterWhere(['like', 'check_mobile', $this->check_mobile])
            ->andFilterWhere(['like', 'install_position', $this->install_position])
            ->andFilterWhere(['like', 'auditing_user', $this->auditing_user])
            ->andFilterWhere(['like', 'fail_reason', $this->fail_reason]);

        return $dataProvider;
    }
}
