<?php

namespace cms\modules\report\models;

use Yii;
use yii\base\Exception;
use yii\mongodb\Query;
use yii\db\ActiveRecord;
use yii\data\Pagination;
/**
 * This is the model class for table "{{%order_throw_program}}".
 *
 * @property string $id
 * @property string $area_id 节目单地区ID
 * @property string $advert_key 广告标识
 * @property string $date 投放日期
 */
class MongoModels extends ActiveRecord
{
    /*public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }*/

    public $startat;
    public $endat;
    public $shop_name;
    public $shop_id ;




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'advert_key', 'date'], 'required'],
            [['area_id'], 'integer'],
            [['date','startat','endat','shop_id ','shop_name'], 'safe'],
            [['advert_key'], 'string', 'max' => 5],
        ];
    }

    public function search($params,$table,$id){
        $query = new Query();
        $where['shop_id ']=$id;
        $query->from(['local',"$table"]);
        $query->andWhere($where);
        $arr['counts'] = $query->count();
        $arr['pages'] = new Pagination(['totalCount' =>$arr['counts'],'pageSize'=>'2']);
        $arr['data'] = $query->offset($arr['pages']->offset)->limit($arr['pages']->limit)->all();
        return $arr;
    }
}










