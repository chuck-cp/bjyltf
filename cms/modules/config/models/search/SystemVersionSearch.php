<?php

namespace cms\modules\config\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\config\models\SystemVersion;

/**
 * SystemVersionSearch represents the model behind the search form of `cms\modules\config\models\SystemVersion`.
 */
class SystemVersionSearch extends SystemVersion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_type', 'upgrade_type'], 'integer'],
            [['version', 'url', 'desc', 'create_at'], 'safe'],
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
        $query = SystemVersion::find();

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
            'app_type' => $this->app_type,
            'upgrade_type' => $this->upgrade_type,
            //'create_at' => $this->create_at,
        ]);
        //按时间排序
        if($this->create_at){
            if($this->create_at == 1){
                $query->orderBy('create_at asc');
            }elseif($this->create_at == 2){
                $query->orderBy('create_at desc');
            }
        }
        $query->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'desc', $this->desc]);
        $query->orderBy('id desc');
        return $dataProvider;
    }
}
