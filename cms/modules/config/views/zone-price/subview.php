<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \cms\models\SystemAddress;
//$this->title = $model->area_id;
$this->params['breadcrumbs'][] = ['label' => '每日补助设置', 'url' => ['subsidy']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php echo $this->render('layout/tab')?>
<style type="text/css">
    .left{width: 98%; padding-top: 10px;}
    /*border:1px #cccccc solid;*/
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
<div class="row">
    <form class="form-inline col-md-3">
        <div class="form-group">
            <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
            <div class="input-group">
                <div class="input-group-addon">补贴价格：</div>
                <input type="text" id="price" class="form-control" placeholder="请输入补贴价格" value="<?=Html::encode(\cms\modules\config\models\SystemZoneList::getPrice($price)/100)?>">
                <div class="input-group-addon">元</div>
            </div>
        </div>
    </form>
    <div class="form-group rel">
        <span class="relate" price="<?=Html::encode($price)?>">关联地区</span>
        <span class="del mleft" price="<?=Html::encode($price)?>">删除</span>
    </div>
</div>
<div class="checkbox">
    <label><input type="checkbox" id="all" value="">全选</label>
</div>
<div class="row">
    <ul class="left zone sybox">
        <? foreach ($arr as $k => $v) :?>
            <li>
<!--                --><?//=Html::encode(SystemAddress::getAreaNameById($k,5))?>
                <? foreach ($v as $kt => $vt) :?>
<!--                    --><?//=Html::encode(SystemAddress::getAreaByIdLen($kt,7))?>
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
    </ul>
</div>
<div class="row sub">
    <button type="button" class="btn btn-primary ck" data-type="pass">保存当前价格</button>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //点击查看详情
        $('.relate').click(function () {
            var price = $(this).attr('price');
            layer.open({
                type: 2,
                title: '',
                shadeClose: true,
                shade: 0.8,
                area: ['680px', '500px'],
                content: '<?=\yii\helpers\Url::to(['choose'])?>&price_id='+price //iframe的url
            });

        })
        //点击确定修改价格
        $('.sub .ck').click(function () {
            var price_id = $('.relate').attr('price');
            var price = $("#price").val();
            if(!isInteger(price)){
                layer.msg('钱数必须是整数');
                return false;
            }
            $.ajax({
                url : '<?=\yii\helpers\Url::to(['modify-price'])?>',
                type : 'get',
                dataType : 'json',
                data : {'price_id':price_id, 'price': price},
                success:function (phpdata) {
                    if(phpdata){
                        layer.msg('保存成功！');
                        window.location.href="index.php?r=config%2Fzone-price%2Fsubsidy";
                    }else{
                        layer.msg('保存失败！');
                    }
                },error:function (phpdata) {
                    layer.msg('操作失败！');
                }
            });
        })
        function isInteger(x) {
            return x % 1 === 0;
        }
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
                    type: 'POST',
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


