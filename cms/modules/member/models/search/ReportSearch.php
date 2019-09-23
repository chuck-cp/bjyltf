<?php

namespace cms\modules\member\models\search;


use console\models\OrderThrowHistory;
use MongoDate;
use Yii;
use yii\base\Exception;
use yii\mongodb\Query;
use yii\db\ActiveRecord;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use cms\modules\member\models\ReportMongo;
use MongoDB\Driver\Command;
use MongoDB\Driver\Manager;
use stdClass;
/**
 * This is the model class for table "{{%order_throw_program}}".
 *
 * @property string $id
 * @property string $area_id 节目单地区ID
 * @property string $advert_key 广告标识
 * @property string $date 投放日期
 */
class ReportSearch extends ReportMongo
{
    /*public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }*/

    public  $shop_id;
    public  $shop_name;
    public  $arrival_rate;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_name', 'arrival_rate', 'shop_id', 'shop_name', 'street_name', 'order_id'], 'safe'],
        ];
    }

    public function getArrivalRateReportSearch($params,$table,$id,$export=0){
        $query = new Query();
        $where['order_id'] =(int)$id;
        if(isset($params['ReportSearch']['shop_id']) && !empty($params['ReportSearch']['shop_id'])){
            $where['shop_id'] = (int)$params['ReportSearch']['shop_id'];
        }
        if(isset($params['ReportSearch']['shop_name']) && !empty($params['ReportSearch']['shop_name'])){
            //店铺名称使用模糊搜索
            $where['shop_name'] = ['$regex' => $params['ReportSearch']['shop_name']];
        }
        if(isset($params['ReportSearch']['arrival_rate']) && !empty($params['ReportSearch']['arrival_rate'])){
            //到达率
            if($params['ReportSearch']['arrival_rate']==1){
                $query->andWhere(['=', 'arrival_rate', 100]);
            }elseif ($params['ReportSearch']['arrival_rate']==2){
                $query->andWhere(['<>', 'arrival_rate', 100]);
            }
        }
        $query->from($table);
        $query->andWhere($where);
        if($export==0){
            $arr['counts'] = $query->count();
            $arr['pages'] = new Pagination(['totalCount' =>$arr['counts'],'pageSize'=>'20']);
            $arr['data'] = $query->offset($arr['pages']->offset)->limit($arr['pages']->limit)->all();
        }else{
            $arr=$query->all();
        }
        return $arr;
    }



    public function getBroadcastRateReportSearch($params,$table,$order_id,$orderData=[]){
        $query = new Query();
        $where['order_id'] =(int)$order_id;
        if(isset($params['ReportSearch']['shop_id']) && !empty($params['ReportSearch']['shop_id'])){
            $where['shop_id'] = (int)$params['ReportSearch']['shop_id'];
        }
        if(isset($params['ReportSearch']['shop_name']) && !empty($params['ReportSearch']['shop_name'])){
            //店铺名称使用模糊搜索
            $where['shop_name'] = ['$regex' => $params['ReportSearch']['shop_name']];
        }

        $query->from($table);
        $query->andWhere($where);
        $arr['counts'] = $query->count();
        $arr['pages'] = new Pagination(['totalCount' =>$arr['counts'],'pageSize'=>'20']);

        //$arr['pages']->offset=2;

        $arr['data'] = $query->offset($arr['pages']->offset)->limit($arr['pages']->limit)->all();
        foreach ($arr['data'] as $k=>$v){
            $arr['data'][$k]['ShouldNumber']=$orderData['rate']*$v['buyed_number'];
        }
        return $arr;
    }


    public function mongoAggregate($pipeline)
    {
        $manager = new Manager(Yii::$app->mongodb->dsn);
        $command = new Command([
            'aggregate' => 'order_throw_history',
            'pipeline' => $pipeline,
            'cursor' => new stdClass,
        ]);
        return $manager->executeCommand('guanggao', $command);
    }


    public function getBroadcastRateReportDateSearch($order_id)
    {
        $throwData = $this->mongoAggregate([
            [
                '$match' => [
                    'order_id' => (int)$order_id,
                ],
            ],
            [
                '$group' => [
                    '_id' =>[
                        'order_id'=>'$order_id',
                        'date' => '$date',
                        'shop_id' => '$shop_id'
                    ],
                    'throw_number' => [
                        '$sum' => '$throw_number'
                    ]
                ],
            ]
        ]);
        $result = [];
        foreach ($throwData as  $key=>$value){
            $id = $value->_id;
            $key = $id->order_id.$id->shop_id.$value->_id->date->toDateTime()->format('Ymd');
            $result[$key] = $value->throw_number;
        }
        return $result;
    }
}










