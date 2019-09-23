<?php

namespace cms\modules\config\controllers;

use cms\core\CmsController;
use cms\models\AdvertPosition;
use cms\models\AdvertPrice;
use cms\modules\config\models\search\AdvertPositionSearch;
use cms\modules\config\models\search\AdvertPriceSearch;
use common\libs\ToolsClass;
use Yii;
use cms\modules\config\models\AdvertConfig;
use cms\modules\config\models\search\AdvertConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdvertConfigController implements the CRUD actions for AdvertConfig model.
 */
class AdvertConfigController extends CmsController
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
     * Lists all AdvertConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdvertConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdvertConfig model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    //广告参数添加
    public function actionConfigcreate($type)
    {
        $model = new AdvertConfigSearch();
        if ($model->load(Yii::$app->request->post())) {
            $array = Yii::$app->request->post()['AdvertConfigSearch'];
            $upafter = AdvertConfig::afterAdvertConfig($array);
            if($upafter){
                if($array['type']==2){
                    return $this->success('添加成功',['format']);
                }elseif($array['type']==3){
                    return $this->success('添加成功',['duration']);
                }elseif($array['type']==4){
                    return $this->success('添加成功',['measure']);
                }else{
                    return $this->success('添加成功',['place']);
                }
            }else{
                return $this->error('添加失败');
            }
        }

        $dataProvider = $model->search(Yii::$app->request->queryParams);
        return $this->renderPartial('configcreate', [
            'model' => $model,
            'type'=>$type,
            'dataProvider' => $dataProvider,
        ]);
    }

   //广告参数修改
//    public function actionConfigup($id)
//    {
//        $model = $this->findModel($id);
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->renderPartial('configup', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Deletes an existing AdvertConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdvertConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdvertConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdvertConfig::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //广告位
    public function actionPlace(){
        $searchModel = new AdvertPositionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('place', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //添加广告位
    public function actionPlacecreate()
    {
        $model = new AdvertPositionSearch();//&& $model->save()
        $configModel = new AdvertConfig();
        if ($model->load(Yii::$app->request->post())) {
            $relut =Yii::$app->request->post();
            $arr = $relut['AdvertPositionSearch'];
            $model->key = $arr['key'];//广告标识
            $model->type = $arr['type'];//形式
            $model->name = $arr['name'];//广告名
            for($i=1;$i<=$relut['beishu'];$i++){
                $cishu[] = $arr['rate']*$i;
            }
            $model->rate =implode(',',$cishu);//频次
            $geshi = $configModel::find()->where(['shape'=>$arr['type'],'type'=>2])->select('content')->asArray()->all();
            $model->format =implode(',',array_column($geshi,'content'));//格式
            $model->size = $arr['size'];//大小
            $model->spec = implode(',',$arr['spec']);//规格
            $model->time = implode(',',$arr['time']);//时长
            $model->create_user_id = $arr['create_user_id'];
            $model->create_user_name = $arr['create_user_name'];
            if($model->save()){
                return $this->success('添加成功',['place']);
            }else{
                return $this->error('添加失败');
            }
        }

        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $model->type = [1];
        return $this->renderPartial('placecreate', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    //AJAX区分视频/图片
    public function actionAjaxtype(){
        $all = Yii::$app->request->post();
        $time = AdvertConfig::find()->where(['shape'=>$all['typeid'],'type'=>3])->select('id,content,type')->asArray()->all();
        $spec = AdvertConfig::find()->where(['shape'=>$all['typeid'],'type'=>4])->select('id,content,type')->asArray()->all();
        $data['shape'] = $all['typeid'];
        $data['time'] = AdvertPrice::stringasarray(array_column($time,'content'));
        $data['spec'] = AdvertPrice::stringasarray(array_column($spec,'content'));
        return json_encode($data,true);
    }

    //修改广告位
    public function actionPlaceup($id)
    {
        $model = new AdvertPosition();
        $modelid = $model->findOne(['id'=>$id]);
        $configModel = new AdvertConfig();
        if ($model->load(Yii::$app->request->post())) {
            $arr =Yii::$app->request->post()['AdvertPosition'];
            for($i=1;$i<=$arr['beishu'];$i++){
                $cishu[] = $arr['rate']*$i;
            }
            $geshi = $configModel::find()->where(['shape'=>$arr['type'],'type'=>2])->select('content')->asArray()->all();
            $res = $model->updateAll(['key'=>$arr['key'],'type'=>$arr['type'],'name'=>$arr['name'],'rate'=>implode(',',$cishu),'format'=>implode(',',array_column($geshi,'content')),'size'=>$arr['size'],'spec'=>implode(',',$arr['spec']),'time'=>implode(',',$arr['time']),'create_user_id'=>$arr['create_user_id'],'create_user_name'=>$arr['create_user_name']],['id'=>$modelid->id]);
            if($res!==false){
                return $this->success('修改成功',['place']);
            }else{
                return $this->error('修改失败');
            }
        }
        $modelid->time = explode(',',$modelid->time);
        $modelid->spec = explode(',',$modelid->spec);
        $rate = explode(',',$modelid->rate);
        $modelid->rate = $rate[0];
        $modelid->beishu = count($rate);
        return $this->renderPartial('placeup', [
            'model' => $modelid,
        ]);
    }

    //广告价格
    public function actionPrice(){
        $searchModel = new AdvertPriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('price', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //添加广告价格
    public function actionPricecreate()
    {
        $model = new AdvertPriceSearch();
        if($model->load(Yii::$app->request->post())){
            $arr = Yii::$app->request->post()['AdvertPriceSearch'];
//            if(AdvertPrice::findOne(['advert_id'=>$arr['advert_id'],'time'=>$arr['time']])){
//                Yii::$app->getSession()->setFlash('error', '价格已设置');
//                return $this->redirect(['advert-config/price']);
//            }
            $model->price_1 = $arr['price_1']*100;
            $model->price_2 = $arr['price_2']*100;
            $model->price_3 = $arr['price_3']*100;
            $res = $model->save();
            if($res){
                return $this->success('修改成功',['price']);
            }else{
                return $this->error('修改失败');
            }

        }
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $dataone = AdvertPosition::find()->select('id,type,time,spec')->asArray()->one();
        $spec = explode(',',$dataone['spec']);
        $dataone['spec'] = AdvertPrice::stringasarray($spec);
        $time = explode(',',$dataone['time']);
        $dataone['time'] = AdvertPrice::stringasarray($time);
        return $this->renderPartial('pricecreate', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dataone' => $dataone,
        ]);
    }

    //ajax确认是否有提交重复价格
    public function actionAjaxprice(){
        $all = Yii::$app->request->post();
        $res = AdvertPrice::findOne(['advert_id'=>$all['advertid'],'time'=>$all['time']]);
        if(empty($res)){
            return 1;
        }else{
            return 2;
        }
    }
    //AJAX获取广告位信息
    public function actionAjaxplace(){
        $all = Yii::$app->request->post();
        $data = AdvertPosition::find()->where(['id'=>$all['advertid']])->select('id,rate,key,type,time,spec')->asArray()->one();
        $time = explode(',',$data['time']);
        $data['time'] = AdvertPrice::stringasarray($time);
        $spec = explode(',',$data['spec']);
        $data['spec'] = AdvertPrice::stringasarray($spec);
        $rate = explode(',',$data['rate']);
        foreach ($rate as $keyra=>$valuera){
            $dataone['rates'][$keyra] = $valuera/$rate[0];
        }
        $data['rate'] = AdvertPrice::stringasarray($dataone['rates']);
        return json_encode($data,true);
    }

    //修改广告价格
    public function actionPriceup($id){
        $model = new AdvertPrice();
        $modelid = $model->findOne(['id'=>$id]);
        if ($model->load(Yii::$app->request->post())) {
            $arr =Yii::$app->request->post()['AdvertPrice'];
            $arr['price_1'] = $arr['price_1']*100;
            $arr['price_2'] = $arr['price_2']*100;
            $arr['price_3'] = $arr['price_3']*100;
            $res =  $model->updateAll(['price_1'=>$arr['price_1'],'price_2'=>$arr['price_2'],'price_3'=>$arr['price_3'],'create_user_id'=>$arr['create_user_id'],'create_user_name'=>$arr['create_user_name']],['id'=>$arr['id']]);
            if($res!==false){
                return $this->success('操作成功',['advert-config/price']);
            }else{
                return $this->error('操作失败');
            }
        }

        return $this->renderPartial('priceup', [
            'model' => $modelid,
        ]);
    }

    //广告形式
//    public function actionShape(){
//        $searchModel = new AdvertConfigSearch();
//        $searchModel->type = 1;
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        return $this->render('shape', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }

    //广告格式
    public function actionFormat(){
        $searchModel = new AdvertConfigSearch();
        $searchModel->type = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('format', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //广告时长
    public function actionDuration(){
        $searchModel = new AdvertConfigSearch();
        $searchModel->type = 3;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('duration', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //广告尺寸
    public function actionMeasure(){
        $searchModel = new AdvertConfigSearch();
        $searchModel->type = 4;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('measure', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //删除设置
    public function actionDeldate(){
        $all = Yii::$app->request->post();
        if($all['action'] == 'place'){//删除广告位
            $model = AdvertPosition::findOne(['id'=>$all['id']]);
            AdvertPrice::deleteAll(['advert_id'=>$model->id]);
            $respl = $model->delete();
        }elseif($all['action'] == 'price'){//删除价格
            $respl = AdvertPrice::findOne(['id'=>$all['id']])->delete();
        }else{//删除设置
            $model = AdvertConfig::findOne(['id'=>$all['id']]);
            if($model->type == 2){//修改格式
                $respl = $model->delete();
                $geshi = AdvertConfig::find()->where(['shape'=>$model->shape,'type'=>$model->type])->asArray()->all();
                $format = implode(',',array_column($geshi,'content'));
                AdvertPosition::updateAll(['format'=>$format],['type'=>$model->shape]);//更新广告格式
            }elseif($model->type == 3){//修改时长
                //修改广告位
                $time = AdvertPosition::find()->where(['and',['like','time',$model->content],['type'=>$model->shape]])->asArray()->all();
                foreach($time as $keyt=>$valuet){
//                if($valuet['time'] == $model->content){
//                    AdvertPosition::findOne($valuet['id'])->delete();
//                }else{
                    $times = explode(',',$valuet['time']);
                    $newtime =implode(',',array_diff($times, [$model->content]));
                    AdvertPosition::updateAll(['time'=>$newtime],['id'=>$valuet['id']]);
//                }
                }
                //删除广告价格
                AdvertPrice::deleteAll(['type'=>$model->shape,'time'=>$model->content]);
                //删除基础设置
                $respl = $model->delete();
            }elseif($model->type == 4){//修改尺寸
                $spec = AdvertPosition::find()->where(['and',['like','spec',$model->content],['type'=>$model->shape]])->asArray()->all();
                foreach($spec as $keys=>$values){
                    $specs = explode(',',$values['spec']);
                    $newspec =implode(',',array_diff($specs, [$model->content]));
                    AdvertPosition::updateAll(['spec'=>$newspec],['id'=>$values['id']]);
                }
                $respl = $model->delete();
            }
        }
        if($respl){
            return 1;
        }else{
            return 2;
        }
    }

    //判断设置参数是否已存在
    public function actionChecksame(){
        $all = Yii::$app->request->post();
        $res = AdvertConfig::findOne(['type'=>$all['type'],'shape'=>$all['shape'],'content'=>$all['content']]);
        if(empty($res)){
            return 1;
        }else{
            return 2;
        }

    }
}
