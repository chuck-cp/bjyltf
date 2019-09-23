<?php
namespace cms\core;

use yii\base\Exception;
use yii\mongodb\Query;

class MongoActiveRecord extends CmsActiveRecord
{
    /*
     * 查询mongo单条数据
     * @param collection string 集合名称
     * @param param array 其他参数
     * */
    public function mongoFindOne($collection,$param){
        $where = isset($param['where']) ? $param['where'] : [];
        $select = isset($param['select']) ? $param['select'] : [];
        $query = new Query();
        $result = $query->select($select)->where($where)->from($collection)->one();
        return $result;
    }

    /*
     * 删除mongo数据
     * @param collection string 集合名称
     * @param where array 删除条件
     * */
    public function mongoDelete($collection,$where){
        $collection = \Yii::$app->mongodb->getCollection($collection);
        $result = $collection->remove($where);
        return $result;
    }

    /*
     * 查询mongo多条数据
     * @param collection string 集合名称
     * @param where array 搜索条件
     * @param param array 其他参数
     * */
    public function mongoFindAll($collection,$where,$params=[]){
        try{
            $query = new Query();
            $select = isset($params['select']) ? $params['select'] : [];
            $limit = isset($params['limit']) ? $params['limit'] : '';
            $orderBy = isset($params['orderBy']) ? $params['orderBy'] : [];
            $result = $query->select($select)->where($where)->from($collection)->orderBy($orderBy)->limit($limit)->all();
            return $result;
        }catch (Exception $e){
            //print_r($e->getMessage());exit;
            return false;
        }
    }

    /*
     * 修改mongo数据
     * @param collection string 集合名称
     * @param update array 更新内容
     * @param where array 更新条件
     * */
    public function mongoUpdate($collection,$update,$where){
        $collection = \Yii::$app->mongodb->getCollection($collection);
        return $collection->update($where,$update);
    }

    /*
     * 插入mongo数据
     * @param collection string 集合名称
     * @param date array 插入内容
     * */
    public function mongoInsert($collection,$data){
        $collection = \Yii::$app->mongodb->getCollection($collection);
        return $collection->insert($data);
    }
}
