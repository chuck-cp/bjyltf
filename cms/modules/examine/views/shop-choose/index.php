<?php

use yii\helpers\Html;
use cms\core\CmsGridView;
use \cms\modules\shop\models\ShopUpdateRecord;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\shop\models\search\ShopUpdateRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '店铺变更审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-update-record-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'shop_id',
            'shop_name',
            'apply_name',
            'apply_mobile',
            'identity_card_num',
            'registration_mark',
            'company_name',
            'update_shop_name',
            'update_apply_name',
            'update_apply_mobile',
            'update_identity_card_num',
            'update_registration_mark',
            'update_company_name',
            'create_at',
            [
                'label' => '审核状态',
                'value' => function($searchModel){
                    return ShopUpdateRecord::getStatus($searchModel->examine_status);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/examine/shop-choose/view','id'=>$searchModel->id]);
                    },
                ],
            ],

        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.area').change(function () {
            var type = $(this).attr('key');
            var selObj = $('[key='+type+']').parents('.col-xs-2');
            selObj.nextAll().find('select').find('option:not(:first)').remove();
            var parent_id = $(this).val();
            if(!parent_id){
                return false;
            }
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
                type: 'POST',
                dataType: 'json',
                data:{'parent_id':parent_id},
                success:function (phpdata) {
                    $.each(phpdata,function (i,item) {
                        selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                    })
                },error:function (phpdata) {
                    layer.msg('获取失败！');
                }
            })
        })
    })
</script>