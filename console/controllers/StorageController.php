<?php

namespace console\controllers;

use cms\modules\ledmanage\ledmanage;
use console\models\Screen;
use console\models\Shop;
use console\models\ShopScreenReplace;
use Stomp\Broker\ActiveMq\Mode\DurableSubscription;
use Stomp\Client;
use Stomp\SimpleStomp;
use Stomp\Transport\Bytes;
use yii\console\Controller;
use yii\console\Exception;
use yii\db\mssql\PDO;
use Yii;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class StorageController extends Controller
{
    /**
     * 获取屏幕状态队列处理
     *     */
    public function actionStorage()
    {
        //循环读取队列
        while(true){
            $stopm=new SimpleStomp(new Client(Yii::$app->params['pushScreenQueue']));
            $stopm->subscribe('/queue/'.Yii::$app->params['pushScreenQueueKey'], 'binary-sub-test', 'client-individual');
           // sleep(5);
            $msg = $stopm->read();//读取队列
            if ($msg != null) {
                echo "[".date('y-m-d h:i:s')."]读取成功.$msg->body\n";
                $data = json_decode($msg->body);
                $stopm->ack($msg);//释放该条队列
                echo "[".date('y-m-d h:i:s')."]处理开始\n";
                //处理队列数据
                if(is_object($data)){
                    \Yii::$app->db->open();//打开数据库链接
                    $screenModel = Screen::find()->where(['software_number'=>$data->deviceNum])->select('id,shop_id,status,offline_time,replace_id')->one();
                    if(!empty($screenModel)){
                        $dbTrans=\Yii::$app->db->beginTransaction();//事物开始
                        try{

                            echo "[".date('y-m-d h:i:s')."]".$data->deviceNum."\n";
                            $shopModel = Shop::find()->where(['id'=>$screenModel->shop_id])->select('id,error_screen_number,screen_status')->one();
                            $error_screen_number = $shopModel->error_screen_number;//失联的屏幕数量
                            if($data->onlineStatus == 0){
                                $screenModel->status = 2;//2为离线状态
                                $error_screen_number = $error_screen_number+1;//失联的屏幕数量
                            }else{
                                if($error_screen_number > 0){
                                    $error_screen_number = $error_screen_number-1;
                                }
                                $screenModel->status = 1;//1为在线状态
                            }
                            //设置店铺屏幕状态
                            if($error_screen_number > 0){
                                $screen_status = 2;
                            }else{
                                $screen_status = 1;
                            }
                            $shopModel->error_screen_number = $error_screen_number;
                            $shopModel->screen_status = $screen_status;
                            $shopModel->save();
                            $screenModel->offline_time = date('Y-m-d H:i:s');
                            $screenModel->save();
                            if($screenModel['replace_id'] > 0){
                                //更换屏幕
                                if(!Screen::find()->where(['replace_id'=>$screenModel['replace_id'],'status'=>0])->count()){
                                    //查询是否有未激活的设备
                                    ShopScreenReplace::updateAll(['screen_status'=>1],['id'=>$screenModel['replace_id'],'screen_status'=>0]);
                                }
                            }
                            echo "[".date('y-m-d h:i:s')."]处理完成\n";
                            $dbTrans->commit();
                        }catch (Exception $e) {
                            echo "[".date('y-m-d h:i:s')."]处理出错\n";
                            \Yii::error($e->getMessage());
                            $dbTrans->rollBack();
                        }
                    }
                    \Yii::$app->db->close();//关闭数据库链接
                }
            }else{
                sleep(1);
            }
            $stopm->unsubscribe('/queue/'.Yii::$app->params['pushScreenQueueKey'], 'binary-sub-test');
        }
    }
}
