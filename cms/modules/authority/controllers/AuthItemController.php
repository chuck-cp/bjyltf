<?php

namespace cms\modules\authority\controllers;

use cms\core\CmsController;
use cms\modules\authority\models\AuthAssignment;
use common\libs\ToolsClass;
use xplqcloud\cos\Auth;
use yii\helpers\Url;
use cms\modules\authority\models\AuthItemChild;
use cms\modules\authority\models\AuthRule;
use cms\modules\authority\models\search\AuthRuleSearch;
use Yii;
use cms\modules\authority\models\AuthItem;
use cms\modules\authority\models\search\AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends CmsController
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
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
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
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        if ($model->load(Yii::$app->request->post())) {
            $array = Yii::$app->request->post()['AuthItem'];
            $model->name = $array['name'];
            $model->description = $array['description'];
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
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        if ($model->load(Yii::$app->request->post())) {
            $array = Yii::$app->request->post()['AuthItem'];
            $array['odlname'] = $model->oldAttributes['name'];//旧名字
            $res = AuthItem::itemUpOther($array);
            if($res){
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
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($name)
    {
        $this->findModel($name)->delete();
        AuthItemChild::deleteAll(['parent' => $name]);
        AuthAssignment::deleteAll(['item_name' => $name]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

     //关联权限
     public function actionRelevance(){
         $ruleid = Yii::$app->request->get();
         $Rule = AuthItemChild::find()->where(['parent'=>$ruleid['ruleid']])->asArray()->all();
         $checkRule = array_column($Rule,'child');
         $dataProvider = AuthRule::getAllName();
         return $this->renderPartial('relevance', [
             'dataProvider' => $dataProvider,
             'ruleid' => $ruleid,
             'checkRule' => $checkRule,
         ]);
     }

    //角色关联权限
    public function actionAddItemRule(){
        $rules = Yii::$app->request->post();
        $res = AuthItem::itemaddrule($rules);
        if($res){
            return $this->success('关联成功',['index']);
        }else{
            return $this->error('关联失败');
        }
    }
}
