<?php

namespace cms\modules\examine\controllers;

use cms\core\CmsController;
use cms\models\LogExamine;
use cms\modules\examine\models\search\MemberInfoSearch;
use cms\modules\examine\models\ShopScreenReplaceList;
use cms\modules\member\models\Member;
use cms\modules\member\models\MemberInfo;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use common\libs\ToolsClass;
use Yii;
use cms\modules\examine\models\ShopScreenReplace;
use cms\modules\examine\models\search\ShopScreenReplaceSearch;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\libs\RedisClass;
use cms\modules\shop\models\ShopLable;
/**
 * ShopScreenReplaceController implements the CRUD actions for ShopScreenReplace model.
 */
class ShopScreenReplaceController extends CmsController
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
     * Creates a new ShopScreenReplace model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopScreenReplace();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopScreenReplace model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ShopScreenReplace model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ShopScreenReplace model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopScreenReplace the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopScreenReplace::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /****************************************换屏指派****************************************/
    //待指派店铺列表
    public function actionIndex()
    {
        $searchModel = new ShopScreenReplaceSearch();
        $searchModel->zhipai = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'LableArr'=>ShopLable::find()->asArray()->all(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //换屏指派人员列表
    public function actionAssignMember($id){
        $searchModel = new MemberInfoSearch();
        $searchModel->electrician_status=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('assign-member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id'=>$id,
        ]);
    }
    //换屏指派
    public function actionReassign(){
        $data=Yii::$app->request->post();
        if(empty($data)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $ShopDate=ShopScreenReplace::find()->where(['id'=>$data['id']])->select('id,install_member_id,status,replace_screen_number')->asArray()->one();
            if($ShopDate['install_member_id']!=0 || $ShopDate['status']!=0){
                return json_encode(['code'=>3,'msg'=>'该店铺已指派,操作失败']);
            }
            //添加安装人信息
            ShopScreenReplace::updateAll(['install_member_id'=>$data['member_id'],'install_member_name'=>$data['name'],'assign_at'=>date('Y-m-d'),'assign_time'=>date('Y-m-d H:i:s'),'status'=>1],['id'=>$data['id']]);

            //安装人增加待安装记录
            MemberInfo::updateAll(['wait_shop_number'=>new Expression("wait_shop_number + 1"),'wait_screen_number'=>new Expression("wait_screen_number + ".$ShopDate['replace_screen_number'])],['member_id'=>$data['member_id']]);

            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'指派成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }
    //取消指派
    public function actionNoReassign(){
        $data=Yii::$app->request->post();
        if(empty($data)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $rescreenModel = ShopScreenReplace::findOne(['id'=>$data['id']]);
            if($rescreenModel->status == 1){
                //取消减除个人记录数据
                $MemberModel=MemberInfo::findOne(['member_id'=>$rescreenModel->install_member_id]);
                $MemberModel->wait_shop_number-=1;//待更换的店铺数量(一次一家)
                $MemberModel->wait_screen_number-=$rescreenModel->replace_screen_number;//待更换的屏幕数量
                $MemberModel->save();

                //取消更换记录里的安装人
                $rescreenModel->status=0;
                $rescreenModel->install_member_id=0;
                $rescreenModel->install_member_name='';
                $rescreenModel->assign_at='0000-00-00';
                $rescreenModel->assign_time='0000-00-00 00:00:00';
                $rescreenModel->save();
            }else{
                return json_encode(['code'=>3,'msg'=>'该条记录无法取消指派，请确认']);
            }
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'取消成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }
    /****************************************换屏指派end****************************************/

    /****************************************换屏审核*******************************************/
    //换屏审核列表
    public function actionResExamine(){
        $searchModel = new ShopScreenReplaceSearch();
        $searchModel->zhipai = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('res-examine', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //换屏审核详情页
    public function actionView($shopid,$reid){
        $shopModel = Shop::findOne(['id'=>$shopid]);//店铺详情
        $shopappModel = ShopApply::findOne(['id'=>$shopid]);//店铺信息
        $resModel = ShopScreenReplace::findOne(['id'=>$reid]);//换屏信息
        $screenModel = Screen::findAll(['replace_id'=>$reid,'shop_id'=>$shopid]);//屏幕详情
        $wireman = Member::findOne(['id'=>$resModel->install_member_id]);//电工信息
        return $this->render('view', [
            'shopModel'=>$shopModel,
            'shopappModel'=>$shopappModel,
            'resModel'=>$resModel,
            'screenModel'=>$screenModel,
            'wireman'=>$wireman,
            'desc' => LogExamine::find()->where(['foreign_id'=>$reid,'examine_key'=>8])->orderBy('create_at desc')->asArray()->all()
        ]);
    }
    //换屏审核
    public function actionReExamine(){
        $arr = Yii::$app->request->post();
        if(!$arr['resid']){
            return false;
        }
        $Model = ShopScreenReplace::findOne(['id'=>$arr['resid']]);
        if($Model){
            //是否已经审核过
            if($Model->status > 2){
                return json_encode(['code' => 3, 'msg' => '该店铺已审核，请勿重复审核']);
            }
            $status = $arr['type'] == 'pass' ? 1 : 2;
            $desc = isset($arr['desc']) ? $arr['desc'] : '0';
            $res = ShopScreenReplace::examineResScreen($Model,$status,$desc);
            if($res ==1){
                return json_encode(['code' => 1, 'msg' => '审核成功']);
            } else if($res ==3){
                return json_encode(['code' => 3, 'msg' => '屏幕信息重复提交，操作失败']);
            } else {
                return json_encode(['code' => 2, 'msg' => '审核失败']);
            }
        }else{
            return false;
        }
    }
    /****************************************换屏审核end****************************************/
    /****************************************撤销****************************************/
    //撤销维护
    public function actionRepeal(){
        $id = Yii::$app->request->post('id');
        $res = ShopScreenReplace::deleteAll(['id'=>$id]);
        return $res;
    }
    /****************************************撤销*****************************************/
}
