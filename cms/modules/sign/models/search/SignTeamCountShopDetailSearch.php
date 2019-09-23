<?php

namespace cms\modules\sign\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\sign\models\SignTeamCountShopDetail;

/**
 * SignTeamCountShopDetailSearch represents the model behind the search form of `cms\modules\sign\models\SignTeamCountShopDetail`.
 */
class SignTeamCountShopDetailSearch extends SignTeamCountShopDetail
{
    public $create_at_end;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'mongo_id', 'sign_id', 'sign_number'], 'integer'],
            [['shop_name', 'create_at','create_at_end'], 'safe'],
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
        $query = SignTeamCountShopDetail::find();

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
        /***创建时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','yl_sign_team_count_shop_detail.create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','yl_sign_team_count_shop_detail.create_at',$this->create_at_end.' 23:59:59']);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'team_id' => $this->team_id,
            'mongo_id' => $this->mongo_id,
            'sign_id' => $this->sign_id,
            'sign_number' => $this->sign_number,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name]);
        $commandQuery = clone $query;
       // echo $commandQuery->createCommand()->getRawSql();
        return $query;
        //return $dataProvider;
    }
}
