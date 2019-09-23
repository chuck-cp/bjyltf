<?php

namespace cms\modules\notice\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "{{%system_banner}}".
 *
 * @property int $id
 * @property string $image_url 图片地址
 * @property string $link_url 链接地址
 * @property int $sort 排序
 */
class SystemBanner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_url'], 'string', 'max' => 255],
            [['link_url'], 'required'],
            [['link_url'], 'url'],
            [['target'], 'safe'],
            [['sort', 'type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_url' => '图片',
            'link_url' => '链接',
            'target' => '目标地址',
            'sort' => '排序',
            'type' => '类型',
        ];
    }
    /**
     * 保存提交数据并且调换sort
     */
    public static function infoSave($model){
        if($model){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $oldSort = $model->getOldAttribute('sort');
                $oldModel = self::findOne(['sort'=>$oldSort]);
                if($oldModel){

                }
                    $model->sort;
                //$model->save();
            }catch (Exception $e){

            }
        }else{
            return false;
        }
    }
    //判断banner数量是否超5
    public function judgeBannerNumbers($type){
        $numbers = self::find()->where(['type'=>$type])->count();
        return $numbers > 5;
    }

}
