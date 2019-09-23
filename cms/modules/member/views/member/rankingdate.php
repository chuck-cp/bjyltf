<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
use yii\widgets\LinkPager;
$this->title = '内部业务人员排行';
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">
    <div class="member-search">

        <?php $form = ActiveForm::begin([
//            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <input type="hidden" name="type" value="2">
        <div class="row">
            <div class="col-xs-2 form-group">
                <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label('时间区间'); ?>
            </div>
            <div class="col-xs-2 form-group">
                <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('姓名');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'mobile')->textInput(['class'=>'form-control fm'])->label('电话');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','data'=>'provience','key'=>'area','class'=>'form-control fm'])->label('所属省') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','data'=>'city','key'=>'area','class'=>'form-control fm'])->label('所属市') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','data'=>'area','key'=>'area','class'=>'form-control fm'])->label('所属区') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','data'=>'town','key'=>'area','class'=>'form-control fm'])->label('所属街道') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <table class="table table-striped table-bordered" style="margin-top:10px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>姓名</th>
                <th>手机号</th>
                <th>地区</th>
                <th>业务区域</th>
                <th>联系商家数量</th>
                <th>联系LED数量</th>
                <th>操作</th>
            <tb>
        </thead>
        <tbody>
            <?php foreach ($asArr['data'] as $k=>$v):?>
                <tr>
                    <td><?php echo $v['id']?></td>
                    <td><?php echo $v['memInfo']['name']?></td>
                    <td><?php echo $v['memInfo']['mobile']?></td>
                    <td><?php echo $v['memInfo']['area_name']?></td>
                    <td><?php echo SystemAddress::getAreaByIdLen($v['memInfo']['admin_area'],9); ?></td>
                    <td><?php echo $v['sum(shop_number)']?></td>
                    <td><?php echo $v['sum(screen_number)']?></td>
                    <td><a href="<?=\yii\helpers\Url::to(['/member/member/view'])?>&id=<?echo $v['member_id']?>">查看详情</a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <div style="text-align: center;">
        <?= LinkPager::widget([
            'pagination' => $asArr['pages'],
            'nextPageLabel' => '下一页',
            'prevPageLabel' => '上一页',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
        ]); ?>
    </div>
</div>
<style type="text/css">
    .fm{width: 105px;display: inline-block;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
$(function () {
    //点击切换地区
    $("select[key='area']").change(function () {
        var parent_id = $(this).val();
        var type = $(this).attr('data');
        var selObj = $(this).parents('.col-xs-2');
        selObj.nextAll().find('select').find('option:not(:first)').remove();
        if (!parent_id) {
            return false;
        }
        $.ajax({
            url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
            type: 'POST',
            dataType: 'json',
            data: {'parent_id': parent_id},
            success: function (phpdata) {
                $.each(phpdata, function (i, item) {
                    selObj.next().find('select').append('<option value=' + i + '>' + item + '</option>');
                })
            }, error: function (phpdata) {
                layer.msg('获取失败！');
            }
        })
    })
})
</script>