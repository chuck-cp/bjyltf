<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "{{%log_examine}}".
 *
 * @property string $id
 * @property int $examine_key 审核类型(1、店铺 2、身份证3、财务审核)
 * @property string $foreign_id 外键ID
 * @property int $examine_result 审核结果(1、通过 2、驳回)
 * @property string $examine_desc 审核结果描述(如果为审核店铺时,结果为0为审核正常,为大于0的数字时,则说明用户提交的屏幕数量与实际审核通过的数量不一致)
 * @property string $create_user_id 审核人ID
 * @property string $create_user_name 审核人名称
 * @property string $create_at 审核日期
 */
class LogExamine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log_examine}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examine_key', 'foreign_id', 'examine_result', 'create_user_id'], 'integer'],
            [['foreign_id', 'examine_result', 'create_user_id', 'create_user_name'], 'required'],
            [['create_at'], 'safe'],
            [['examine_desc'], 'string', 'max' => 255],
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
            'examine_key' => 'Examine Key',
            'foreign_id' => 'Foreign ID',
            'examine_result' => 'Examine Result',
            'examine_desc' => 'Examine Desc',
            'create_user_id' => 'Create User ID',
            'create_user_name' => 'Create User Name',
            'create_at' => 'Create At',
        ];
    }

    //获取店铺审核通过信息
    public static function getShopCheckMan($shopid,$key)
    {
        $post = self::find()->where(['foreign_id'=>$shopid,'examine_key'=>$key,'examine_result'=>1])->select('create_user_name,create_at')->asArray()->one();
        return $post;
    }
    /**
     *
     */
    public static function getCkeck($shop_id,$key,$result=1){
        return self::find()->where(['foreign_id'=>$shop_id,'examine_key'=>$key,'examine_result'=>$result])->asArray()->one();
    }

    public static function getLogExamin($foreign_id,$key){
        return self::find()->where(['foreign_id'=>$foreign_id,'examine_key'=>$key])->asArray()->all();
    }

    /**
     * 获取驳回原因
     */
    public static function getDismissal($id){
        if($id){
            if(self::findOne(['foreign_id'=>$id])){
                $arr = self::find()->where(['foreign_id'=>$id])->orderBy('id desc')->select('examine_desc')->asArray()->one();
                return $arr['examine_desc'];
            }
            return '';
        }
        return '';
    }
}
