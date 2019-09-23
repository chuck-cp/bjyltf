<?php

namespace cms\modules\authority\controllers;

use common\libs\ToolsClass;
use Yii;
use cms\modules\authority\models\CustomUser;
use cms\modules\authority\models\search\CustomUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomUserController implements the CRUD actions for CustomUser model.
 */
class CustomUserController extends Controller
{
    /**
     * {@inheritdoc}
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

    /**
     * Lists all CustomUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CustomUser model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CustomUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomUser();
        $arr=Yii::$app->request->post();

        if(!empty($arr)){
            if(!$arr['CustomUser']['username'] || !$arr['CustomUser']['name'] || !$arr['CustomUser']['password_hash']){
                return json_encode(['code' => 3, 'msg' => '所有选项不能为空!']);
            }
            $model->username=trim($arr['CustomUser']['username']);
            $model->name=trim($arr['CustomUser']['name']);
            $model->create_at=date('Y-m-d H;i:s');
            $model->auth_key=Yii::$app->security->generateRandomString();
            $model->password_hash = Yii::$app->security->generatePasswordHash(trim($arr['CustomUser']['password_hash']));
            if($model->save())
                return json_encode(['code' => 1, 'msg' => '添加成功!']);
            return json_encode(['code' => 2, 'msg' => '添加失败!']);

        }
        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CustomUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return json_encode(['code'=>1,'msg'=>'完成']);
        }
        return $this->renderPartial('update', [
            'model' => $model,
        ]);
    }


    /**
     * 重置密码
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionResetpw($id){
        $model = $this->findModel($id);
        $data=Yii::$app->request->post();
        if(!empty($data)){
            $model=$this->findModel($id);
            $model->password_hash=Yii::$app->security->generatePasswordHash($data['CustomUser']['new_password']);
            if($model->save())
                return json_encode(['code'=>1,'msg'=>'重置完成']);
            return json_encode(['code'=>2,'msg'=>'重置失败']);
        }
        return $this->renderPartial('resetpw', [
            'model' => $model,
        ]);
    }


    public function actionStatus(){
        $data=Yii::$app->request->post();
        if(!empty($data)){
            if($data['status']==1){
                $status=2;
            }else if($data['status']==2){
                $status=1;
            }
            if(CustomUser::updateAll(['status'=>$status],['id'=>$data['id']])){
                return json_encode(['code'=>1,'msg'=>'完成']);
            }else{
                return json_encode(['code'=>2,'msg'=>'失败']);
            }
        }
    }

    /**
     * Deletes an existing CustomUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDel(){
        $id= Yii::$app->request->post('id');
        if($this->findModel($id)->delete())
            return json_encode(['error' => 1, 'msg' => '删除成功']);
        return json_encode(['error' => 2, 'msg' => '删除失败']);

    }

    /**
     * Finds the CustomUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CustomUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
