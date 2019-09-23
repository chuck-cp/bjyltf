<?php

use yii\helpers\Html;
use \cms\models\SystemAddress;
use cms\modules\config\models\SystemAddressLevel;

$this->title = '地区等级设置';
$this->params['breadcrumbs'][] = ['label' => '地区等级设置'];
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<?php //echo $this->render('layout/tab')?>
<style type="text/css">
    .left{width: 98%; padding-top: 10px;}
    .sybox li{
        list-style: none;
        border-bottom: 1px solid #ccc;
        padding-top: 10px;
    }
    .sub{
        text-align: center;
        width: 585px;
    }
    .rel{
        line-height: 35px;
        color: #5e87b0;
    }
    .area,.relate,.del{
        cursor: pointer;
    }
</style>
<h3><?=Html::encode(SystemAddressLevel::getNameByLevel($level))?></h3>
<div class="form-group rel">
    <span class="relate" level="<?=Html::encode($level)?>">关联地区</span>
    <label><input type="checkbox" id="all" value="<?=Html::encode($level)?>">全选</label>
    <span class="del mleft" level="<?=Html::encode($level)?>">删除</span>
</div>
<hr>

<!--<div class="checkbox">-->
<!--    <label><input type="checkbox" id="all" value="1">全选</label>-->
<!--</div>-->
<div class="row">
    <ul class="left zone sybox">
        <?if(empty($arr)):?>
            暂无地区
        <?else:?>
        <? foreach ($arr as $k => $v):?>
            <li>
                <? foreach ($v as $kt => $vt) :?>
                    <?=Html::encode(SystemAddress::getAreaNameById($kt))?>
                    <? foreach ($vt as $kth => $vth) :?>
                        <p style="display: inline-block; padding-left: 10px">
                            <input type="checkbox" value="<?=Html::encode($kth)?>">
                            <span class="area">
                                <?=Html::encode($vth)?>
                            </span>
                        </p>
                    <? endforeach;?><br/>
                <? endforeach;?>
            </li>
        <? endforeach;?>
        <?endif;?>
    </ul>
</div>
<script src="/static/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //点击查看关联地区
        $('.relate').click(function () {
            var level = $(this).attr('level');
            layer.open({
                type: 2,
                title: '',
                shadeClose: true,
                shade: 0.8,
                area: ['680px', '500px'],
                content: '<?=\yii\helpers\Url::to(['choose'])?>&level='+level //iframe的url
            });
        })
//        //点击确定修改价格
//        $('.sub .ck').click(function () {
//            var price_id = $('.relate').attr('price');
//            var price = $("#price").val();
//            var mprice = $("#month_price").val();
//            if(!isInteger(price) || !isInteger(mprice)){
//                layer.msg('钱数必须是整数');
//                return false;
//            }
//            $.ajax({
//                url : '<?//=\yii\helpers\Url::to(['modify-price'])?>//',
//                type : 'get',
//                dataType : 'json',
//                data : {'price_id':price_id, 'price': price,'month_price':mprice},
//                success:function (phpdata) {
//                    if(phpdata){
//                        layer.msg('保存成功！');
//                        window.location.href="index.php?r=config%2Fzone-price%2Fzone";
//                    }else{
//                        layer.msg('保存失败！');
//                    }
//                },error:function (phpdata) {
//                    layer.msg('操作失败！');
//                }
//            });
//        })
//        function isInteger(x) {
//            return x % 1 === 0;
//        }
        //删除
        $(".del").click(function () {
            var crr = [];
            $('.zone p').children(':checkbox').each(function () {
                if($(this).is(':checked')){
                    crr.push($(this).val())
                }
            })
            if(crr.length < 1){
                layer.msg('请选择要删除的地区！');
                return false;
            }
            layer.confirm('您确定要删除这些地区吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                var url = "<?=\yii\helpers\Url::to(['batch-delete'])?>";
                var data = new Object();
                data.drr = crr;
                var parameters = new Object();
                parameters._data = data;
                parameters._url = url;
                parameters._success = '删除成功';
                parameters._error = '删除失败';
                var $data = parameters._data;
                var $url = parameters._url;
                var $success = parameters._success;
                var $error = parameters._error;
                $.ajax({
                    url: $url,
                    type: 'get',
                    data: $data,
                    async: false,
                    success:function (phpdata) {
                        if(!phpdata){
                            layer.msg($error);
                            return false;
                        }
                        if($success){
                            if(phpdata){
                                layer.msg($success);
                                location.reload();
                                return false;
                            }
                        }
                    },error:function () {
                        layer.msg($error);
                        return false;
                    }
                })
            }, function(){
                layer.msg('您已取消操作！');
            });
        })
        //全选
        $('#all').change(function(){
            $('.zone p').children(':checkbox').prop('checked',$(this).is(':checked')?true:false);
        });
    })
</script>


