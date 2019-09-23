<?php

namespace cms\modules\config\models;

use cms\models\AdvertPosition;
use common\libs\ToolsClass;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%advert_config}}".
 *
 * @property int $id
 * @property string $shape 设置参数
 * @property string $content 设置参数
 * @property int $type 配置项（1.形式，2.格式，3.时长，4.尺寸）
 * @property string $update_at 最后修改时间
 * @property int $create_user_id 操作人姓名
 * @property string $create_user_name 操作人姓名
 */
class AdvertConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advert_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shape'], 'required'],
            [['shape', 'type', 'create_user_id'], 'integer'],
            [['update_at'], 'safe'],
            [['content'], 'string', 'max' => 255],
            [['create_user_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shape' => '形式',
            'content' => '参数',
            'type' => '类型',
//            'spec' => '规格',
            'update_at' => '更新时间',
            'create_user_id' => '操作人ID',
            'create_user_name' => '操作人',
        ];
    }
    /*
     * 广告形式
     */
    public static function getAdvertType($type){
        $adverCmodel = new AdvertConfig();
        $advertrarray =$adverCmodel->find()->where(['type'=>$type])->select('id,shape,content')->asArray()->all();
        if(!empty($advertrarray)){
            return ArrayHelper::map($advertrarray,'shape','content');
        }else{
            return array();
        }
    }
    /*
    * 广告时长
    */
    public static function getAdvertTime($type,$shape){
        $adverCmodel = new AdvertConfig();
        $advertrarray =$adverCmodel->find()->where(['type'=>$type,'shape'=>$shape])->select('id,content')->asArray()->all();
        if(!empty($advertrarray)){
            return ArrayHelper::map($advertrarray,'content','content');
        }else{
            return array();
        }
    }
    /*
    * 广告尺寸
    */
    public static function getAdvertSize($type,$shape){
        $adverCmodel = new AdvertConfig();
        $advertrarray =$adverCmodel->find()->where(['type'=>$type,'shape'=>$shape])->select('id,content')->asArray()->all();
        if(!empty($advertrarray)){
            return ArrayHelper::map($advertrarray,'content','content');
        }else{
            return array();
        }
    }

    //修改广告格式同时修改广告位格式设置
    public static function afterAdvertConfig($array){
        if(empty($array)){
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model = new AdvertConfig();
            $model->type = $array['type'];
            $model->shape = $array['shape'];
            $model->content = ToolsClass::trimall($array['content']);
            $model->create_user_id = $array['create_user_id'];
            $model->create_user_name = $array['create_user_name'];
            $model->save();

            $placeModel = new AdvertPosition();
            if($array['type'] == 2) {
                $formats = $placeModel->find()->where(['type' => $array['shape']])->select('id,format')->asArray()->all();
                if (!empty($formats)){
                    foreach ($formats as $keyf => $valuef) {
                        if(!empty($valuef['format'])){
                            $newformats = explode(',',$valuef['format']);
                        }else{
                            $newformats = [];
                        }
                        $newformats[] = ToolsClass::trimall($array['content']);
                        $valuef['format'] = implode(',',$newformats);
                        $placeModel::updateAll(['format' => $valuef['format'], 'create_user_id' => $array['create_user_id'], 'create_user_name' => $array['create_user_name']], ['id' => $valuef['id']]);
                    }
                }
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return false;
        }
    }
}
