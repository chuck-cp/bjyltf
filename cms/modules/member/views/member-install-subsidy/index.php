<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\MemberInstallSubsidySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '安装人补贴';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-install-subsidy-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>当前选择日期：
        <?if($searchModel->create_at):?>
            <?echo $searchModel->create_at;?>
        <?else:?>
            <?echo date('Y-m-d',strtotime('-1 day'))?>
        <?endif;?>

    </p>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '安装人电话',
                'value' => function($model){
                    return $model->memberNameMobile['mobile'];
                }
            ],
            [
                'label' => '安装人姓名',
                'value' => function($model){
                    return $model->memberNameMobile['name'];
                }
            ],
            [
                'label' => '常驻地址',
                'value' => function($model){
                    return $model->memberArea['live_area_name'];
                }
            ],
            [
                'label' => '本日安装店铺',
                'value' => function($model){
                    return $model->install_shop_number;
                }
            ],
            [
                'label' => '本日安装屏幕',
                'value' => function($model){
                    return $model->install_screen_number;
                }
            ],
            [
                'label' => '安装人本日安装收入（元）',
                'value' => function($model){
                    return ToolsClass::priceConvert($model->income_price);
                }
            ],
            [
                'label' => '本日指派店铺',
                'value' => function($model){
                    return $model->assign_shop_number;
                }
            ],
            [
                'label' => '本日指派屏幕',
                'value' => function($model){
                    return $model->assign_screen_number;
                }
            ],
            [
                'label' => '本日补贴额',
                'value' => function($model){
                    return ToolsClass::priceConvert($model->subsidy_price);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {subsidy}',
                'buttons' => [
                    'view' => function($url,$model){
                        return Html::a('查看',['/member/member/install-information','id'=>$model->install_member_id]);
                    },
                    'subsidy' => function($url,$model){
                        return html::a('补贴当日收入','javascript:void(0);',['class'=>'subsidy','member_name'=>$model->memberNameMobile['name'],'member_mobile'=>$model->memberNameMobile['mobile'],'income_price'=>ToolsClass::priceConvert($model->income_price),'id'=>$model->id,'member_id'=>$model->install_member_id,'subsidy_price'=>ToolsClass::priceConvert($model->subsidy_price)]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //金额四舍五入
    function decimal(num,v){
        var vv = Math.pow(10,v);
        return Math.round(num*vv)/vv;
    }
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
        $('.subsidy').click(function(){
            var name = $(this).attr('member_name');
            var mobile = $(this).attr('member_mobile');
            var income_price = $(this).attr('income_price');//当日收入
            var member_id = $(this).attr('member_id');//当日收入
            var id = $(this).attr('id');
            var l_subsidy_price = $(this).attr('subsidy_price');
           /* alert(name);
            alert(moblie);
            alert(income_price);return false;*/
            pg = layer.open({
                type: 1,
                title:'补贴当日收入',
                skin: 'layui-layer-rim', //加上边框
                area: ['500px', '400px'], //宽高
                shadeClose: true,
                content:'<div style="text-align: center; margin-top:30px;"><label>请填写补贴金额</label></div><div style="text-align: center;margin-top:10px;"><label><input style="width:200px;height: 40px;border-radius:5px; border:solid 1px #ccc;text-align:center;"  type="text" name="subsidy_price"></label></div><div style="text-align: center; margin-top:30px;"><label>请填写补贴原因（必填）</label></div><div style="text-align: center;margin-top:10px;"><label><textarea style="width:200px;height: 80px;border-radius:5px; border:solid 1px #ccc;padding-top: 5px;"  type="text" name="subisdy_desc" ></textarea></label></div><div style="text-align: center; margin-top:30px;"><label><button type="button" style="margin-right:30px;" class="btn btn-primary qx" data-type="rebut">取消</button><button type="button" class="btn btn-primary qd" data-type="rebut">确定</button></label><div>',
            });
            $('.qx').click(function(){
                layer.closeAll();
            })
            $('.qd').click(function(){
                var subsidy_price = $.trim($('input[name="subsidy_price"]').val());//补贴金额
                var subisdy_desc  = $.trim($('textarea[name="subisdy_desc"]').val());
                var ArrMen= subsidy_price.split(".");    //截取字符串
                console.log(subsidy_price);
                if(ArrMen.length==2){
                    if(ArrMen[1].length>2){    //判断小数点后面的字符串长度
                        layer.msg('小数点后最多为两位',{icon:2});
                        return false;
                    }
                }
                if(!subsidy_price||subsidy_price==''){
                    layer.msg('请填写补贴金额',{icon:2});
                    return false;
                }
                if(subsidy_price<0.01){
                    layer.msg('金额不能小于0',{icon:2});
                    return false;
                }
                if(isNaN(subsidy_price)){
                    layer.msg('必须为数字',{icon:2});
                    return false;
                }
                if(!subisdy_desc || subisdy_desc==''){
                    layer.msg('请填写补贴原因',{icon:2});
                    return false;
                }else if(subisdy_desc.length>100){
                    layer.msg('补贴原因不得超过100个字符',{icon:2});
                    return false;
                }
                layer.closeAll();
                var a_sum_price=Number(subsidy_price)+Number(income_price)+Number(l_subsidy_price);
                var sum_price=decimal(a_sum_price,2); //当日总收入
                var a_sum_subsidy_price=Number(subsidy_price)+Number(l_subsidy_price);
                var sum_subsidy_price=decimal(a_sum_subsidy_price,2);//当日补贴金额
                pg = layer.open({
                    type: 1,
                    title:'确认补贴金额',
                    skin: 'layui-layer-rim', //加上边框
                    area: ['450px', '600px'], //宽高
                    shadeClose: true,
                    content:'<div style="text-align: center; margin-top:30px;"><label>核对补贴信息</label></div><div style="text-align: center; margin-top:10px;"><label>补贴信息一旦确认不可撤回或更改</label></div><div style="text-align: center; margin-top:5px;"><label>请仔细检查确认</label></div><div style="width:200px;margin:0 auto"><div style="text-align: center; margin-top:30px;overflow: hidden"><label style="float:left ;">安装人：</label><label style="float: right">'+name+'</label></div><div style="text-align: center; margin-top:10px;overflow: hidden"><label style="float:left ;">安装人电话：</label><label style="float: right">'+mobile+'</label></div><div style="text-align: center; margin-top:10px;overflow: hidden"><label style="float:left ;">当日收入：</label><label style="float: right">'+income_price+'</label></div><div style="text-align: center; margin-top:10px;overflow: hidden"><label style="float:left ;">当日补贴金额：</label><label style="float: right">'+sum_subsidy_price+'</label></div><div style="text-align: center; margin-top:10px;overflow: hidden"><label style="float:left ;">当日总收入：</label><label style="float: right">'+sum_price+'</label></div><div style="text-align: center; margin-top:30px;"><label>补贴原因</label></div><div style="text-align: center; margin-top:10px;"><label>'+subisdy_desc+'</label></div><div style="margin-top:30px;"><label><button type="button" style="margin-right:30px;" class="btn btn-primary qxbt" data-type="rebut">取消补贴</button><button type="button" class="btn btn-primary qrbt" data-type="rebut" >确认补贴</button></label></div></div>',
                });
                $('.qxbt').click(function(){
                    layer.closeAll();
                })
                $('.qrbt').click(function(){
                    var layerMsg = layer.load('请稍后...',{
                        icon: 0,
                        shade: [0.1,'black']
                    });
                    $.ajax({
                        url : '<?=\yii\helpers\Url::to(['amountofsubsidies'])?>',
                        type : 'POST',
                        dataType : 'json',
                        data : {'name':name,'mobile':mobile,'income_price':income_price,'id':id,'subsidy_price':subsidy_price,'member_id':member_id,'subisdy_desc':subisdy_desc},
                        success:function (data){
                            if(data.code==1){
                                layer.msg(data.msg, {
                                    icon: 1,
                                    time: 2000,
                                    end:function(){
                                        window.parent.location.reload();
                                    }
                                });
                            }else{
                                layer.msg(data.msg);
                                layer.closeAll();
                            }
                        },
                        error:function () {
                            layer.msg('操作失败！');
                            layer.closeAll();
                        }
                    });
                })
            })
        })


    })

</script>
