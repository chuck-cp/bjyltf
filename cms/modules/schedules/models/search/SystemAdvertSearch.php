<?php

namespace cms\modules\schedules\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\schedules\models\SystemAdvert;

/**
 * SystemAdvertSearch represents the model behind the search form of `cms\modules\schedules\models\SystemAdvert`.
 */
class SystemAdvertSearch extends SystemAdvert
{
    public $create_at_end;
    public $launch_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'advert_time', 'throw_rate', 'throw_status'], 'integer'],
            [['advert_name', 'advert_position_key', 'shop_name', 'image_url', 'link_url', 'start_at', 'end_at', 'create_at','create_at_end','launch_date'], 'safe'],
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
        $query = SystemAdvert::find();

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
        if(isset($this->launch_date) && $this->launch_date){
            $query->andWhere(['<=','start_at',$this->launch_date]);
            $query->andWhere(['>=','end_at',$this->launch_date]);
        }
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>=','create_at',$this->create_at.' 00:00:00']);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<=','create_at',$this->create_at_end.' 23:59:59']);
        }
        if($this->advert_time){
            $advert_time = $this->advert_time == 1 ? 'advert_time desc' : 'advert_time asc';
            $query->orderBy($advert_time);
        }
        if($this->throw_rate){
            $throw_rate = $this->throw_rate == 1 ? 'throw_rate desc' : 'throw_rate asc';
            $query->orderBy($throw_rate);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'advert_time' => $this->advert_time,
            'throw_rate' => $this->throw_rate,
            'throw_status' => $this->throw_status,
        ]);

        $query->andFilterWhere(['like', 'advert_name', $this->advert_name])
            ->andFilterWhere(['like', 'advert_position_key', $this->advert_position_key])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'link_url', $this->link_url]);
        $query->orderBy('id desc');
        /*$commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();*/
        return $dataProvider;
    }
}
