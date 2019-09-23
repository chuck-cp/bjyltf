<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\account\models\search\ScreenRunTimeShopSubsidySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '每月维护费用支出';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="screen-run-time-shop-subsidy-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <table style="border: 1px solid #dddddd; width: 19%; margin-bottom: 20px;text-align: center;" >
        <tr>
            <th style="text-align: center;" >
                <p style="margin-top: 10px;font-size: 16px;">
                    支出总额：<?echo $TotalPrice?>
                </p>
            </th>
        </tr>
    </table>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
                'firstPageLabel'=>'首页',
                'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            [
                'label' => '序号',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '商家编号',
                'value' => function($searchModel){
                    return $searchModel->shop_id;
                }
            ],
            [
                'label' => '商家名称',
                'value' => function($searchModel){
                    return $searchModel->shop_name;
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return $searchModel->area_name;
                }
            ],
            [
                'label' => '法人ID',
                'value' => function($searchModel){
                    return $searchModel->apply_id;
                }
            ],
            [
                'label' => '法人姓名',
                'value' => function($searchModel){
                    return $searchModel->apply_name;
                }
            ],
            [
                'label' => '法人手机号',
                'value' => function($searchModel){
                    return $searchModel->apply_mobile;
                }
            ],
            [
                'label' => '维护费用时间周期',
                'value' => function($searchModel){
                    return substr($searchModel->date,0,4).'年'.substr($searchModel->date,-2).'月';
//                    return date('Y年m月',strtotime($searchModel->date));
                }
            ],
            [
                'label' => '屏幕数量',
                'value' => function($searchModel){
                    return $searchModel->screen_number;
                }
            ],
            [
                'label' => '维护费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->price);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '明细详情',
                'template' => '{detailed}',
                'buttons' => [
                    'detailed' => function($url,$searchModel){
                        return html::a('查看费用明细','javascript:void(0);',['class'=>'view','shop_id'=>$searchModel->shop_id,'date'=>$searchModel->date]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.view').click(function () {
        var shop_id = $(this).attr('shop_id');
        var date = $(this).attr('date');
        var pageup = layer.open({
            type: 2,
            title: '查看费用明细',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '50%'],
            content: '<?=\yii\helpers\Url::to(['/account/screen-run-time-shop-subsidy/view'])?>&shop_id='+shop_id+'&date='+date
        });
    })
</script>
