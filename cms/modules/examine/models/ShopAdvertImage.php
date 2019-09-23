<?php

namespace cms\modules\examine\models;

use cms\modules\shop\models\Shop;
use Yii;

/**
 * This is the model class for table "yl_shop_advert_image".
 *
 * @property string $id
 * @property string $shop_id 店铺ID
 * @property int $shop_type 店铺类型(1、自营或租赁 2、连锁店)
 * @property string $image_url
 * @property string $image_size 图片大小
 * @property string $image_sha 图片的密钥
 * @property int $status 发布状态(1、已发布)
 * @property int $sort 排序
 */
class ShopAdvertImage extends \yii\db\ActiveRecord
{
    public $imgnum;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_shop_advert_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'shop_type', 'image_size', 'status', 'sort'], 'integer'],
            [['image_url'], 'required'],
            [['image_url'], 'string', 'max' => 255],
            [['image_sha'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'shop_type' => 'Shop Type',
            'image_url' => 'Image Url',
            'image_size' => 'Image Size',
            'image_sha' => 'Image Sha',
            'status' => 'Status',
            'sort' => 'Sort',
        ];
    }

    //导出数据处理

    public static function getCsv($data){
        foreach ($data as $k=>$v){
            $csv[$k]['id']=$v['shop']['id'];
            $csv[$k]['name']=$v['shop']['name'];
            $csv[$k]['area_name']=$v['shop']['area_name'];
            $csv[$k]['address']=$v['shop']['address'];
            $csv[$k]['screen_number']=$v['shop']['screen_number'];
            $csv[$k]['screen_imgnumnumber']=$v['imgnum'];
        }
        return $csv;
    }

    //商家信息
    public function getShop(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }
    //总部信息
    public function getHead(){
        return $this->hasOne(ShopHeadquarters::className(),['id'=>'shop_id']);
    }

}
