<?php

namespace cms\models;

use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "{{%system_account}}".
 *
 * @property string $total 广告总收入
 * @property string $adv_expend 广告总支出
 * @property string $margin 净收益
 */
class SystemAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['total', 'adv_expend'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'total' => 'Total',
            'adv_expend' => 'Adv Expend',
        ];
    }

    public static function getTotalMoney(){
        $arr = self::find()->asArray()->one();
        $arr['margin'] = $arr['total'] - $arr['adv_expend'];
        if(!empty($arr)){
            foreach ($arr as $k => $item) {
                $arr[$k] = ToolsClass::priceConvert($item);
            }
        }else{
            $arr = ['total'=>'0.00', 'adv_expend'=>'0.00', 'margin'=>'0.00'];
        }
        return $arr;
    }

    /**
     * 修改总价格
     * @Author wpw
     */
    public static function getUpdateTotal($price){
        $arr = self::find()->asArray()->one();
        self::updateAll(['total'=>$arr['total']+$price,'id'=>$arr['id']]);
    }
}
