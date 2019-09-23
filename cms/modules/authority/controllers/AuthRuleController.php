<?php

namespace cms\modules\authority\controllers;

use cms\core\CmsController;
use Yii;
use cms\modules\authority\models\AuthRule;
use cms\modules\authority\models\search\AuthRuleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthRuleController implements the CRUD actions for AuthRule model.
 */
class AuthRuleController extends CmsController
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

    /**
     * Lists all AuthRule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthRuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthRule model.
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
     * Creates a new AuthRule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthRule();
        if ($model->load(Yii::$app->request->post())) {
            $array = Yii::$app->request->post()['AuthRule'];
            $model->name = $array['name'];
            $model->data = $array['data'];
            $model->level = $array['level'];
            $model->created_at = date("Y-m-d H:i:s");
            if($model->save()){
                return $this->success('添加成功',['index']);
            }else{
                return $this->error('添加失败');
            }
        }
        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthRule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = date("Y-m-d H:i:s");
            if($model->save()){
                return $this->success('修改成功',['index']);
            }else{
                return $this->error('修改失败');
            }
        }
        return $this->renderPartial('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AuthRule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($name)
    {
        $this->findModel($name)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthRule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthRule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthRule::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
