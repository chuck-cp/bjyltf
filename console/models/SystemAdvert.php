<?php

namespace console\models;

use common\libs\Redis;
use common\libs\ToolsClass;

class SystemAdvert extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_advert}}';
    }

    /*
     * 写入要推送的店铺ID
     * @param shop_id array 店铺ID
     * */
    public function writePushShopId()
    {
        // 推送测试店铺
        $shopModel = SystemTestShop::find()->asArray()->all();
        foreach ($shopModel as $key => $value) {
            try{
                Redis::getInstance(5)->rpush('push_shop_list',json_encode(['head_id'=>0,'shop_id'=>$value['shop_id'],'area_id'=>$value['area_id']]));
            }catch (\Exception $e){
                ToolsClass::printLog('push_shop_advert',$e->getMessage());
            }
        }

        // 推送正式店铺
        $shopModel = Shop::find()->where(['status'=>5])->select('headquarters_id,area,id')->asArray()->all();
        if (empty($shopModel)) {
            return false;
        }
        foreach ($shopModel as $shop) {
            try {
                echo Redis::getInstance(5)->rpush('push_shop_list',json_encode(['head_id'=>$shop['headquarters_id'],'shop_id'=>$shop['id'],'area_id'=>$shop['area']]))."\r\n";
            } catch (\Exception $e) {
                ToolsClass::printLog('write_push_shop_id',$e->getMessage());
            }
        }
        Redis::getInstance(5)->ltrim('push_shop_custom_advert_list',1,0);
        Redis::getInstance(5)->ltrim('push_area_list',1,0);
    }

    // 判断是否有需要推送的广告
    public function isPushSystemAdvert()
    {
        $isThrow = false;     // 是否要给所有设备推送广告
        $dbTrans = \Yii::$app->db->beginTransaction();
        try{
            $tomorrow = date('Y-m-d',strtotime('+1 day'));
            self::updateAll(['throw_status'=>1],['and',['throw_status'=>0],['<=','start_at',$tomorrow]]);
            self::updateAll(['throw_status'=>2],['and',['throw_status'=>1],['<=','end_at',date('Y-m-d')]]);
            // 获取自定义系统广告
            $advertModel = self::find()->select('advert_position_key,advert_type,id,image_url,image_size,image_sha,link_url,advert_time,throw_rate,over_at')->where(['and',['throw_status'=>1],['<=','start_at',$tomorrow],['>=','end_at',$tomorrow]])->asArray()->all();
            // 给广告排期并存储到redis
            $waitAdvert = [];
            // 其他系统自定义广告
            $systemAdvert = [];
            if ($advertModel) {
                foreach ($advertModel as $key => $advert) {
                    if ($advert['advert_type'] == 1) {
                        // 等改日广告
                        if ($advert['link_url']) {
                            $advert['link_url'] = strstr($advert['link_url'],'?') ? $advert['link_url'].'&deadline='.strtotime($advert['over_at']) : $advert['link_url'].'?deadline='.strtotime($advert['over_at']);
                        }
                        $waitAdvert[] = [
                            'id' => $advert['id'],
                            'link_url' => $advert['link_url'],
                            'image_url' => $advert['image_url'],
                            'image_size' => $advert['image_size'],
                            'image_sha' => $advert['image_sha'],
                            'throw_rate' => $advert['throw_rate'],
                            'advert_time' => ToolsClass::minuteCoverSecond($advert['advert_time'])
                        ];
                    } else {
                        // 其他系统自定义广告
                        $systemAdvert[$advert['id']] = [
                            'id' => $advert['id'],
                            'advert_key' => $advert['advert_position_key'],
                            'link_url' => $advert['link_url'],
                            'image_url' => $advert['image_url'],
                            'image_size' => $advert['image_size'],
                            'image_sha' => $advert['image_sha'],
                            'throw_rate' => $advert['throw_rate'],
                            'advert_time' => ToolsClass::minuteCoverSecond($advert['advert_time'])
                        ];
                    }
                }
                $isThrow = \Yii::$app->throw_db->createCommand("update yl_order_throw_program_detail set content = '' where LENGTH(area_id) = 7")->execute();
                if ($systemAdvert) {
                    $advertAreaModel = SystemAdvertArea::find()->where(['advert_id' => array_keys($systemAdvert)])->select('advert_id,area_id')->asArray()->all();
                    if ($advertAreaModel) {
                        $reformAdvert = [];
                        foreach ($advertAreaModel as $value) {
                            if (!isset($reformAdvert[$value['area_id']])) {
                                $reformAdvert[$value['area_id']] = [
                                    'A1' => [
                                        'normal' => [],
                                        'abnormal' => []
                                    ],
                                    'A2' => [
                                        'normal' => [],
                                        'abnormal' => []
                                    ],
                                    'B' => [
                                        'normal' => [],
                                        'abnormal' => []
                                    ],
                                    'C' => [
                                        'normal' => [],
                                        'abnormal' => []
                                    ],
                                    'D' => [
                                        'normal' => [],
                                        'abnormal' => []
                                    ],
                                ];
                            }
                            $advertContent = $systemAdvert[$value['advert_id']];
                            if ($advertContent['throw_rate'] == 0) {
                                $reformAdvert[$value['area_id']][$advertContent['advert_key']]['abnormal'][] = $advertContent;
                            } else {
                                $reformAdvert[$value['area_id']][$advertContent['advert_key']]['normal'][] = $advertContent;
                            }
                        }
                        foreach ($reformAdvert as $area_id => $content) {
                            $content = json_encode($content);
                            $result = \Yii::$app->throw_db->createCommand("insert into yl_order_throw_program_detail (area_id,content) values ({$area_id},'{$content}') ON DUPLICATE KEY UPDATE content = '{$content}'")->execute();
                            if ($result) {
                                $isThrow = true;
                            }
                        }
                    }
                }
            }
            $waitAdvert = json_encode($waitAdvert);
            $lastWaitAdvert = Redis::getInstance(5)->get('system_custom_advert');
            Redis::getInstance(5)->set('system_custom_advert',$waitAdvert);
            $dbTrans->commit();
            # 返回本次的节目单是否有变化
            return ($waitAdvert != $lastWaitAdvert) || $isThrow;
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            ToolsClass::printLog('write_push_shop',$e->getMessage());
            $dbTrans->rollBack();
            return false;
        }
    }
}
