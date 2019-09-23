<?php

namespace cms\modules\notice\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\notice\models\SystemBanner;

/**
 * BannerSearch represents the model behind the search form of `app\modules\notice\models\SystemBanner`.
 */
class BannerSearch extends SystemBanner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort', 'type'], 'integer'],
            [['image_url', 'link_url'], 'safe'],
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
        $query = SystemBanner::find();

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
            'sort' => $this->sort,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'link_url', $this->link_url]);
        $query->orderBy('sort asc');
        return $dataProvider;
    }
}
