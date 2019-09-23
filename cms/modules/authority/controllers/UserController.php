<?php

namespace cms\modules\authority\controllers;

use cms\core\CmsController;
use cms\models\SystemOffice;
use cms\modules\authority\models\AuthArea;
use cms\modules\authority\models\AuthAssignment;
use cms\modules\authority\models\AuthItem;
use cms\modules\authority\models\AuthRule;
use cms\modules\sign\models\SignTeam;
use common\libs\ToolsClass;
use Yii;
use cms\modules\authority\models\User;
use cms\modules\authority\models\search\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\models\SystemAddress;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CmsController
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'ProvinceArr'=>SystemAddress::getAreasByPid(101),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        if(Yii::$app->request->isAjax){
            return User::userAdd(Yii::$app->request->post());
        }
        return $this->renderPartial('create', [
            'offices'=>SystemOffice::find()->asArray()->all(),
            'model' => $model,
            'MemberGroupArr'=> User::MemberGroupArr(),
            //'OrderGroupArr'=> User::OrderGroupArr(),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $item = AuthAssignment::find()->where(['user_id'=>$id])->asArray()->all();
        $itemname = array_column($item,'item_name');
        return $this->renderPartial('update', [
            'userid' => $id,
            'model' => $model,
            'itemname' => $itemname,
            'rulearray' => AuthItem::find()->select('name,description')->asArray()->all(),
        ]);
    }
    //userid绑定角色名
    public function actionBoundUpdate()
    {
        $array = Yii::$app->request->post();
        if(empty($array['item'])){
            $ress = AuthAssignment::deleteAll(['user_id'=>$array['userid']]);//取消关联角色
            if($ress){
                return $this->success('取消关联角色成功',['index']);
            }else{
                return $this->error('取消关联角色失败');
            }
        }else{
            $res = User::userAddItem($array);
            if($res){
                return $this->success('关联角色成功',['index']);
            }else{
                return $this->error('关联角色失败');
            }
        }
    }

    /**
     * 用户修改显示//
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEdituser($id){
        $model = $this->findModel($id);
        return $this->renderPartial('edituser', [
            'model' => $model,
            'offices'=>SystemOffice::find()->asArray()->all(),
            'MemberGroupArr'=> User::MemberGroupArr(),
//            'OrderGroupArr'=> User::OrderGroupArr(),
        ]);
    }
    public function actionEdit(){
        $data=Yii::$app->request->post();
        if(!empty($data)){
            if(!preg_match("/^1[34578]{1}\d{9}$/",$data['User']['phone'])){
                return json_encode(['code' => 3, 'msg' => '手机号格式不正确!']);
            }
            if(!preg_match('/\w+([-+.\']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$data['User']['email'])){
                return json_encode(['code' => 3, 'msg' => '邮箱格式不正确!']);
            }
            if(empty($data['User']['office_name'])){
                $office_id = 0;
            }else{
                $office_id = implode(',',$data['User']['office_name']);
            }
            if(isset($data['User']['area_auth'])){
                if(in_array('101',$data['User']['area_auth'])){
                    $area_auth=101;
                }else{
                    $area_auth=implode(',',$data['User']['area_auth']);
                }
            }else{
                $area_auth=101;
            }
            $id=$data['User']['id'];
            if(empty($data['User']['member_group'])){
                $data['User']['member_group'] = 0;
            }
            if(User::updateAll(['username'=>$data['User']['username'],'true_name'=>$data['User']['true_name'],'phone'=>$data['User']['phone'],'email'=>$data['User']['email'],'office_auth'=>$office_id,'member_group'=>$data['User']['member_group'],'area_auth'=>$area_auth],['id'=>$id])!==false){
                return json_encode(['code' => 1, 'msg' => '修改成功!']);
            }else{
                return json_encode(['code' => 2, 'msg' => '修改失败!']);
            }
        }
    }

    /**
     * 重置密码显示
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionResetpw($id){
        $model = $this->findModel($id);
        return $this->renderPartial('resetpw', [
            'model' => $model,
        ]);
    }

    /**
     * 执行重置密码
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionResetpws(){
        $data=Yii::$app->request->post();
        if(!empty($data)){
            $id=$data['User']['id'];
            if($data['User']['new_password']!==$data['User']['old_cipher']){
                return json_encode(['code'=>3,'msg'=>'两次密码不一致！']);
            }
            if(!$data['User']['new_password'] || !$data['User']['old_cipher']){
                return json_encode(['code'=>3,'msg'=>'所有选项不能为空！']);
            }
            $password_hash=Yii::$app->security->generatePasswordHash($data['User']['new_password']);
            if(User::updateAll(['password_hash'=>$password_hash],['id'=>$id])){
                return json_encode(['code'=>1,'msg'=>'重置完成']);
            }else{
                return json_encode(['code'=>2,'msg'=>'修改失败']);
            }
        }
    }

    public function actionStatus(){
        $data=Yii::$app->request->post();
        if(!empty($data)){
            if($data['status']==1){
                $status=2;
            }else if($data['status']==2){
                $status=1;
            }
            if(User::updateAll(['status'=>$status],['id'=>$data['id']])){
                return json_encode(['code'=>1,'msg'=>'完成']);
            }else{
                return json_encode(['code'=>2,'msg'=>'失败']);
            }
        }
    }


    /**
     * 删除用户
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDel(){
        $id= Yii::$app->request->post('id');
        if($this->findModel($id)->delete()) {
            $res = User::afterDel($id);
            AuthArea::deleteAll(['user_id'=>$id]);
            if ($res!==false) {
                return json_encode(['error' => 1, 'msg' => '删除成功']);
            } else {
                return json_encode(['error' => 2, 'msg' => '删除失败']);
            }
        } else {
            return json_encode(['error' => 2, 'msg' => '删除失败']);
        }
    }


    /**
     * 修改密码
     */
    public function actionModify($id){
        $data=Yii::$app->request->post();
        if(!empty($data)){
            /*if(!LoginForm::validatePassword($data['User']['old_cipher'])){
                return json_encode(['error'=>3,'msg'=>'原始密码错误']);
            }*/
            $password_hash=Yii::$app->security->generatePasswordHash($data['User']['new_password']);
            if(User::updateAll(['password_hash'=>$password_hash],['id'=>$id])){
                return json_encode(['error'=>1,'msg'=>'修改成功，退出登录']);
            }else{
                return json_encode(['error'=>2,'msg'=>'修改失败']);
            }
        }
        $model = $this->findModel($id);
        return $this->render('modify', [
            'model' => $model,
        ]);
    }

    /**
     * 关联地区
     */
    public function actionAuthArea($user_id){
        $AuthAreaOne=AuthArea::findOne(['user_id'=>$user_id]);
        if(Yii::$app->request->isAjax){
            $data=Yii::$app->request->post();
            if(in_array('101',$data['User']['area_auth'])){
                $AreaId=101;
            }else{
                $AreaId=implode(',',$data['User']['area_auth']);
            }
            if($AuthAreaOne){
                $AuthAreaOne->area_id=$AreaId;
                if($AuthAreaOne->save(false))
                    return json_encode(['code'=>1,'msg'=>'操作成功']);
                return json_encode(['code'=>2,'msg'=>'操作失败']);
            }else{
                $AuthArea=new AuthArea();
                $AuthArea->area_id=$AreaId;
                $AuthArea->user_id=$user_id;
                if($AuthArea->save(false))
                    return json_encode(['code'=>1,'msg'=>'操作成功']);
                return json_encode(['code'=>2,'msg'=>'操作失败']);
            }
        }
        $ProvinceArr=SystemAddress::getAreasByPid(101);
        $model = $this->findModel($user_id);
        return $this->renderPartial('auth-area', [
            'model'=>$model ,
            'area_id'=>$AuthAreaOne?explode(',',$AuthAreaOne->area_id):['0'],
            'ProvinceArr'=>$ProvinceArr,
        ]);
    }

    /**
     * 关联签到维护组
     */
    public function actionSignTeam(){
        $user_id = Yii::$app->request->get('user_id');
        if(Yii::$app->request->isAjax){
            $data=Yii::$app->request->post('User');
            if(empty($data['sign_team'])){
                $res = User::updateAll(['sign_team' => 0], ['id' => $data['id']]);
            }else {
                $res = User::updateAll(['sign_team' => implode(',', $data['sign_team'])], ['id' => $data['id']]);
            }
            if($res) {
                return json_encode(['code'=>1,'msg'=>'操作成功']);
            }else{
                return json_encode(['code'=>2,'msg'=>'操作失败']);
            }
        }
        $model = $this->findModel($user_id);
        $teamModel = SignTeam::find()->select('id,team_name,team_type')->asArray()->all();
        $teamModelyw = [];
        $teamModelwh = [];
        foreach ($teamModel as $ke=>$vt){
            if($vt['team_type']==1){
                $teamModelyw[] = $vt;
            }elseif($vt['team_type']==2){
                $teamModelwh[] = $vt;
            }
        }
        return $this->renderPartial('sign-team', [
            'model'=>$model ,
            'teamModelyw'=>$teamModelyw ,
            'teamModelwh'=>$teamModelwh ,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



}
