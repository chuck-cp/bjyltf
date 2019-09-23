<?php

namespace cms\modules\config\models;

use Yii;

/**
 * This is the model class for table "yl_system_bank".
 *
 * @property string $id
 * @property string $bank_name 银行名称
 * @property string $bank_logo 银行的LOGO
 */
class SystemBank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_system_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_name'], 'required'],
            [['bank_name'], 'string', 'max' => 100],
            [['bank_logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bank_name' => '银行名称',
            'bank_logo' => '银行LoGo',
        ];
    }
    //判断banner数量是否超5
    public function BankCount($bank_name){
        return $numbers = self::find()->where(['bank_name'=>$bank_name])->count();
    }
}
