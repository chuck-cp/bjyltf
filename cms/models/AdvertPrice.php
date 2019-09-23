<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "{{%advert_price}}".
 *
 * @property int $id
 * @property int $advert_id 广告ID
 * @property string $time 时长
 * @property string $price 价格
 * @property string $update_at 最后修改时间
 * @property int $create_user_id 操作人姓名
 * @property string $create_user_name 操作人姓名
 */
class AdvertPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advert_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advert_id', 'time', 'price'], 'required'],
            [['advert_id', 'create_user_id'], 'integer','message'=>'输入内容必须为整数'],//'price_1','price_2','price_3',
            [['update_at'], 'safe'],
            [['time'], 'string', 'max' => 5],
            [['create_user_name'], 'string', 'max' => 50],
            [['advert_id', 'time'], 'unique', 'targetAttribute' => ['advert_id', 'time']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'advert_id' => '广告ID',
            'time' => '时长',
            'price_1' => '一级价格',
            'price_2' => '二级价格',
            'price_3' => '三级价格',
            'update_at' => '更新时间',
            'create_user_id' => '操作人ID',
            'create_user_name' => '操作人',
        ];
    }

    //数组转换为关联数组
    public static function stringasarray($array){
        $newarray=[];
        foreach($array as $key=>$value){
            $newarray[$value] = $value;
        }
        return $newarray;
    }
}
