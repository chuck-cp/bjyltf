<?php

use yii\helpers\Html;
use cms\modules\ledmanage\models\SystemDevice;
use cms\modules\ledmanage\models\SystemDeviceFrame;
use yii\widgets\LinkPager;
use cms\models\SystemOffice;

$this->title = '设备列表';
$this->params['breadcrumbs'][] = 'LED库存管理';
$this->params['breadcrumbs'][] = '设备列表';
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();

?>
<?$user_office = explode(',',Yii::$app->user->identity->office_auth);?>
<?if(isset($_GET['kuid'])){
    $_GET['kuid'] = $_GET['kuid'];
}else{
    $_GET['kuid'] = explode(',',Yii::$app->user->identity->office_auth)[0];
}?>
<?$office = SystemOffice::find()->asArray()->all();?>
<div class="system-device-frame-index">
    <h3><select id="device" class="kunum" name="kunum" style="border:none;"/>
        <?foreach($office as $ko=>$vo):?>
            <?if(in_array('0',$user_office)||in_array($vo['id'],$user_office)):?>
                <option value="<?=Html::encode($vo['id'])?>" <?if($vo['id'] == $_GET['kuid']):?> selected="selected"<?endif;?>><?=Html::encode($vo['office_name'])?></option>
            <?endif;?>
        <?endforeach;?>
    </select></h3>
    <?php echo $this->render('office_search', ['model' => $searchModel,'isoutput'=>$isoutput,'kuid'=>$kuid]); ?>
    <table class="table table-striped table-bordered" style="margin-top:10px;">
        <thead>
            <tr>
<!--                <th><input id="check" type="checkbox" class="select-on-check-all" name="selection_all" value="1"></th>-->
                <th>ID</th>
                <th>硬件编号</th>
                <th>厂家名称</th>
                <th>办事处</th>
                <th>仓库</th>
                <th>规格</th>
                <th>品质</th>
                <th>材质</th>
                <th>NFC</th>
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
                <td><?echo array_key_exists($v['manufactor'],$asArr['config']['frame_device_manufactor']) ? $asArr['config']['frame_device_manufactor'][$v['manufactor']] : '未设置';?></td>
                <td>
                    <?if(!empty($v['offices'])):?>
                        <?echo $v['offices']['office_name']?>
                    <?endif;?>
                </td>
                <td>
                    <?echo array_key_exists($v['storehouse'],explode(',',$v['offices']['storehouse'])) ? explode(',',$v['offices']['storehouse'])[$v['storehouse']] : '未设置';?>
                </td>
                <td><?echo array_key_exists($v['device_size'],$asArr['config']['frame_device_size']) ? $asArr['config']['frame_device_size'][$v['device_size']] : '未设置';?></td>
                <td><?echo array_key_exists($v['device_level'],$asArr['config']['frame_device_level']) ? $asArr['config']['frame_device_level'][$v['device_level']] : '未设置';?></td>
                <td><?echo array_key_exists($v['device_material'],$asArr['config']['frame_device_material']) ? $asArr['config']['frame_device_material'][$v['device_material']] : '未设置';?></td>
                <td><?echo SystemDeviceFrame::getIsHave('nfc',$v['nfc']);?></td>
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
                    <a id="<?=Html::encode($v['device_number'])?>" device_number="<?=Html::encode($v['device_number'])?>" href="javascript:void(0);" class="nots">信息</a>
                    <!--<a href="javascript:void(0);" id="<?/* echo $v['id']*/?>" class="del"> 删除</a>-->
                    <?php if($v['is_output']==0):?>
                        <a id="<?echo $v['id']?>"  href="javascript:void(0);" class="out">出库</a>
                    <?php else:?>
                        <span style="color: #999;">出库</span>
                    <?php endif;?>
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
    //切换设备办事处
    $(".kunum").change(function() {
        var kuid = $(".kunum option:checked").val();
        window.location.replace("<?=\yii\helpers\Url::to(['offices'])?>&kuid="+kuid);
    })

    $(document).on('click','#check',function(){
        if($(this).is(":checked")){
            $('.aa').prop('checked',true);
        }else{
            $('.aa').prop('checked',false);
        }
    })
    $(function () {
        //点击修改
        $('.view_this').click(function () {
            var id = $(this).attr('id');
            layer.open({
                type: 2,
                title: '修改',
                shadeClose: true,
                shade: 0.8,
                area: ['40%', '65%'],
                content: '<?=\yii\helpers\Url::to(['/ledmanage/frame/equipment'])?>&id='+id //iframe的url
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
                content: '<?=\yii\helpers\Url::to(['/ledmanage/frame/screen-info'])?>&did='+did+'&device_number='+device_number //iframe的url
            });
        });

        //单个出库
        $('.out').bind('click', function () {
            var is_output = $(this).parents('tr').find('td:eq(4)').html();
            var kuid = $(".kunum option:checked").val();
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
                area: ['80%', '80%'],
                content: '<?=\yii\helpers\Url::to(['/ledmanage/frame/single'])?>&deviceid='+id+'&kuid='+kuid
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
        });

        //批量出库
        $('.batch').bind('click', function () {
            var kuid = $(this).attr('kuid');
            layer.open({
                type: 2,
                title: '批量出库',
                shadeClose: true,
                shade: 0.8,
                area: ['80%', '90%'],
                content: '<?=\yii\helpers\Url::to(['/ledmanage/frame/batchs'])?>&kuid='+kuid
            });
        })

    })
</script>