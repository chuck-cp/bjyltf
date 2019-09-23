<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use cms\modules\authority\models\User;
use cms\modules\ledmanage\models\SystemDevice;
use cms\modules\member\models\Member;
use yii\widgets;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\ledmanage\models\search\SystemDeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '设备列表';
$this->params['breadcrumbs'][] = 'LED库存管理';
$this->params['breadcrumbs'][] = '设备列表';
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();

?>
<div class="system-device-index">
    <?php echo $this->render('_search', ['model' => $searchModel,'isoutput'=>$isoutput]); ?>
    <table class="table table-striped table-bordered" style="margin-top:10px;">
        <thead>
            <tr>
<!--                <th><input id="check" type="checkbox" class="select-on-check-all" name="selection_all" value="1"></th>-->
                <th>ID</th>
                <th>硬件编号</th>
                <th>软件编号</th>
                <th>厂家名称</th>
                <th>办事处</th>
                <th>仓库</th>
                <th>规格</th>
                <th>GPS</th>
                <th>入库时间</th>
                <th>入库负责人</th>
                <th>出库时间</th>
                <th>出库负责人</th>
                <th>设备领取人</th>
                <th>是否出库</th>
                <th>批次</th>
                <th>操作</th>
                <th>备注</th>
            </tr>
        </thead>
        <tbody>
        <span>搜索结果共计： <?=Html::encode($asArr['counts'])?> 条</span>
        <?php foreach ($asArr['data'] as $k=>$v):?>
            <tr>
<!--                <td><input class="aa" type="checkbox" name="selection[]" value="--><?//echo $v['id']?><!--"></td>-->
                <td><?echo $v['id'];?></td>
                <td><?echo $v['device_number'];?></td>
                <td><?echo $v['software_id'];?></td>
                <td><?echo array_key_exists($v['manufactor'],$asArr['config']['manufactory']) ? $asArr['config']['manufactory'][$v['manufactor']] : '未设置';?></td>
                <td>
                    <?if(!empty($v['offices'])):?>
                        <?echo $v['offices']['office_name']?>
                    <?endif;?>
                </td>
                <td>
                    <?echo array_key_exists($v['storehouse'],explode(',',$v['offices']['storehouse'])) ? explode(',',$v['offices']['storehouse'])[$v['storehouse']] : '未设置';?>
                </td>
                <td><?echo array_key_exists($v['spec'],$asArr['config']['led_spec']) ? $asArr['config']['led_spec'][$v['spec']] : '未设置';?></td>
                <td><?echo SystemDevice::getIsHave('gps',$v['gps']);?></td>
                <td><?echo $v['create_at'];?></td>
                <td>
                    <?php if($v['in_manager']==0):?>
                        ---
                    <?php else:?>
                        <?php if(empty($asArr['in_manager'])):?>
                        <?php endif;?>
                        <?php if(!in_array($v['in_manager'],array_keys($asArr['in_manager']))):?>
                            ---
                        <?php else:?>

                            <?php echo $asArr['in_manager'][$v['in_manager']]?>

                        <?php endif;?>
                    <?php endif;?>
                </td>
                <td><?echo $v['stock_out_at'];?></td>
                <td>
                    <?php if($v['out_manager']==0):?>
                        ---
                    <?php else:?>
                        <?php /*if(empty($asArr['out_manager'])):*/?>
                        <?php if(!in_array($v['out_manager'],array_keys($asArr['out_manager']))):?>
                            ---
                        <?php else:?>
                            <?php echo $asArr['out_manager'][$v['out_manager']]?>
                        <?php endif;?>
                    <?php endif;?>
                </td>
                <td>
                    <?php if($v['receive_member_id']==0):?>
                        ---
                    <?php else:?>
                        <?php if(!empty($asArr['receive_member_id'])):?>
                            <?php if(!$asArr['receive_member_id'][$v['receive_member_id']]):?>
                                ---
                            <?php else:?>
                                <?php echo $asArr['receive_member_id'][$v['receive_member_id']]?>
                            <?php endif;?>
                        <?php else:?>
                            ---
                        <?php endif;?>
                    <?php endif;?>
                </td>
                <td><?echo SystemDevice::getIsHave('is_output',$v['is_output']);?></td>
                <td><?echo $v['batch']?></td>
                <td>
                    <a href="javascript:void(0);" id="<? echo $v['id']?>" class="view_this">修改</a>
                    <!--<a href="javascript:void(0);" id="<?/* echo $v['id']*/?>" class="del"> 删除</a>-->
                    <a id="<?echo $v['device_number']?>" device_number="<?echo $v['device_number']?>" href="javascript:void(0);" class="nots">信息</a>
                </td>
                <td>
                    <?php if($v['remark']):?>
                        <?php echo $v['remark'];?>
                    <?php else:?>
                        ---
                    <?php endif;?>
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
<script src="/static/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    /*$(".export").click(function(){
        var layerMsg = layer.load('正在导出，请稍后...',{
            icon: 0,
            shade: [0.1,'black']
        });
    })*/

    $(document).on('click','#check',function(){
        if($(this).is(":checked")){
            $('.aa').prop('checked',true);
        }else{
            $('.aa').prop('checked',false);
        }
    })

    $(function () {
        //点击查看详情
        $('.view_this').click(function () {
            var id = $(this).attr('id');
            layer.open({
                type: 2,
                title: '修改',
                shadeClose: true,
                shade: 0.8,
                area: ['40%', '65%'],
                content: '<?=\yii\helpers\Url::to(['/ledmanage/led-manage/equipment'])?>&id='+id //iframe的url
            });
        })
        //点击查看屏幕信息
        $(document).on('click', '.nots', function () {
            var did = $(this).attr('id');
            var device_number = $(this).attr('device_number');
            layer.open({
                type: 2,
                title: '屏幕信息：',
                shadeClose: true,
                shade: 0.8,
                area: ['580px', '59%'],
                content: '<?=\yii\helpers\Url::to(['/ledmanage/led-manage/screen-info'])?>&did='+did+'&device_number='+device_number //iframe的url
            });

        })

        //单个出库
        $('.out').bind('click', function () {
            var is_output = $(this).parents('tr').find('td:eq(4)').html();
            if(is_output == '已出库'){
                layer.msg('该设备已出库！');
                return false;
            }
            var id = $(this).attr('id');
            layer.open({
                type: 2,
                title: '单个出库',
                shadeClose: true,
                shade: 0.8,
                area: ['55%', '55%'],
                content: '<?=\yii\helpers\Url::to(['/ledmanage/led-manage/single'])?>&deviceid='+id
            });
        })

        //删除
        $('.del').click(function(){
            var id = $(this).attr('id');
            layer.confirm('确定删除所选数据吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.ajax({
                    url:'<?=\yii\helpers\Url::to(['delete'])?>',
                    type : 'POST',
                    dataType : 'json',
                    data : {'id':id},
                    success:function (data) {
                        if(data.code==1){
                            layer.msg(data.msg,{icon:1});
                            setTimeout(function(){
                                window.parent.location.reload();
                            },2000);
                        }else{
                            layer.msg(data.msg,{icon:2});
                        }
                    },error:function (error) {
                        layer.msg('操作失败！');
                    }
                });
            });
        })


        //提示层
        $('.batch').bind('click', function () {
            layer.open({
                type: 2,
                title: '批量出库',
                shadeClose: true,
                shade: 0.8,
                area: ['80%', '90%'],
//                content: '<?//=\yii\helpers\Url::to(['/ledmanage/led-manage/batch'])?>//&deviceid='+str
                content: '<?=\yii\helpers\Url::to(['/ledmanage/led-manage/batchs'])?>'
            });
        })

    })
</script>