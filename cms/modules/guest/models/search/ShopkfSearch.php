<?php

namespace cms\modules\guest\models\search;

use cms\modules\authority\models\AuthAssignment;
use common\libs\ToolsClass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cms\modules\shop\models\Shop;

/**
 * ShopSearch represents the model behind the search form about `cms\modules\shop\models\Shop`.
 */
class ShopkfSearch extends Shop
{
    public $default_status;
    public $create_at_start;
    public $create_at_end;
    public $install_finish_at_start;
    public $install_finish_at_end;
    public $shop_examine_at_start;
    public $shop_examine_at_end;
    public $apply_name;
    public $apply_mobile;
    public $contacts_name;
    public $contacts_mobile;
    public $assign_status;
    public $company_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'admin_member_id', 'area', 'apply_screen_number', 'screen_number', 'error_screen_number', 'screen_status', 'apply_client','delivery_status','install_member_id','shop_operate_type'], 'integer'],
            [['id','member_name',  'shop_image', 'name','province','city','town','way', 'area_name', 'create_at','create_at_end','mirror_account','status', 'apply_code','examine_user_name','examine_user_group', 'install_member_name','mobile','create_at_start','create_at_end','apply_name','apply_mobile','contacts_name','contacts_mobile','member_mobile','assign_status','install_finish_at_start','install_finish_at_end','shop_examine_at_start','shop_examine_at_end','company_name'], 'safe'],
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
        $query = Shop::find()->joinWith('apply')->joinWith('member');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if (!trim($this->name) && !trim($this->apply_name)  && !trim($this->apply_mobile) && !trim($this->company_name) && !trim($this->contacts_name) && !trim($this->contacts_mobile) && !trim($this->id) && !trim($this->member_name) && !trim($this->member_mobile) && !trim($this->shop_examine_at_start) && !trim($this->shop_examine_at_end)){//什么条件都没有
            $query->Where(['yl_shop.id'=>0]);
        }elseif(trim($this->shop_examine_at_start) && !trim($this->shop_examine_at_end)){//
            echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('请选择结束时间')</script>";
            $query->Where(['yl_shop.id'=>0]);
        }elseif(!trim($this->shop_examine_at_start) && trim($this->shop_examine_at_end)){//
            echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('请选择开始时间')</script>";
            $query->Where(['yl_shop.id'=>0]);
        }elseif ((strtotime($this->shop_examine_at_end)-strtotime($this->shop_examine_at_start))/86400>31){
            echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('所选时间必须在31天之内')</script>";
            $query->Where(['yl_shop.id'=>0]);
        }elseif (!trim($this->name) && !trim($this->apply_name)  && !trim($this->apply_mobile) && !trim($this->company_name) && !trim($this->contacts_name) && !trim($this->contacts_mobile) && !trim($this->id) && !trim($this->member_name) && !trim($this->member_mobile) && trim($this->shop_examine_at_start) && trim($this->shop_examine_at_end)){
            echo "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('审核时间筛选要配合其他条件使用')</script>";
            $query->Where(['yl_shop.id'=>0]);
        }else{
            if(isset($this->shop_examine_at_start) && $this->shop_examine_at_start){
                $query->andWhere(['>=','yl_shop.shop_examine_at',$this->shop_examine_at_start.' 00:00:00']);
            }
            if(isset($this->shop_examine_at_end) && $this->shop_examine_at_end){
                $query->andWhere(['<=','yl_shop.shop_examine_at',$this->shop_examine_at_end.' 23:59:59']);
            }
            $query->andFilterWhere([
                'yl_shop_apply.apply_mobile' => $this->apply_mobile,
                'yl_shop.id' => $this->id,
                'yl_shop_apply.apply_name' => $this->apply_name,
                'yl_shop.member_name' => $this->member_name,
                'yl_shop.member_mobile' => $this->member_mobile,
                'yl_shop_apply.contacts_name' => $this->contacts_name,
                'yl_shop_apply.contacts_mobile' => $this->contacts_mobile,
                'yl_shop.install_member_name' => $this->install_member_name,
                'yl_shop.install_mobile' => $this->install_mobile,
                'yl_shop.install_mobile' => $this->install_mobile,
            ]);
            $query->andFilterWhere(['like', 'yl_shop.name', $this->name]);
            $query->andFilterWhere(['like', 'yl_shop_apply.company_name', $this->company_name]);
        }
        $commandQuery = clone $query;
     //   echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
