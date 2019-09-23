<?php

namespace cms\modules\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\shop\models\ScreenRunTime;

/**
 * ScreenRunTimeSearch represents the model behind the search form of `cms\modules\shop\models\ScreenRunTime`.
 */
class ScreenRunTimeSearch extends ScreenRunTime
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'time'], 'integer'],
            [['date', 'software_number'], 'safe'],
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
        $query = ScreenRunTime::find()->groupBy('software_number')->select('yl_screen_run_time.*,sum(time)as time_sum');

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
        $query->andWhere(['>=','yl_screen_run_time.date',date('Y-m-d',strtotime('-15 day')).' 00:00:00']);
        $query->andWhere(['<=','yl_screen_run_time.date',date('Y-m-d',strtotime('-1 day')).' 23:59:59']);
        $query->andWhere(['shop_id'=>$this->shop_id]);
        // grid filtering conditions
       /* $query->andFilterWhere([
            'id' => $this->id,

            'shop_id' => $this->shop_id,
            'time' => $this->time,
        ]);*/

        $query->andFilterWhere(['like', 'software_number', $this->software_number]);
        return $dataProvider;
    }
    public function viewSearch($params)
    {
        $query = ScreenRunTime::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        /***创建时间***/
        $query->andWhere(['>=','yl_screen_run_time.date',date('Y-m-d',strtotime('-15 day')).' 00:00:00']);
        $query->andWhere(['<=','yl_screen_run_time.date',date('Y-m-d').' 23:59:59']);
        $query->andWhere(['software_number'=>$this->software_number]);
        return $dataProvider;
    }
}
