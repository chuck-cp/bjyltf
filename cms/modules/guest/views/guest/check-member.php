<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
use yii\helpers\Url;
use cms\models\TbInfoRegion;
use cms\modules\member\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '人员查询';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">
    <div class="member-search">

        <?php $form = ActiveForm::begin([
//            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="row">
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('姓名');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'mobile')->textInput(['class'=>'form-control fm'])->label('电话');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            /*['class' => 'yii\grid\CheckboxColumn'],*/
            'id',
            'name',
            'mobile',
            'create_at',
            [
                'label' => '所属地区',
                'value' => function($model){
                    return SystemAddress::getAreaNameById($model->area);
                }

            ],
//            [
//                'label' => '业务区域',
//                'value' => function($model){
//                    return SystemAddress::getAreaByIdLen($model->admin_area,9);
//                }
//            ],
//            [
//                'label' => '收益总额',
//                'value' => function($model){
//                    return number_format($model->memberAccount['count_price']/100,2);
//                }
//            ],
//            [
//                'label' => '联系商家数量',
//                'value' => function($model){
//                    return $model->memCount['shop_number'] ? $model->memCount['shop_number'] : 0;
//                }
//            ],
//            [
//                'label' => '联系LED数量',
//                'value' => function($model){
//                    return $model->memCount['screen_number'] ? $model->memCount['screen_number'] : 0;
//                }
//            ],
//            [
//                'label' => '安装商家数量',
//                'value' => function($model){
//                    return $model->memCount['install_shop_number'] ? $model->memCount['install_shop_number'] : 0;
//                }
//            ],
//            [
//                'label' => '安装LED数量',
//                'value' => function($model){
//                    return $model->memCount['install_screen_number'] ? $model->memCount['install_screen_number'] : 0;
//                }
//            ],
//            [
//                'label' => '是否为内部人员',
//                'value' => function($model){
//                    return $model->inside==1 ? '是':'否';
//                }
//            ],
//            [
//                'label' => '是否为电工',
//                'value' => function($model){
//                    return $model->memIdcardInfo['electrician_examine_status']==1 ? '是' : '否';
//                }
//            ],
//            [
//                'label' => '是否为内部电工',
//                'value' => function($model){
//                    return $model->memIdcardInfo['company_electrician']==1 ? '是' : '否';
//                }
//            ],
//            [
//                'label' => '是否为合作推广人',
//                'value' => function($model){
//                    if($model->inside==1){
//                        return '否';
//                    }else{
//                        if($model->parent_id>0){
//                            return '是';
//                        }else{
//                            return '否';
//                        }
//                    }
//                }
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$model){
                        return Html::a('查看详情',['guest/member-view','id'=>$model->id]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">

</script>