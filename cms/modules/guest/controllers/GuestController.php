<?php

namespace cms\modules\guest\controllers;

use cms\modules\examine\models\ActivityDetail;
use cms\modules\examine\models\Order;
use cms\modules\guest\models\Member;
use cms\modules\guest\models\search\ActivityDetailSearch;
use cms\modules\guest\models\search\MemberSearch;
use cms\modules\guest\models\search\OrderkfSearch;
use common\libs\ToolsClass;
use Yii;
use cms\modules\guest\models\search\ShopkfSearch;
use yii\filters\VerbFilter;
use cms\core\CmsController;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopRemark;
use cms\modules\withdraw\models\search\MemberWithdrawSearch;

use cms\modules\member\models\search\OrderSearch;
use yii\web\NotFoundHttpException;
use cms\models\OrderMessage;
use cms\modules\member\models\OrderDate;
/**
 * ShopController implements the CRUD actions for shop model.
 */
class GuestController extends CmsController
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
     * Lists all shop models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new ShopkfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $shopModel = $this->findModel($id);
        if(!ToolsClass::authCustomToken($shopModel->apply['apply_mobile'].$id,Yii::$app->request->get('token'))){
            return $this->render('//public/auth');
        }
        return $this->render('view', [
            'model' => $shopModel
        ]);
    }

    //保存备注信息
    public function actionRemark(){
        $Data=Yii::$app->request->post();
        $model=new ShopRemark();
        $model->shop_id=$Data['id'];
        $model->content=$Data['remark'];
        $model->create_user_id=Yii::$app->user->identity->getId();
        $model->create_user_name=Yii::$app->user->identity->username;
        $model->create_user_type=2;
        $model->create_at=date('Y-m-d H:i:s');
        if($model->save())
            return json_encode(['code'=>1,'msg'=>'备注添加成功']);
        return json_encode(['code'=>2,'msg'=>'备注添加失败']);
    }

    //提现查询
    public function actionCash(){
        $searchModel = new MemberWithdrawSearch();
        $dataProvider = $searchModel->Cashsearch(Yii::$app->request->queryParams);
        return $this->render('cash', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //推荐查询
    public function actionActivity(){
        $searchModel = new ActivityDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('activity', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //推荐查询详情
    public function actionActivityView($id)
    {
        return $this->render('activity-view', [
            'model' => ActivityDetail::findOne(['id'=>$id]),
        ]);
    }

    //人员查询
    public function actionCheckMember(){
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('check-member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //人员详情
    public function actionMemberView($id)
    {
        return $this->render('member-view', [
            'model' => Member::findOne(['id'=>$id]),
        ]);
    }


    protected function findModel($id)
    {
        if (($model = shop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    //广告查询
    public function actionAdvice()
    {
        $searchModel = new OrderkfSearch();
        $dataProvider = $searchModel->Ordersearch(Yii::$app->request->queryParams);
        return $this->render('advice', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //广告查询详情
    public function actionAdviceView($id)
    {
        return $this->render('advice-view', [
            'model' => $this->findModel($id),
        ]);
    }

    //查看订单详情
    public function actionDetail($id)
    {
        $model = Order::findOne(['id'=>$id]);
        //订单付款信息
        $payMsg = OrderMessage::find()->where(['order_id' => $id, 'type' => 1])->select('desc,create_at')->asArray()->all();
        //订单投放状态信息
        $throwMsg = OrderMessage::find()->where(['order_id' => $id, 'type' => 2])->select('desc,reject_reason,create_at')->asArray()->all();
        //订单时间
        $orderDate = OrderDate::find()->where(['order_id' => $id])->select('start_at,end_at,is_update')->asArray()->one();
        $orderDate['datenum'] = OrderDate::diffBetweenTwoDays($orderDate['start_at'], $orderDate['end_at']);
        return $this->renderPartial('detail', [
            'model' => $model,
            'payMsg' => $payMsg,
            'throwMsg' => $throwMsg,
            'orderDate' => $orderDate,
        ]);
    }

}
