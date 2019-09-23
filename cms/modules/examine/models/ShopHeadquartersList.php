<?php

namespace cms\modules\examine\models;

use cms\modules\shop\models\Shop;
use Yii;

/**
 * This is the model class for table "yl_shop_headquarters_list".
 *
 * @property string $id 主键
 * @property int $headquarters_id 总部ID
 * @property string $branch_shop_name 分店的店铺名称
 * @property string $branch_shop_area_id 分店所在的地区ID
 * @property string $branch_shop_area_name 分店所在的读取名称
 * @property string $branch_shop_address 分店所在的详细地址
 */
class ShopHeadquartersList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_shop_headquarters_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['headquarters_id', 'branch_shop_name', 'branch_shop_area_id', 'branch_shop_area_name', 'branch_shop_address'], 'required'],
            [['headquarters_id', 'branch_shop_area_id','shop_id'], 'integer'],
            [['branch_shop_name', 'branch_shop_area_name', 'branch_shop_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'headquarters_id' => 'Headquarters ID',
            'branch_shop_name' => 'Branch Shop Name',
            'branch_shop_area_id' => 'Branch Shop Area ID',
            'branch_shop_area_name' => 'Branch Shop Area Name',
            'branch_shop_address' => 'Branch Shop Address',
        ];
    }

    public static function getShopByListId($id){
        return self::find()->where(['yl_shop_headquarters_list.headquarters_id'=>$id])->joinWith('shop')->asArray()->all();
    }

    public function getShop(){
        return $this->hasOne(Shop::className(),['headquarters_list_id'=>'id'])->select('id,create_at,status,headquarters_list_id');
    }
}
