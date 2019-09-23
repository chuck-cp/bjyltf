<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "yl_shop_lable".
 *
 * @property string $id
 * @property string $title 标签名称
 * @property string $desc 标签的描述
 */
class ShopLable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_shop_lable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'desc'], 'required'],
            [['title'], 'string', 'max' => 30],
            [['desc'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'desc' => 'Desc',
        ];
    }

    /**
     * 列表标签
     */
    public static function listlable($LableListArr){
        $LableArr=ShopLable::find()->asArray()->all();
        foreach($LableArr as $v){
            foreach (explode(',',$LableListArr) as $vv){
                if($v['id']==$vv){
                    $label[]=$v['title'];
                }
            }
        }
        if(!empty($label))
            return implode(',',$label);
        return '';
    }
}
