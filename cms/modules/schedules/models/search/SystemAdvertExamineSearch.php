<?php

namespace cms\modules\schedules\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\schedules\models\SystemAdvertExamine;

/**
 * SystemAdvertSearch represents the model behind the search form of `cms\modules\schedules\models\SystemAdvert`.
 */
class SystemAdvertExamineSearch extends SystemAdvertExamine
{
    public $date_start;
    public $date_end;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_start','date_end'], 'safe'],
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
        $query = SystemAdvertExamine::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if(isset($this->date_end) && $this->date_end){
            $query->andWhere(['<=','date',$this->date_end]);
        }
        if(isset($this->date_start) && $this->date_start){
            $query->andWhere(['>=','date',$this->date_start]);
        }
        $query->orderBy('id desc');
        /*$commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();*/
        return $dataProvider;
    }
}
