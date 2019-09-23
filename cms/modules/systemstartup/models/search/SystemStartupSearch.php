<?php

namespace cms\modules\systemstartup\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\systemstartup\models\SystemStartup;

/**
 * SystemStartupSearch represents the model behind the search form about `cms\modules\systemstartup\models\SystemStartup`.
 */
class SystemStartupSearch extends SystemStartup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'visibility', 'create_user_id'], 'integer'],
            [['version', 'start_at', 'end_at', 'start_pic', 'link', 'create_user_name', 'create_at'], 'safe'],
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
        $query = SystemStartup::find();
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
            'visibility' => $this->visibility,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'create_user_id' => $this->create_user_id,
            'create_at' => $this->create_at,
        ]);

        $query->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'start_pic', $this->start_pic])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'create_user_name', $this->create_user_name]);
        $query->orderBy('id desc');
        return $dataProvider;
    }
}
