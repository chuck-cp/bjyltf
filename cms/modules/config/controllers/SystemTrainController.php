<?php

namespace cms\modules\config\controllers;

use common\libs\ToolsClass;
use Yii;
use cms\modules\config\models\SystemTrain;
use cms\modules\config\models\search\SystemTrainSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\config\models\SystemConfig;

/**
 * SystemTrainController implements the CRUD actions for SystemTrain model.
 */
class SystemTrainController extends Controller
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
     * Lists all SystemTrain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemTrainSearch();
        $dataRes=$searchModel->search(Yii::$app->request->queryParams)->asArray()->all();
        return $this->render('index', [
            'dataRes' => $dataRes,
        ]);
    }

    /**
     * Displays a single SystemTrain model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionEdit($id,$type)
    {
        if($type==1){
            return $this->renderPartial('imgtextup', [
                'model' => $this->findModel($id),
            ]);
        }else{
            return $this->renderPartial('videoup', [
                'model' => $this->findModel($id),
            ]);
        }

    }
    /**
     * Creates a new SystemTrain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * 添加视频
     */
    public function actionCreatevideo()
    {
        $model = new SystemTrain();
        if(Yii::$app->request->isAjax){
            if($model->add(Yii::$app->request->post())){
                return json_encode(['code'=>1,'msg'=>'添加成功']);
            }else{
                return json_encode(['code'=>2,'msg'=>'添加失败']);
            }
        }
        return $this->renderPartial('video', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * 添加图文
     */
    public function actionCreateimgtext(){
        $model = new SystemTrain();
        if(Yii::$app->request->isAjax){
            if($model->add(Yii::$app->request->post())){
                return json_encode(['code'=>1,'msg'=>'添加成功']);
            }else{
                return json_encode(['code'=>2,'msg'=>'添加失败']);
            }
        }
        return $this->renderPartial('imgtext', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing SystemTrain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $data=Yii::$app->request->post();
       // ToolsClass::p($data);die;
        $model = new SystemTrain();
        if($model->edit($data))
            return json_encode(['code'=>1,'msg'=>'修改成功']);
        return json_encode(['code'=>2,'msg'=>'修改失败']);
    }

    /**
     * 上传封面图
     */
    public function actionCovermap(){
        $id='cover_map';
        $model=SystemConfig::findOne(['id'=>$id]);
        if(Yii::$app->request->isAjax){
           $data= Yii::$app->request->post();
           if($data['SystemConfig']['content']){
               if(SystemConfig::updateAll(['content'=>$data['SystemConfig']['content']],['id'=>$id])){
                   return json_encode(['code'=>1,'msg'=>'配置成功']);
               }else{
                   return json_encode(['code'=>2,'msg'=>'配置失败']);
               }
           }else{
               return json_encode(['code'=>3,'msg'=>'请选择封面图']);
           }
        }
        return $this->renderPartial('covermap', [
            'model' => $model,
        ]);
    }

    /**
     * 删除资料
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDel(){
        $id= Yii::$app->request->post('id');
        if($this->findModel($id)->delete()){
            return json_encode(['error'=>1,'msg'=>'删除成功']);
        }else{
            return json_encode(['error'=>2,'msg'=>'删除失败']);
        }
    }

    /**
     * 排序
     */
    public function actionSort(){
        $data=Yii::$app->request->post();
        //ToolsClass::p($data);die;
        foreach ($data['sort'] as $k => $v) {
            SystemTrain::updateAll(['sort'=>$v],['id'=>$k]);
        }
        return json_encode(['code'=>'1','msg'=>'排序成功']);
    }

    /**
     * @return string
     * 启用禁用
     */
    public function actionStatus(){
        $data=Yii::$app->request->post();
        if(!empty($data)){
            if($data['status']==1){
                $status=2;
            }else if($data['status']==2){
                $status=1;
            }
            if(SystemTrain::updateAll(['status'=>$status],['id'=>$data['id']])){
                return json_encode(['code'=>1,'msg'=>'完成']);
            }else{
                return json_encode(['code'=>2,'msg'=>'失败']);
            }
        }
    }

    /**
     * Finds the SystemTrain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SystemTrain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemTrain::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * 获取签名
     * @return string
     */
    public function actionQm() {
        $current = time();
        $expired = $current + 86400;
        $arg_list = [
            "secretId" => Yii::$app->cos_gg->secret_id,
            "currentTimeStamp" => $current,
            "expireTime" => $expired,
            "random" => rand()
        ];
        $orignal = http_build_query($arg_list);
        $tk['token'] = base64_encode(hash_hmac('SHA1', $orignal, Yii::$app->cos_gg->secret_key, true).$orignal);
        return json_encode($tk);
    }
}
