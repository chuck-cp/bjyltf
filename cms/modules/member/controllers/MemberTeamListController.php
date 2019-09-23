<?php

namespace cms\modules\member\controllers;

use cms\modules\member\models\Member;
use cms\modules\member\models\MemberTeam;
use cms\modules\shop\models\search\ShopSearch;
use cms\modules\shop\models\Shop;
use common\libs\ToolsClass;
use moonland\phpexcel\Excel;
use Yii;
use cms\modules\member\models\MemberTeamList;
use cms\modules\member\models\search\MemberSearchTeamList;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii2tech\csvgrid\CsvGrid;
/**
 * MemberTeamListController implements the CRUD actions for MemberTeamList model.
 */
class MemberTeamListController extends Controller
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
     * Lists all MemberTeamList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberSearchTeamList();
        $member_id = Yii::$app->request->get('team_id');
        $searchModel->team_id = $member_id;
        $teamObj = MemberTeam::findOne($member_id);
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($arr);
        $assign = [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'teamObj' => $teamObj,
            'mobile' => Yii::$app->request->get('mobile'),
            'member_id' => $member_id,
        ];
        if(isset($arr['search']) && $arr['search'] == 0) {
            $tmDataObj = $searchModel->search($arr, 1);
            $exporter = new CsvGrid([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $tmDataObj,
                    'pagination' => [
                        'pageSize' => 100, // export batch size
                    ],
                ]),
            ]);
            if($exporter->dataProvider->getCount() == 0){
                return $this->render('index', $assign);
            }
            $exporter->export()->send(chr(0xEF).chr(0xBB).chr(0xBF).'tm_'.date('Y-m-d').'_'.time().'.csv');
        }

        return $this->render('index', $assign);
    }
    /*
     * 查看安装任务
     */
    public function actionInstallTask(){
        $member_id = Yii::$app->request->get('member_id');
        //1.已安装店铺
        $alreadyShop = Shop::find()->where(['install_member_id'=>$member_id,'status'=>5])->select('`name`,area_name,address,screen_number,install_assign_at')->asArray()->all();
        //2.未安装店铺
        $noInstallShop = Shop::find()->where(['install_member_id'=>$member_id])->andWhere(['<','status',5])->select('`name`,area_name,address,screen_number,install_assign_at')->asArray()->all();
        return $this->renderPartial('install-task',[
            'alreadyShop' => $alreadyShop,
            'noInstallShop' => $noInstallShop,
        ]);
    }
    /*
     * 团队指派记录
     *
     */
    public function actionRecord()
    {
        $member_id = Yii::$app->request->get('team_id');
        $teamObj = MemberTeam::findOne($member_id);
        $searchModel = new ShopSearch();
        $searchModel->install_team_id = $member_id;
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        if(isset($params['search']) && $params['search']=='0'){
            $allData = $searchModel->search($params,1)->asArray()->all();
            $title = ['序号','店铺名称','店铺地址','安装屏幕数','被指派人姓名','被指派人联系方式','安装状态'];
            $csvData = [];
            foreach ($allData as $k => $v){
                $csvData[$k]['id'] = $v['id'];
                $csvData[$k]['name'] = $v['name'];
                $csvData[$k]['address'] = $v['area_name'].$v['address'];
                $csvData[$k]['screen_number'] = $v['screen_number'];
                $csvData[$k]['install_member_name'] = $v['install_member_name'];
                $csvData[$k]['mobile'] = (new MemberTeamList())->memberPhone;
                $csvData[$k]['status'] = $v['status'] == 5 ? '已安装' : '未安装';

            }
            $file_name="zhipai_".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csvData,$title,$file_name);
        }


        return $this->render('record', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member_id' => $member_id,
            'teamObj' => $teamObj,
            'mobile' => Yii::$app->request->get('mobile'),
        ]);


    }
    /**
     * Displays a single MemberTeamList model.
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
     * Creates a new MemberTeamList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MemberTeamList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->team_member_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MemberTeamList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->team_member_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MemberTeamList model.
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
     * Finds the MemberTeamList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MemberTeamList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemberTeamList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
