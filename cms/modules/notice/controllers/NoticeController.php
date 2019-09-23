<?php

namespace cms\modules\notice\controllers;
use cms\modules\shop\models\ShopApply;
use common\libs\RedisClass;
use cms\models\MemberMessage;
use cms\modules\notice\models\SystemBanner;
use cms\models\MemberEquipment;
use common\libs\ToolsClass;
use Yii;
use cms\modules\notice\models\SystemNotice;
use cms\modules\notice\models\search\NoticeSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\member\models\Member;
use cms\core\CmsController;
/**
 * NoticeController implements the CRUD actions for SystemNotice model.
 */
class NoticeController extends CmsController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

    /**
     * Lists all SystemNotice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NoticeSearch();
        $searchModel->status = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SystemNotice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SystemNotice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SystemNotice();
        $transaction = Yii::$app->db->beginTransaction();
        $prr = Yii::$app->request->post();
        $type = isset($prr['type']) ? $prr['type'] : [];
        if($model->load(Yii::$app->request->post())){
            try{
                if(!$message_id = $model->saveNotice($type)){
                    throw new \yii\db\Exception("error");
                }
                RedisClass::rpush('system_push_notice_list',$model->title,1);

//                Yii::$app->jpush->push()
//                    ->setPlatform('all')
//                    ->setNotificationAlert('玉龙传媒')
//                    ->addRegistrationId($push_ids)
//                    ->addIosNotification(' ', 'iOS sound', '+1', true, 'iOS category', array())
//                    ->addAndroidNotification(' ', $model->title, 2, array())
//                    ->setOptions(100000, 3600, null, false)
//                    ->send();

//                // 获取所有开启推送的用户push_id(yl_member_equipment表)
//                $prr = MemberEquipment::find()->where(['push_status'=>1,'status'=>1])->select('push_id')->all();
//                $push_ids = array_filter(array_column($prr,'push_id'));
//                //将push_ids和title推入redis队列
//                if(!empty($push_ids)){
//                    foreach ($push_ids as $pid){
//                        RedisClass::rpush('system_push_notice',json_encode(['push_id'=>$pid,'title'=>$model->title]),4);
//                    }
//                }
//                //每发布一条公告向redis中写入
//                $mrr = Member::find()->where(['status'=>1])->select('id')->asArray()->all();
//                if(!empty($mrr)){
//                    $redisObj = Yii::$app->redis;
//                    $redisObj->select(0);
//                    $initial = 1;
//                    foreach ($mrr as $v){
//                        $redisObj->set('system_notice_'.$message_id.'_'.$v['id'],$initial);
//                    }
//                }
                $transaction->commit();
                return $this->redirect(['index']);
            }catch (\Exception $e){
                $transaction->rollBack();
            }
        }else{
            $model->top = 0;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    /*
     * banner info
     */
   /* public function actionBannerInfo(){
        $bannerInfo = SystemBanner::find()->groupBy('type')->select('count(id) as num')->asArray()->all();
        return $this->renderPartial('banner-info',[
            'bannerInfo' => $bannerInfo,
        ]);
    }*/
    /**
     * Updates an existing SystemNotice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $member=new MemberMessage();
        $data=Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $member::updateAll(['title'=>$data['SystemNotice']['title'],'content'=>$data['SystemNotice']['content']],['notice_id'=>$id]);
                return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SystemNotice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $obj = new SystemNotice();
        $transaction = Yii::$app->db->beginTransaction();
        $res = $obj->deleteNotice($model);
        if($res){
            $transaction->commit();
            return $this->redirect(['index','msg'=>'1']);
        }else{
            $transaction->rollBack();
            return $this->redirect(['view','id'=>$id,'msg'=>'0']);
        }



    }

    /**
     * Finds the SystemNotice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemNotice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemNotice::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
