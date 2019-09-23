<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;

/*$this->title = $model->id;*/
$this->params['breadcrumbs'][] = ['label' => '广告查询', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-device-index">
    <?php echo $this->render('layout/tab',['id'=>$id]);?>
    <div class="order-search">
        <?php $form = ActiveForm::begin([
            'action' => ['arrival-rate-report','id'=>$id],
            'method' => 'get',
        ]); ?>
        <div class="container">
            <br />
            <div class="row">
                <div class="col-md-3">
                    <?=$form->field($searchModel,'shop_id')->textInput(['class'=>'form-control fm'])->label('店铺ID:')  ?>
                </div>
                <div class="col-md-3">
                    <?=$form->field($searchModel,'shop_name')->textInput(['class'=>'form-control fm'])->label('店铺名称：')  ?>
                </div>
                <div class="col-md-3">
                    <?=$form->field($searchModel,'arrival_rate')->dropDownList(['1'=>'已全部到达','2'=>'未全部到达'],['class'=>'form-control fm','prompt'=>'全部'])->label('到达率：')  ?>
                </div>
                <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <table class="table table-striped table-bordered" style="margin-top:10px;">
        <thead>
        <tr>
            <th width="15%">地区名称</th>
            <th>街道</th>
            <th>店铺</th>
            <th>店铺ID</th>
            <th>到达率</th>
            <th width="10%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($asArr['data'] as $k=>$v):?>
            <tr>
                <td><?echo $v['area_name'];?></td>
                <td><?echo $v['street_name'];?></td>
                <td><?echo $v['shop_name'];?></td>
                <td><?echo $v['shop_id'];?></td>
                <td><?echo $v['arrival_rate'];?>%</td>
                <td>
                    <a style="cursor:pointer;" id="<?echo $v['_id']?>" class="view">查看详情</a>
                    <?if(!isset($v['maintain_id']) || $v['maintain_id']==0):?>
                        <a style="cursor:pointer;" class="Lower-hair" order_id="<?echo $v['order_id']?>" mongo_id="<?echo (string)$v['_id']?>" shop_id="<?echo $v['shop_id']?>">下发</a>
                    <?else:?>
                        <a style="cursor:pointer;" class="cancel-lower-hair" maintain_id="<?echo $v['maintain_id']?>">取消下发</a>
                    <?endif;?>
                </td>
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
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.view').bind('click', function () {
            var id = $(this).attr('id');
            layer.open({
                type: 2,
                title: '到达率报告详情：',
                shadeClose: true,
                shade: 0.8,
                area: ['40%', '80%'],
                content: '<?=\yii\helpers\Url::to(['arrival-rate-report-view'])?>&id='+id //iframe的url
            });
        })

        //下发
        $('.Lower-hair').click(function(){
            var order_id = $(this).attr('order_id');
            var mongo_id = $(this).attr('mongo_id');
            var shop_id = $(this).attr('shop_id');
            layer.confirm('确定要进行此操作吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.ajax({
                    url:'<?=\yii\helpers\Url::to(['lower-hair'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'mongo_id':mongo_id,'order_id':order_id,'shop_id':shop_id},
                    success:function (data) {
                        if(data.code==1){
                            layer.msg(data.msg,{icon:1});
                            setTimeout(function(){
                                parent.location.reload();
                            },2000);
                        }else{
                            layer.msg(data.msg,{icon:2});
                        }
                    },error:function (error) {
                        layer.msg('操作失败！',{icon:7});
                    }
                });
            });
        })
        //取消下发
        $('.cancel-lower-hair').click(function(){
            var maintain_id = $(this).attr('maintain_id');
            layer.confirm('确定要进行此操作吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.ajax({
                    url:'<?=\yii\helpers\Url::to(['cancel-lower-hair'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'maintain_id':maintain_id},
                    success:function (data) {
                        if(data.code==1){
                            layer.msg(data.msg,{icon:1});
                            setTimeout(function(){
                                parent.location.reload();
                            },2000);
                        }else{
                            layer.msg(data.msg,{icon:2});
                        }
                    },error:function (error) {
                        layer.msg('操作失败！',{icon:7});
                    }
                });
            });
        })
    })

</script>