<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%system_address}}".
 *
 * @property string $id
 * @property string $name 地区名称
 * @property string $parent_id 上级ID
 * @property int $level 等级
 * @property int $is_buy 该地区是否可以购买广告,有屏幕时可以购买(1、可以买 0、不可以买)
 * @property string $install_at 安装时间
 */
class SystemAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id', 'level', 'is_buy'], 'integer'],
            [['install_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'is_buy' => 'Is Buy',
            'install_at' => 'Install At',
        ];
    }
    /*
    * 查找某地区下的街道数量
    */
    public static function getStreetsById($id){
        if(!$id){
            return 0;
        }
        $len = strlen($id);
        if($len == 9){
            return self::find()->where(['parent_id' => $id, 'level'=>6,'is_buy'=>1])->count();
        }elseif ($len == 7){
            return self::find()->where(['left(parent_id,7)' => $id, 'level'=>6,'is_buy'=>1])->count();
        }elseif ($len == 5){
            return self::find()->where(['left(parent_id,5)' => $id, 'level'=>6,'is_buy'=>1])->count();
        }else{
            return 0;
        }

    }
}
