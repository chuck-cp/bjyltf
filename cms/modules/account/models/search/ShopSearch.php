<?php

namespace cms\modules\account\models\search;

use cms\modules\shop\models\TbInfoApply;
use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use cms\modules\shop\models\Shop;

/**
 * ShopSearch represents the model behind the search form of `cms\modules\shop\models\Shop`.
 */
class ShopSearch extends Shop
{
    public $offset;
    public $limit;
    public $install_name;
    public $parentmember_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'admin_member_id', 'shop_member_id', 'wx_member_id', 'area', 'apply_screen_number', 'screen_number', 'error_screen_number', 'screen_status', 'apply_client', 'mirror_account'], 'integer'],
            [['member_name', 'member_mobile', 'shop_image', 'name','province','city','town','way', 'area_name', 'address', 'create_at','create_at_end','mobile','apply_mobile','apply_name','install_name','install_mobile','parentmember_id','member_id','introducer_member_id','install_member_id','status'], 'safe'],
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
    public function search($params, $export = 0)
    {
        $query = Shop::find()->joinWith('apply')->joinWith('parentMember')->joinWith('shopreplace');

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
        ;
        //echo $this->install_status;

        /***时间***/
        if(isset($this->create_at) && $this->create_at){
            $query->andWhere(['>','yl_shop.create_at',$this->create_at]);
        }
        if(isset($this->create_at_end) && $this->create_at_end){
            $query->andWhere(['<','yl_shop.create_at',$this->create_at_end]);
        }
        //按地区搜索
        $area = max($this->province,$this->city,$this->area,$this->town);
        if($area){
            $query->andWhere(['left(yl_shop.area,'.strlen($area).')' => $area]);
        }
        $query->orderBy('id desc');
        // grid filtering conditions
        $query->andFilterWhere([
            'yl_shop.id' => $this->id,
            'member_id' => $this->member_id,
            'admin_member_id' => $this->admin_member_id,
            'yl_shop_screen_replace.shop_member_id' => $this->shop_member_id,
            'wx_member_id' => $this->wx_member_id,
            'apply_screen_number' => $this->apply_screen_number,
            'screen_number' => $this->screen_number,
            'error_screen_number' => $this->error_screen_number,
            'yl_shop.status' => $this->status,
            'screen_status' => $this->screen_status,
            'acreage' => $this->acreage,
            'apply_client' => $this->apply_client,
            'mirror_account' => $this->mirror_account,
            'yl_member.id' => $this->parentmember_id,
            'member_id' => $this->member_id,
            'introducer_member_id' => $this->introducer_member_id,
            'yl_shop.install_member_id' => $this->install_member_id,
        ]);

        $query->andFilterWhere(['like', 'yl_shop.member_name', $this->member_name])
            ->andFilterWhere(['like', 'yl_shop.member_mobile', $this->member_mobile])
            ->andFilterWhere(['like', 'yl_shop.install_mobile', $this->install_mobile])
            ->andFilterWhere(['like', 'yl_shop.shop_image', $this->shop_image])
            ->andFilterWhere(['like', 'yl_shop.name', $this->name])
            ->andFilterWhere(['like', 'yl_shop.area_name', $this->area_name])
            ->andFilterWhere(['like', 'yl_shop_screen_replace.apply_name', $this->apply_name])
            ->andFilterWhere(['like', 'yl_shop_screen_replace.apply_mobile', $this->apply_mobile])
            ->andFilterWhere(['like', 'yl_shop.install_member_name', $this->install_name])
            ->andFilterWhere(['like', 'yl_shop.address', $this->address]);
        //return $dataProvider;
        /*$commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();*/
        if($export == 1){
            return $query;
        }elseif ($export == 2){
            return $arr['data'] = $query->offset($this->offset)->limit($this->limit)->asArray()->all();
        }
        return $dataProvider;
    }
}
