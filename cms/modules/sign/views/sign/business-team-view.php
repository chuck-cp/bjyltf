<?php
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\sign\models\search\SignTeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '团队管理';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="sign-team-index">

    <table class="table table-striped table-bordered" style="margin-top:10px;">
        <thead>
        <tr>
            <th>序号</th>
            <th>用户名</th>
            <th>签到总数</th>
            <th>未签到数（天）</th>
            <th>超时签到数（天）</th>
            <th>未达标数（天）</th>
            <th>早退数（天）</th>
            <th>重复签到数</th>
            <th>重复签到率</th>
            <th>重复店铺数</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($dataArr['data'] as $k=>$v):?>
                <tr>
                    <td><?=$k+1?></td>
                    <td><?=$v['member']['name']?></td>
                    <td><?=$v['sign_number_sum']?></td>
                    <td><?=$v['no_sign_number']?></td>
                    <td><?=$v['late_sign_sum']?></td>
                    <td><?=$v['no_qualified']?></td>
                    <td><?=$v['leave_early_num']?></td>
                    <td><?=$v['repeat_sign_sum']?></td>
                    <td>
                        <?if($v['repeat_sign_sum']==0):?>
                            0%
                        <?else:?>
                            <?=round($v['repeat_sign_sum']/$v['sign_number_sum'],2)*100?>%
                        <?endif;?>

                    </td>
                    <td><?=$v['repeat_shop_sum']?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <div style="text-align: center;">
        <?= LinkPager::widget([
            'pagination' => $dataArr['pages'],
            'nextPageLabel' => '下一页',
            'prevPageLabel' => '上一页',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
        ]); ?>
    </div>
</div>
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
