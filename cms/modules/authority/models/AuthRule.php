<?php

namespace cms\modules\authority\models;

use Yii;

/**
 * This is the model class for table "{{%auth_rule}}".
 *
 * @property string $name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['data'], 'string'],
            [['level'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'unique','message'=>'角色名必须唯一，已存在'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '权限名',
            'data' => '权限描述',
            'level' => '权限等级',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }

    public static function getAllName(){
        $allinfo = self::find()->select('name,data,level')->where(['level'=>1])->asArray()->all();

        $newarray = [];
        foreach($allinfo as $k=>$v){
            $len = strlen($v['name']);
            $newarray[$k]['name'] = $v['name'];
            $newarray[$k]['data'] = $v['data'];
            $newarray[$k]['level'] = $v['level'];
            $newarray[$k]['list']=self::find()->where(['left(name,'.$len.')' => $v['name']])->andWhere(['level'=>2])->select('name,data,level')->asArray()->all();
        }
        $arrays = [];
        foreach($newarray as $newk=>$newv){
            foreach($newv['list'] as $listk=>$lvalue){
                $lentwo = strlen($lvalue['name'])+1;
                $arrays=self::find()->where(['left(name,'.$lentwo.')' => $lvalue['name'].'/'])->andWhere(['level'=>3])->select('name,data,level')->asArray()->all();
                $newarray[$newk]['list'][$listk]['list']=$arrays;
            }
        }
        return $newarray;
    }

    public static function getActionByLevel($level){
        if($level == 1){
            $actions = '模块';
        }elseif($level == 2){
            $actions = '模块/控制器';
        }elseif($level == 3){
            $actions = '模块/控制器/方法';
        }
        return $actions;
    }
}
