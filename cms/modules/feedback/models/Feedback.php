<?php

namespace cms\modules\feedback\models;

use Yii;
use cms\modules\member\models\Member;
/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $question
 * @property string $content
 * @property string $create_at
 */
class Feedback extends \yii\db\ActiveRecord
{
    public $name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'content'], 'required'],
            [['member_id'], 'integer'],
            [['create_at','name'], 'safe'],
            [['question', 'content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'question' => 'Question',
            'content' => 'Content',
            'create_at' => 'Create At',
            'name' => '姓名',
        ];
    }
    /**
     * 关联member
     */
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('id,name,mobile');
    }
}
