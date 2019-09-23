<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\libs\ToolsClass;
$this->title = '安装费用补贴';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['install-subsidy'],
        'method' => 'get',
    ]);
    ?>
    <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.area').change(function () {
                var type = $(this).attr('key');
                var selObj = $('[key='+type+']').parents('td');
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
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>时间：</p>
                    <?= $form->field($searchModel, 'create_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker'])->label(false); ?>

                </td>
                <td>
                    <p>.</p>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker'])->label(false); ?>
                </td>
                <td>
                    <p>安装人ID</p>
                    <?=$form->field($searchModel,'install_member_id')->textInput(['placeholder'=>'姓名','class'=>'form-control'])->label(false);?>

                </td>
                <td>
                    <p>安装人姓名</p>
                    <?=$form->field($searchModel,'name')->textInput(['placeholder'=>'姓名','class'=>'form-control'])->label(false);?>

                </td>
                <td>
                    <p>安装人手机号</p>
                    <?=$form->field($searchModel,'mobile')->textInput(['placeholder'=>'手机号','class'=>'form-control'])->label(false);?>
                </td>
                <td>
                    <p></p>
                    <br/>
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                    <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
                </td>
            </tr>
        </table>
    </div>
        <table style="border: 1px solid #dddddd; width: 20%; margin-bottom: 20px;text-align: center;" >
            <tr>
                <th style="text-align: center;" >
                    <p style="margin-top: 10px;font-size: 16px;">
                        补贴总额：<?echo $TotalSubsidy;?>
                    </p>
                </th>
                <th style="text-align: center;" >
                    <p style="margin-top: 10px;font-size: 16px;">
                        共补贴 <?echo $NumberOfSubsidies;?> 人，<? echo $CountSubsidyList?> 次
                    </p>
                </th>
            </tr>
        </table>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '安装人ID',
                'value' => function($searchModel){
                    return $searchModel->install_member_id;
                }
            ],
            [
                'label' => '安装人姓名',
                'value' => function($searchModel){
                    return $searchModel->memberNameMobile['name'];
                }
            ],
            [
                'label' => '安装人手机号',
                'value' => function($searchModel){
                    return  $searchModel->memberNameMobile['mobile'];
                }
            ],

            [
                'label' => '申请补贴日期',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label' => '补贴费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->subsidy_price);
                }
            ],
            [
                'label' => '当日总收入',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->memberIncomePrice['income_price']);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '补贴原因',
                'template' => '{subisdy_desc}',
                'buttons' => [
                    'subisdy_desc' => function($url,$searchModel){
                        return $searchModel->subisdy_desc;
                       // return html::a('关联角色','javascript:void(0);',['class'=>'upplace','id'=>$searchModel->id]);
                    }
                ],
            ],
        ]

    ]);?>
    <?php ActiveForm::end();?>
</div>
<style type="text/css">
    .search tr td{
        background-color: #f2f2f2;
    }
    p{font-weight: bold;}
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
    .action-column{
        width: 50%;
    }
</style>