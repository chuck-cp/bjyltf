<?php

namespace cms\modules\account\controllers;

use cms\modules\account\models\search\ScreenRunTimeByMonthSearch;
use cms\modules\account\models\search\ShopApplyBrokerageSearch;
use cms\modules\account\models\ShopApplyBrokerage;
use Yii;
use cms\models\ScreenRunTimeShopSubsidy;
use cms\modules\account\models\search\ScreenRunTimeShopSubsidySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\libs\ToolsClass;
use cms\models\ScreenRunTimeByMonth;
/**
 * ScreenRunTimeShopSubsidyController implements the CRUD actions for ScreenRunTimeShopSubsidy model.
 */
class ScreenRunTimeShopSubsidyController extends Controller
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
     * Lists all ScreenRunTimeShopSubsidy models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScreenRunTimeShopSubsidySearch();
        $searchModel->type=2;
        $map=Yii::$app->request->queryParams;
        if(isset($map['search']) && $map['search'] == 0){
            $DateArr = $searchModel->search($map,1)->asArray()->all();
            if(!empty($DateArr)){
                $title=['序号','商家编号','商家名称','所属地区','法人ID','法人姓名','法人手机号','维护费用时间周期','屏幕数量','应发维护费','维护费用','发放状态'];
                $CsvArr=ScreenRunTimeShopSubsidy::ExportCsv($DateArr);
                $file_name="每月维护费用支出".date("mdHis",time()).".csv";
                ToolsClass::Getcsv($CsvArr,$title,$file_name);
            }
        }
        $dataProvider = $searchModel->search($map);
        //支出总额
        $TotalPrice= ToolsClass::priceConvert(ScreenRunTimeShopSubsidy::find()->sum('price'));
        return $this->render('index', [
            'TotalPrice' => $TotalPrice,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScreenRunTimeShopSubsidy model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($shop_id,$date)
    {
        $searchModel = new ScreenRunTimeByMonthSearch();
        $searchModel->shop_id=$shop_id;
        $searchModel->date=$date;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $TotalPrice= ToolsClass::priceConvert(ScreenRunTimeByMonth::find()->where(['shop_id'=>$shop_id])->sum('price'));
        return $this->renderPartial('view', [
            'TotalPrice'=>$TotalPrice,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }





    /**
     * Finds the ScreenRunTimeShopSubsidy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ScreenRunTimeShopSubsidy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScreenRunTimeShopSubsidy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //第二年每月买断费支出
    public function actionShopApplyBrokerage()
    {
        $searchModel = new ShopApplyBrokerageSearch();
        $searchModel->grant_status=1;
        $map=Yii::$app->request->queryParams;
        if(isset($map['search']) && $map['search'] == 0){
            $DateArr = $searchModel->search($map,1)->asArray()->all();
            if(!empty($DateArr)){
                $title=['ID','店铺ID','店铺名称','店铺所属地区','详细地址','法人ID','法人姓名','法人手机号','维护费用时间周期','屏幕数量','镜面数量','安装完成时间','维护费用','发放状态'];
                $CsvArr=ShopApplyBrokerage::ExportCsv($DateArr);
                $file_name="每月买断费用支出".date("mdHis",time()).".csv";
                ToolsClass::Getcsv($CsvArr,$title,$file_name);
            }
        }
        $dataProvider = $searchModel->search($map);
        return $this->render('brokerage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
